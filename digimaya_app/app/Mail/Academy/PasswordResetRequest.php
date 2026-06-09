<?php

namespace App\Mail\Academy;

use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetRequest extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Member $member, public string $token)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Digimaya] Reset password Digimaya Academy',
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.academy.password-reset-request-text',
            with: [
                'member' => $this->member,
                'resetUrl' => route('member.setup', $this->token),
                'loginUrl' => route('member.login'),
                'expiresHours' => 24,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
