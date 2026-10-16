<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SiteSubmissionReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $submissionType,
        public readonly int $submissionId,
        public readonly array $details,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: ucfirst($this->submissionType).' request #'.$this->submissionId,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.site-submission-received',
        );
    }
}
