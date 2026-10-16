<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\UserMessage;

class NewMessage extends Notification
{
    use Queueable;

    public $sender;
    public $message;

    public function __construct(User $sender, UserMessage $message)
    {
        $this->sender = $sender;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name_value,
            'message_id' => $this->message->id,
            'message' => $this->sender->name_value . ' sent you a message',
            'url' => route('community.messages.show', $this->sender->id),
            'type' => 'new_message',
            'created_at' => now()->toDateTimeString(),
        ];
    }
}