<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Forum;
use App\Models\ForumMember;
use App\Models\ForumPost;
use App\Models\ForumBlock;
use App\Models\User;
use App\Notifications\ForumJoinRequest;
use App\Notifications\ForumJoinApproved;

class ForumController extends Controller
{
    /**
     * Check if user can create/manage forums (HR or Admin)
     */
    private function canManageForums(): bool
    {
        $user = auth()->guard('twill_users')->user();

        if (!$user || !$user->role_id) {
            return false;
        }

        return (
            $user->role_id === 1 ||
            $user->role_id === 3 ||
            $user->is_superadmin ||
            $user->is_company_admin === 1 ||
            $user->role_value === 'SUPERADMIN' ||
            $user->role_value === 'Company HR'
        );
    }

    /**
     * Display list of all forums
     */
    public function index(): View
    {
        $user = Auth::user();

        $forums = Forum::active()
            ->with(['creator', 'approvedMembers'])
            ->whereHas('creator')
            ->withCount('approvedMembers')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Add user's status for each forum
        $forums->getCollection()->transform(function ($forum) use ($user) {
            $forum->user_is_creator = $forum->isCreator($user);
            $forum->user_is_member = $forum->isMember($user);
            $forum->user_is_blocked = $forum->isBlocked($user);
            $forum->user_status = $forum->getMemberStatus($user);
            return $forum;
        });

        return view('site.pages.community.forums.index', [
            'user' => $user,
            'forums' => $forums,
            'canCreateForum' => $this->canManageForums(),
        ]);
    }

    /**
     * Show create forum form
     */
    public function create(): View
    {
        if (!$this->canManageForums()) {
            abort(403, 'Only HR and Admins can create forums');
        }

        return view('site.pages.community.forums.create', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Store new forum
     */
    public function store(Request $request): JsonResponse
    {
        if (!$this->canManageForums()) {
            return response()->json([
                'success' => false,
                'error' => 'Only HR and Admins can create forums'
            ], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:200',
        ]);

        $forum = Forum::create([
            'title' => $request->title,
            'description' => $request->description,
            'creator_id' => Auth::id(),
            'published' => true,
        ]);

        // Creator is automatically a member
        ForumMember::create([
            'forum_id' => $forum->id,
            'user_id' => Auth::id(),
            'status' => ForumMember::STATUS_APPROVED,
            'joined_at' => now(),
            'published' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Forum created successfully',
            'redirect' => route('community.forums.show', $forum->id),
        ]);
    }

    /**
     * Show single forum with posts
     */
    public function show(Forum $forum): View
    {
        $user = Auth::user();

        if (!$forum->creator) {
            abort(404);
        }

        // Check if user is blocked
        if ($forum->isBlocked($user)) {
            return view('site.pages.community.forums.blocked', [
                'user' => $user,
                'forum' => $forum,
            ]);
        }

        // Check if user is member or creator
        $isMember = $forum->isMember($user) || $forum->isCreator($user) || $forum->getMemberStatus($user) == 1;

        if (!$isMember) {
            // Show forum info but not posts
            return view('site.pages.community.forums.join', [
                'user' => $user,
                'forum' => $forum,
                'memberStatus' => $forum->getMemberStatus($user),
            ]);
        }

        // Load posts with replies
        $posts = ForumPost::where('forum_id', $forum->id)
            ->topLevel()
            ->notDeleted()
            ->with(['user', 'replies' => function($query) {
                $query->notDeleted()->with('user')->orderBy('created_at', 'asc');
            }])
            ->whereHas('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $pendingRequestsCount = ForumMember::where('forum_id', $forum->id)
            ->where('status', ForumMember::STATUS_PENDING)
            ->count();

        return view('site.pages.community.forums.show', [
            'user' => $user,
            'forum' => $forum,
            'posts' => $posts,
            'pendingRequestsCount' => $pendingRequestsCount,
            'isCreator' => $forum->isCreator($user),
        ]);
    }

    /**
     * Request to join forum
     */
    public function join(Request $request, Forum $forum): JsonResponse
    {
        $user = Auth::user();

        // Check if blocked
        if ($forum->isBlocked($user)) {
            return response()->json([
                'success' => false,
                'error' => 'You are blocked from this forum'
            ], 403);
        }

        // Check if already a member
        $existingMember = ForumMember::withTrashed()->where('forum_id', $forum->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingMember) {
            if ($existingMember->isApproved()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Already a member'
                ], 400);
            }
            if ($existingMember->isPending()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Join request already sent'
                ], 400);
            }
            // If rejected, allow them to request again
            if ($existingMember->isRejected()) {
                $existingMember->update([
                    'status' => ForumMember::STATUS_PENDING,
                    'deleted_at' => null
                ]);

                // Notify forum creator
                $forum->creator->notify(new ForumJoinRequest($user, $forum, $existingMember));

                return response()->json([
                    'success' => true,
                    'message' => 'Join request sent',
                ]);
            }
        }

