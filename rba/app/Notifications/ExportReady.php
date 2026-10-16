<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class ExportReady extends Notification implements ShouldQueue
{
    use Queueable;

    protected $filePath;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $downloadUrl = Storage::disk('public')->url($this->filePath);
        $fileName = basename($this->filePath);
        
        return (new MailMessage)
            ->subject('Report Download is Ready')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your course completion report has been generated successfully.')
            ->line('File: ' . $fileName)
            ->action('Download Report', url($downloadUrl))
            ->line('This download link will expire in 24 hours.')
            ->line('Thank you for using ' . config('app.name') . '!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'file_path' => $this->filePath,
            'file_name' => basename($this->filePath),
        ];
    }
}