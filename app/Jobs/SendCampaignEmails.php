<?php

namespace App\Jobs;

use App\Models\Campaign;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCampaignEmails implements \Illuminate\Contracts\Queue\ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public $campaign;

    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function handle()
    {
        $recipients = collect();

        switch ($this->campaign->target) {
            case 'leads':
                $recipients = Lead::all();
                break;
            case 'students':
                $recipients = User::where('role', 'student')->get();
                break;
            case 'all':
                $leads = Lead::all();
                $students = User::where('role', 'student')->get();
                $recipients = $leads->concat($students);
                break;
        }

        foreach ($recipients as $recipient) {
            // dd('before mail', $this->campaign, $recipient->email); // أضف هذا
            Mail::to($recipient->email)->queue(new \App\Mail\CampaignMail($this->campaign, $recipient->name ?? ''));
        }

    }
}
