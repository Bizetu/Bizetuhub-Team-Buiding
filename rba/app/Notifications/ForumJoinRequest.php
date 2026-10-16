<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\Forum;
use App\Models\ForumMember;

class ForumJoinRequest extends Notification
{
    use Queueable;

    public $requester;
    public $forum;
    public $memberRequest;

    public function __construct(User $requester, Forum $forum, ForumMember $memberRequest)
    {
        $this->requester = $requester;
        $this->forum = $forum;
        $this->memberRequest = $memberRequest;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'requester_id' => $this->requester->id,
            'requester_name' => $this->requester->name_value,
            'forum_id' => $this->forum->id,
            'forum_title' => $this->forum->title,
            'member_request_id' => $this->memberRequest->id,
            'message' => $this->requester->name_value . ' wants to join "' . $this->forum->title . '"',
            'url' => route('community.forums.requests', $this->forum->id),
            'type' => 'forum_join_request',
            'created_at' => now()->toDateTimeString(),
        ];
    }
}