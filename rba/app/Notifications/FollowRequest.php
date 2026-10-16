<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\UserFollow;

class FollowRequest extends Notification
{
    public $follower;
    public $followRequest;

    public function __construct(User $follower, UserFollow $followRequest)
    {
        $this->follower = $follower;
        $this->followRequest = $followRequest;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'follower_id' => $this->follower->id,
            'follower_name' => $this->follower->name_value,
            'follow_request_id' => $this->followRequest->id,
            'message' => $this->follower->name_value . ' wants to follow you',
            'url' => route('community.requests'),
            'type' => 'follow_request',
            'created_at' => now()->toDateTimeString(),
        ];
    }
}