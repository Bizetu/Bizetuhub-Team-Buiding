<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use App\Models\Evaluation;

class EvaluationPublished extends Notification
{
    public $evaluation;

    public function __construct(Evaluation $evaluation)
    {
        $this->evaluation = $evaluation;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'evaluation_id' => $this->evaluation->id,
            'title' => $this->evaluation->title,
            'message' => 'New evaluation published: ' . $this->evaluation->title,
            'url' => route('home'),
            'published_at' => now()->toDateTimeString(),
        ];
    }
}