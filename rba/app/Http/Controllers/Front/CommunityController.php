<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserLicense;
use App\Models\UserFollow;
use App\Models\UserMessage;
use App\Notifications\FollowRequest;
use App\Notifications\FollowAccepted;
use App\Notifications\NewMessage;

class CommunityController extends Controller
{
    /**
     * Display a listing of community members (users with similar courses)
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        
        // Get course IDs that the authenticated user is enrolled in
        $userCourseIds = UserLicense::where('user_id', $user->id)
            ->where('published', true)
            ->pluck('course_id')
            ->unique()
            ->toArray();

        // Get users who are enrolled in similar courses (excluding current user)
        $communityUsersQuery = User::query()
            ->published()
            ->where('id', '!=', $user->id)
            ->whereHas('licenses', function ($query) use ($userCourseIds) {
                $query->whereIn('course_id', $userCourseIds)->where('published', true);
            })
            ->with(['company', 'jobRole', 'department', 'branch'])
            ->select('twill_users.*')
            ->distinct();

        // Get follow statuses for these users
        $followingIds = UserFollow::where('follower_id', $user->id)
            ->accepted()
            ->pluck('following_id')
            ->toArray();

        $pendingIds = UserFollow::where('follower_id', $user->id)
            ->pending()
            ->pluck('following_id')
            ->toArray();

        $communityUsers = $communityUsersQuery->paginate(15);

        // Add follow status to each user
        $communityUsers->getCollection()->transform(function ($communityUser) use ($followingIds, $pendingIds, $user) {
            $communityUser->is_following = in_array($communityUser->id, $followingIds);
            $communityUser->follow_pending = in_array($communityUser->id, $pendingIds);
            
            // Check if they follow back
            $communityUser->follows_back = UserFollow::where('follower_id', $communityUser->id)
                ->where('following_id', $user->id)
                ->accepted()
                ->exists();
            
            return $communityUser;
        });

        return view('site.pages.community.index', [
            'user' => $user,
            'communityUsers' => $communityUsers,
        ]);
    }

    /**
     * Send a follow request to another user
     */
    public function follow(Request $request, User $targetUser): JsonResponse
    {
        $user = Auth::user();

        if ($user->id === $targetUser->id) {
            return response()->json(['error' => 'You cannot follow yourself'], 400);
        }

        // Check if already following or request exists
        $existingFollow = UserFollow::where('follower_id', $user->id)
            ->where('following_id', $targetUser->id)
            ->first();

        if ($existingFollow) {
            if ($existingFollow->isAccepted()) {
                return response()->json(['error' => 'Already following this user'], 400);
            }
            if ($existingFollow->isPending()) {
                return response()->json(['error' => 'Follow request already sent'], 400);
            }
        }

        // Create follow request
        $followRequest = UserFollow::create([
            'follower_id' => $user->id,
            'following_id' => $targetUser->id,
            'status' => UserFollow::STATUS_PENDING,
        ]);

        // Send notification to target user
        $targetUser->notify(new FollowRequest($user, $followRequest));

        return response()->json([
            'success' => true,
            'message' => 'Follow request sent!',
        ]);
    }

