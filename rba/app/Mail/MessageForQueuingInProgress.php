<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MessageForQueuingInProgress extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $configLms = config()->get("settings.sendgrid");
        $asmGroupId = (int) $configLms['asm_group_id'];
        $unsubscribeLink = route('unsubscribe', [
            'token'    => encrypt($this->data['recipient_email']),
            'group_id' => $asmGroupId
        ]);
        return $this->from($configLms['mail_from_address'], $this->data['sender'])
            ->subject("Keep going! Your modules are in progress")
            ->view('emails.inProgress', [
                'data'            => $this->data,
                'unsubscribeLink' => $unsubscribeLink
            ]);
    }
}
