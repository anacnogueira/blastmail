<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Campaign;
use App\Models\CampaignEmail;

class EmailCampaign extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Campaign $campaign,
        public CampaignEmail $email
    )
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->campaign->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.email-campaign',
            with: [
                'body' => $this->getBody()
            ]
        );
    }

    public function getBody()
    {
        $body = $this->campaign->body;

        $pattern = '/href="([^"]*)"/';
        preg_match_all($pattern, $body, $matches);

        foreach ($matches[1] as $index => $oldValue) {
            $newValue = 'href="' . route('tracking.clicks', ['email' => $this->email, 'f' => $oldValue]) . '"';
            $body = str_replace($matches[0][$index], $newValue, $body);
        }

        return $body;
    }
}