    /**
     * Unfollow a user
     */
    public function unfollow(Request $request, User $targetUser): JsonResponse
    {
        $user = Auth::user();

        UserFollow::where('follower_id', $user->id)
            ->where('following_id', $targetUser->id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Unfollowed successfully',
        ]);
    }

    /**
     * Display follow requests received
     */
    public function requests(Request $request): View
    {
        $user = Auth::user();

        $followRequests = UserFollow::where('following_id', $user->id)
            ->pending()
            ->with(['follower.company', 'follower.jobRole'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('site.pages.community.requests', [
            'user' => $user,
            'followRequests' => $followRequests,
        ]);
    }

    /**
     * Accept a follow request
     */
    public function acceptRequest(Request $request, UserFollow $followRequest): JsonResponse
    {
        $user = Auth::user();

        if ($followRequest->following_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $followRequest->update(['status' => UserFollow::STATUS_ACCEPTED]);

        // Notify the follower that request was accepted
        $followRequest->follower->notify(new FollowAccepted($user));

        return response()->json([
            'success' => true,
            'message' => 'Follow request accepted',
        ]);
    }

    /**
     * Decline a follow request
     */
    public function declineRequest(Request $request, UserFollow $followRequest): JsonResponse
    {
        $user = Auth::user();

        if ($followRequest->following_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $followRequest->update(['status' => UserFollow::STATUS_DECLINED]);

        return response()->json([
            'success' => true,
            'message' => 'Follow request declined',
        ]);
    }

    /**
     * Display list of users you're following
     */
    public function following(Request $request): View
    {
        $user = Auth::user();

        $following = User::query()
            ->published()
            ->whereHas('followers', function ($query) use ($user) {
                $query->where('follower_id', $user->id)
                    ->where('status', UserFollow::STATUS_ACCEPTED);
            })
            ->with(['company', 'jobRole'])
            ->paginate(15);

        return view('site.pages.community.following', [
            'user' => $user,
            'following' => $following,
        ]);
    }

    /**
     * Display list of your followers
     */
    public function followers(Request $request): View
    {
        $user = Auth::user();

        $followers = User::query()
            ->published()
            ->whereHas('following', function ($query) use ($user) {
                $query->where('following_id', $user->id)
                    ->where('status', UserFollow::STATUS_ACCEPTED);
            })
            ->with(['company', 'jobRole'])
            ->paginate(15);

        return view('site.pages.community.followers', [
            'user' => $user,
            'followers' => $followers,
        ]);
    }

    /**
     * Display messages overview
     */
    public function messages(Request $request): View
    {
        $user = Auth::user();

        // Get users with whom current user has conversations
        $conversations = DB::table('user_messages')
            ->select(
                DB::raw('CASE 
                    WHEN sender_id = ' . $user->id . ' THEN receiver_id 
                    ELSE sender_id 
                END as other_user_id'),
                DB::raw('MAX(created_at) as last_message_at'),
                DB::raw('SUM(CASE 
                    WHEN receiver_id = ' . $user->id . ' AND read_at IS NULL 
                    THEN 1 ELSE 0 
                END) as unread_count')
            )
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })
            ->groupBy('other_user_id')
            ->orderBy('last_message_at', 'desc')
            ->get();

        $conversationUsers = User::query()
            ->published()
            ->whereIn('id', $conversations->pluck('other_user_id'))
            ->with(['company', 'jobRole'])
            ->get()
            ->map(function ($conversationUser) use ($conversations) {
                $conversation = $conversations->firstWhere('other_user_id', $conversationUser->id);
                $conversationUser->last_message_at = $conversation->last_message_at;
                $conversationUser->unread_count = $conversation->unread_count;
                return $conversationUser;
            })
            ->sortByDesc('last_message_at')
            ->values();

        return view('site.pages.community.messages', [
            'user' => $user,
            'conversationUsers' => $conversationUsers,
        ]);
    }

    /**
    * Display conversation with a specific user
    */
    public function showMessages(Request $request, User $targetUser)
    {
        $user = Auth::user();

        // Check if either user has accepted the other's follow request
        $youFollowThem = UserFollow::where('follower_id', $user->id)
            ->where('following_id', $targetUser->id)
            ->where('status', UserFollow::STATUS_ACCEPTED)
            ->exists();

        $theyFollowYou = UserFollow::where('follower_id', $targetUser->id)
            ->where('following_id', $user->id)
            ->where('status', UserFollow::STATUS_ACCEPTED)
            ->exists();

        // Allow messaging if EITHER follow request is accepted
        if (!$youFollowThem && !$theyFollowYou) {
            return redirect()->route('community.index')->with('error', 'You can only message users who have accepted a follow request.');
        }

        // Get messages between users
        $messages = UserMessage::between($user->id, $targetUser->id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        UserMessage::where('sender_id', $targetUser->id)
            ->where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('site.pages.community.conversation', [
            'user' => $user,
            'targetUser' => $targetUser,
            'messages' => $messages,
        ]);
    }

    /**
    * Send a message to another user
    */
    public function sendMessage(Request $request, User $targetUser): JsonResponse
    {
        $user = Auth::user();

        // Check if either user has accepted the other's follow request
        $youFollowThem = UserFollow::where('follower_id', $user->id)
            ->where('following_id', $targetUser->id)
            ->where('status', UserFollow::STATUS_ACCEPTED)
            ->exists();

        $theyFollowYou = UserFollow::where('follower_id', $targetUser->id)
            ->where('following_id', $user->id)
            ->where('status', UserFollow::STATUS_ACCEPTED)
            ->exists();

        // Allow messaging if EITHER follow request is accepted
        if (!$youFollowThem && !$theyFollowYou) {
            return response()->json([
                'success' => false,
                'error' => 'You can only message users who have accepted a follow request'
            ], 403);
        }

        $request->validate([
            'message' => 'required|string|max:200',
        ]);

        $message = UserMessage::create([
            'sender_id' => $user->id,
            'receiver_id' => $targetUser->id,
            'message' => $request->message,
        ]);

        // Send notification
        $targetUser->notify(new NewMessage($user, $message));

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => [
                'id' => $message->id,
                'message' => $message->message,
                'created_at' => $message->created_at->format('M d, Y H:i'),
            ],
        ]);
    }
}