        // Create join request
        $member = ForumMember::create([
            'forum_id' => $forum->id,
            'user_id' => $user->id,
            'status' => ForumMember::STATUS_PENDING,
            'published' => true,
        ]);

        // Notify forum creator
        $forum->creator->notify(new ForumJoinRequest($user, $forum, $member));

        return response()->json([
            'success' => true,
            'message' => 'Join request sent',
        ]);
    }

    /**
     * Approve join request (creator only)
     */
    public function approveMember(Request $request, Forum $forum, ForumMember $member): JsonResponse
    {
        $user = Auth::user();

        if (!$forum->isCreator($user)) {
            return response()->json([
                'success' => false,
                'error' => 'Only forum creator can approve members'
            ], 403);
        }

        $member->update([
            'status' => ForumMember::STATUS_APPROVED,
            'joined_at' => now(),
        ]);

        // Notify user
        $member->user->notify(new ForumJoinApproved($forum));

        return response()->json([
            'success' => true,
            'message' => 'Member approved',
        ]);
    }

    /**
     * Reject join request (creator only)
     */
    public function rejectMember(Request $request, Forum $forum, ForumMember $member): JsonResponse
    {
        $user = Auth::user();

        if (!$forum->isCreator($user)) {
            return response()->json([
                'success' => false,
                'error' => 'Only forum creator can reject members'
            ], 403);
        }

        $member->update([
            'status' => ForumMember::STATUS_REJECTED,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Member rejected',
        ]);
    }

    public function blockUser(Request $request, Forum $forum, User $targetUser): JsonResponse
    {
        $user = Auth::user();

        if (!$forum->isCreator($user)) {
            return response()->json([
                'success' => false,
                'error' => 'Only forum creator can block users'
            ], 403);
        }

        $request->validate([
            'reason' => 'nullable|string|max:255',
        ]);

        ForumMember::where('forum_id', $forum->id)
            ->where('user_id', $targetUser->id)
            ->delete();

        ForumPost::where('forum_id', $forum->id)
            ->where('user_id', $targetUser->id)

            ->delete();

        $existingBlock = ForumBlock::withTrashed()
            ->where('forum_id', $forum->id)
            ->where('user_id', $targetUser->id)
            ->first();

        if ($existingBlock) {
            $existingBlock->restore();
            $existingBlock->update([
                'blocked_by' => $user->id,
                'reason'     => $request->reason,
                'published'  => 1,
            ]);
        } else {
            ForumBlock::create([
                'forum_id'   => $forum->id,
                'user_id'    => $targetUser->id,
                'blocked_by' => $user->id,
                'reason'     => $request->reason,
                'published'  => 1,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'User blocked from forum',
        ]);
    }

    public function unblockUser(Request $request, Forum $forum, User $targetUser): JsonResponse
    {
        $user = Auth::user();

        if (!$forum->isCreator($user)) {
            return response()->json([
                'success' => false,
                'error' => 'Only forum creator can unblock users'
            ], 403);
        }

        if (!$this->canManageForums()) {
            return response()->json([
                'success' => false,
                'error' => 'Only HR and Admins can manage forum members'
            ], 403);
        }

        ForumBlock::where('forum_id', $forum->id)
            ->where('user_id', $targetUser->id)
            ->delete();

        ForumPost::withTrashed()
            ->where('forum_id', $forum->id)
            ->where('user_id', $targetUser->id)
            ->whereNotNull('deleted_at')
            ->restore();

        return response()->json([
            'success' => true,
            'message' => 'User unblocked successfully',
        ]);
    }

    /**
     * Remove member from forum (creator who is HR/Admin only)
     */
    public function removeMember(Request $request, Forum $forum, ForumMember $member): JsonResponse
    {
        $user = Auth::user();

        if (!$forum->isCreator($user)) {
            return response()->json([
                'success' => false,
                'error' => 'Only forum creator can remove members'
            ], 403);
        }

        if (!$this->canManageForums()) {
            return response()->json([
                'success' => false,
                'error' => 'Only HR and Admins can manage forum members'
            ], 403);
        }

        // Can't remove yourself
        if ($member->user_id === $user->id) {
            return response()->json([
                'success' => false,
                'error' => 'You cannot remove yourself from the forum'
            ], 400);
        }

        $userPosts = ForumPost::where('forum_id', $forum->id)->where('user_id', $member->user_id);
        $userPosts->forceDelete();
        $member->forceDelete();

        return response()->json([
            'success' => true,
            'message' => 'Member removed from forum',
        ]);
    }

    public function createPost(Request $request, Forum $forum): JsonResponse
    {
        $user = Auth::user();
        $isMember = $forum->isMember($user) || $forum->isCreator($user) || $forum->getMemberStatus($user) == 1;
        if (!$isMember) {
            return response()->json([
                'success' => false,
                'error' => 'Only members can post'
            ], 403);
        }

        if ($forum->isBlocked($user)) {
            return response()->json([
                'success' => false,
                'error' => 'You are blocked from this forum'
            ], 403);
        }

        $request->validate([
            'content' => 'required|string|max:5000',
            'parent_id' => 'nullable|exists:forum_posts,id',
        ]);

        $post = ForumPost::create([
            'forum_id' => $forum->id,
            'user_id' => $user->id,
            'parent_id' => $request->parent_id,
            'content' => $request->content,
            'published' => true,
        ]);

        $post->load('user');

        return response()->json([
            'success' => true,
            'message' => $request->parent_id ? 'Reply posted' : 'Post created',
            'post' => [
                'id' => $post->id,
                'content' => $post->content,
                'user_name' => $post->user->name_value,
                'user_avatar' => $post->user->profile_pic_url,
                'created_at' => $post->created_at->diffForHumans(),
            ],
        ]);
    }

    public function pendingRequests(Forum $forum): View
    {
        $user = Auth::user();

        if (!$forum->isCreator($user)) {
            abort(403, 'Only forum creator can view join requests');
        }

        $pendingRequests = ForumMember::where('forum_id', $forum->id)
            ->where('status', ForumMember::STATUS_PENDING)
            ->with(['user.company', 'user.jobRole'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('site.pages.community.forums.requests', [
            'user' => $user,
            'forum' => $forum,
            'pendingRequests' => $pendingRequests,
        ]);
    }

    /**
     * Manage forum members (creator who is HR/Admin only)
     */
    public function manageMembers(Request $request, Forum $forum): View
    {
        $user = Auth::user();

        // Check if user is creator AND (HR or Admin)
        if (!$forum->isCreator($user)) {
            abort(403, 'Only forum creator can manage members');
        }

        if (!$this->canManageForums()) {
            abort(403, 'Only HR and Admins can manage forum members');
        }

        // Get approved members
        $members = ForumMember::where('forum_id', $forum->id)
            ->where('status', ForumMember::STATUS_APPROVED)
            ->with(['user.company', 'user.jobRole'])
            ->orderBy('joined_at', 'desc')
            ->paginate(15, ['*'], 'members_page');

        // Get pending requests
        $pendingRequests = ForumMember::where('forum_id', $forum->id)
            ->where('status', ForumMember::STATUS_PENDING)
            ->with(['user.company', 'user.jobRole'])
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'pending_page');

        // Get blocked users
        $blockedUsers = ForumBlock::where('forum_id', $forum->id)
            ->with(['user.company', 'user.jobRole'])
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'blocked_page');

        return view('site.pages.community.forums.manage-members', [
            'user' => $user,
            'forum' => $forum,
            'members' => $members,
            'pendingRequests' => $pendingRequests,
            'blockedUsers' => $blockedUsers,
        ]);
    }
}
