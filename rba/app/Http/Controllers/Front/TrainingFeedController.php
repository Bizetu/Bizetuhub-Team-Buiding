<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserLicense;
use App\Models\UserFollow;
use App\Models\TrainingComment;
use App\Models\TrainingLike;
use App\Notifications\TrainingFeedNotification;

class TrainingFeedController extends Controller
{
    /**
     * Display training feed showing activity from followed users
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        // Get IDs of users the current user is following (accepted follows only)
        $followingIds = UserFollow::where('follower_id', $user->id)
            ->accepted()
            ->pluck('following_id')
            ->toArray();

        // Get licenses from followed users
        $licensesQuery = UserLicense::query()
            ->with(['course', 'user.company', 'user.jobRole', 'completion'])
            ->whereIn('user_id', $followingIds)
            ->where('published', true)
            ->whereHas('course') // Only licenses with valid courses
            ->orderBy('created_at', 'desc');

        // Apply filters if present
        if ($request->filled('year')) {
            $licensesQuery->whereYear('created_at', $request->year);
        }

        if ($request->filled('month')) {
            $licensesQuery->whereMonth('created_at', $request->month);
        }

        if ($request->filled('status')) {
            // Filter based on score from completion
            if ($request->status === 'completed') {
                $licensesQuery->whereHas('completion', function($q) {
                    $q->where('score', '>', 0.79);
                });
            } elseif ($request->status === 'failed') {
                $licensesQuery->whereHas('completion', function($q) {
                    $q->whereBetween('score', [0.2, 0.79]);
                });
            } elseif ($request->status === 'in_progress') {
                $licensesQuery->whereDoesntHave('completion');
            }
        }

        $licenses = $licensesQuery->paginate(10);

        return view('site.pages.training-feed', [
            'user' => $user,
            'licenses' => $licenses,
        ]);
    }

    /**
     * Add a comment to a training activity
     */
    public function addComment(Request $request): JsonResponse
    {
        $request->validate([
            'license_id' => 'required|exists:user_licenses,id',
            'course_id' => 'required|exists:courses,id',
            'comment' => 'required|string|max:500',
            'parent_comment_id' => 'nullable|exists:training_comments,id',
        ]);

        $user = Auth::user();

        // Create comment
        $comment = TrainingComment::create([
            'user_id' => $user->id,
            'license_id' => $request->license_id,
            'course_id' => $request->course_id,
            'parent_comment_id' => $request->parent_comment_id,
            'message' => $request->comment,
        ]);

        // Send notification to license owner if not commenting on own license
        $license = UserLicense::find($request->license_id);
        if ($license->user_id !== $user->id) {
            $license->user->notify(new TrainingFeedNotification(
                'comment',
                'New Comment on Training',
                $user->name . ' commented on your training progress.',
                $comment->id
            ));
        }

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully',
            'comment' => [
                'id' => $comment->id,
                'message' => $comment->message,
                'user_name' => $user->name,
                'created_at' => $comment->created_at->diffForHumans(),
            ],
        ]);
    }

    /**
     * Toggle like on a training activity
     */
    public function toggleLike(Request $request): JsonResponse
    {
        $request->validate([
            'license_id' => 'required|exists:user_licenses,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        $user = Auth::user();

        // Check if like already exists
        $existingLike = TrainingLike::where('user_id', $user->id)
            ->where('like_id', $request->license_id)
            ->where('course_id', $request->course_id)
            ->first();

        if ($existingLike) {
            // Unlike
            $existingLike->delete();
            
            return response()->json([
                'success' => true,
                'action' => 'unliked',
                'message' => 'Like removed',
            ]);
        }

        // Add like
        TrainingLike::create([
            'user_id' => $user->id,
            'like_id' => $request->license_id,
            'course_id' => $request->course_id,
        ]);

        // Notify license owner
        $license = UserLicense::find($request->license_id);
        if ($license->user_id !== $user->id) {
            $license->user->notify(new TrainingFeedNotification(
                'like',
                'New Like on Training',
                $user->name . ' liked your training progress.',
                $license->id
            ));
        }

        return response()->json([
            'success' => true,
            'action' => 'liked',
            'message' => 'Liked successfully',
        ]);
    }

    /**
     * Get comments for a specific license
     */
    public function getComments(Request $request, int $licenseId, int $courseId): JsonResponse
    {
        $comments = TrainingComment::where('license_id', $licenseId)
            ->where('course_id', $courseId)
            ->whereNull('parent_comment_id') // Only top-level comments
            ->with(['user', 'replies.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $formattedComments = $comments->map(function ($comment) {
            return [
                'id' => $comment->id,
                'user_id' => $comment->user_id,
                'user_name' => $comment->user->name,
                'user_avatar' => $comment->user->profile_pic_avatar,
                'message' => $comment->message,
                'created_at' => $comment->created_at->diffForHumans(),
                'replies_count' => $comment->replies->count(),
                'replies' => $comment->replies->map(function ($reply) {
                    return [
                        'id' => $reply->id,
                        'user_name' => $reply->user->name,
                        'user_avatar' => $reply->user->profile_pic_avatar,
                        'message' => $reply->message,
                        'created_at' => $reply->created_at->diffForHumans(),
                    ];
                }),
            ];
        });

        return response()->json([
            'success' => true,
            'comments' => $formattedComments,
        ]);
    }

    /**
     * Get followers for a specific user
     */
    public function getFollowers(int $userId): JsonResponse
    {
        $user = User::findOrFail($userId);
        $followers = $user->followers()
            ->accepted()
            ->with('follower.jobRole')
            ->get()
            ->map(function($userFollow) {
                $followerUser = $userFollow->follower;
                
                return [
                    'id' => $followerUser->id,
                    'name' => $followerUser->name,
                    'profile_pic_avatar' => $followerUser->profile_pic_avatar,
                    'job_role' => $followerUser->job_role_name_value
                ];
            });
 
        return response()->json([
            'success' => true,
            'followers' => $followers
        ]);
    }
}