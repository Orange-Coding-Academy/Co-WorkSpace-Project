<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MarketingCampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $campaignContent;
    public string $workspaceName;

    public function __construct(string $campaignContent, string $workspaceName)
    {
        $this->campaignContent = $campaignContent;
        $this->workspaceName = $workspaceName;
    }

    public function build()
    {
        return $this->subject('Special Updates from ' . $this->workspaceName)
            ->view('emails.marketing-campaign');
    }
}