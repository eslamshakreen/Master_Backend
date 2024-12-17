<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewLeadMail extends Mailable
{
    use Queueable, SerializesModels;

    public $lead;

    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    public function build()
    {
        // نفترض لديك ملف PDF في storage/app/public/pdf/free-lecture.pdf
        // تأكد من إنشاء المسار ورفع الملف
        return $this->subject('محاضرة مجانية من منصتنا')
            ->view('emails.new-lead') // سنتحدث عنه بعد قليل
            ->attachFromStorage('public/pdf/Coursera RLZ4Y9FVBTEY.pdf', 'Coursera RLZ4Y9FVBTEY.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
