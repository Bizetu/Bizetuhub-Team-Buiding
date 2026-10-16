<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\User;

class FollowDeclined extends Notification
{
    use Queueable;

    public $declinedBy;

    public function __construct(User $declinedBy)
    {
        $this->declinedBy = $declinedBy;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'user_id' => $this->declinedBy->id,
            'user_name' => $this->declinedBy->name_value,
            'message' => $this->declinedBy->name_value . ' declined your follow request',
            'url' => route('community.index'),
            'type' => 'follow_declined',
            'created_at' => now()->toDateTimeString(),
        ];
    }
}