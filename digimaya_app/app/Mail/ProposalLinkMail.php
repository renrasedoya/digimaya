<?php

namespace App\Mail;

use App\Models\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProposalLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Proposal $proposal)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Proposal dari Digimaya: ' . $this->proposal->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.proposal-link',
            with: [
                'proposal' => $this->proposal,
                'link' => route('public.proposal.show', $this->proposal->public_token),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
