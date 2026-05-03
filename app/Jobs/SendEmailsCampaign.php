<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\Campaign;
use App\Models\CampaignEmail;
use App\Mail\EmailCampaign;

class SendEmailsCampaign implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct( public Campaign $campaign)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->campaign->emailList->subscribers as $subscriber) {
            SendEmailCampaignJob::dispatch($this->campaign, $subscriber);
        }
    }
}
