<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Models\User;

class FollowAccepted extends Notification
{
    public $acceptedBy;

    public function __construct(User $acceptedBy)
    {
        $this->acceptedBy = $acceptedBy;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'user_id' => $this->acceptedBy->id,
            'user_name' => $this->acceptedBy->name_value,
            'message' => $this->acceptedBy->name_value . ' accepted your follow request',
            'url' => route('community.index'),
            'type' => 'follow_accepted',
            'created_at' => now()->toDateTimeString(),
        ];
    }
}