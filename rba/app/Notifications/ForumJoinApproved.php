<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Forum;

class ForumJoinApproved extends Notification
{
    use Queueable;

    public $forum;

    public function __construct(Forum $forum)
    {
        $this->forum = $forum;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'forum_id' => $this->forum->id,
            'forum_title' => $this->forum->title,
            'message' => 'Your request to join "' . $this->forum->title . '" has been approved',
            'url' => route('community.forums.show', $this->forum->id),
            'type' => 'forum_join_approved',
            'created_at' => now()->toDateTimeString(),
        ];
    }
}