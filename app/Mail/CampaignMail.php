<?php

namespace App\Mail;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    public $campaign;
    public $recipient;

    public $recipientName;

    public function __construct(Campaign $campaign, $recipientName)
    {
        $this->campaign = $campaign;
        $this->recipientName = $recipientName;
    }

    public function build()
    {
        $mail = $this->subject($this->campaign->subject)
            ->view('emails.campaign')
            ->with([
                'campaign' => $this->campaign,
                'body' => $this->campaign->body,
                'name' => $this->recipientName,
            ]);

        if ($this->campaign->attachment) {
            $mail->attachFromStorageDisk('public', $this->campaign->attachment);
        }

        return $mail;
    }


}
