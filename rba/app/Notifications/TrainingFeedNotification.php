<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TrainingFeedNotification extends Notification
{
    use Queueable;

    protected $type;
    protected $subject;
    protected $message;
    protected $modelId;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $type, string $subject, string $message, int $modelId)
    {
        $this->type = $type;
        $this->subject = $subject;
        $this->message = $message;
        $this->modelId = $modelId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->line($this->message)
            ->action('View Training Feed', route('profile.trainingFeed'))
            ->line('Thank you for being part of our community!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => $this->type,
            'subject' => $this->subject,
            'message' => $this->message,
            'model_id' => $this->modelId,
            'url' => route('profile.trainingFeed'),
        ];
    }

    /**
     * Get the database representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => $this->type,
            'subject' => $this->subject,
            'message' => $this->message,
            'model_id' => $this->modelId,
            'url' => route('profile.trainingFeed'),
        ];
    }
}