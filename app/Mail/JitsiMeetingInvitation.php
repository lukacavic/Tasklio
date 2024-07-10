<?php

namespace App\Mail;

use App\Models\User;
use App\Services\Jitsi;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JitsiMeetingInvitation extends Mailable
{
    use Queueable, SerializesModels;

    private string $roomName;

    private string $email;

    private string $meetingUrl;

    private string $message;

    public function __construct(string $email, string $roomName, $message)
    {
        $this->roomName = $roomName;
        $this->email = $email;

        $this->createJitsiUrl();
        $this->message = $message;
    }

    private function createJitsiUrl(): void
    {
        $jitsi = new Jitsi();
        $user = new User();
        $user->email = $this->email;
        $user->name = $this->email;
        $roomName = $this->roomName;

        $token = $jitsi->generateJwt($user, $this->roomName);

        $this->meetingUrl = url()->query("https://meet.rinels.hr/{$roomName}", ['jwt' => $token]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Poziv za video sastanak',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.jitsi-meeting-invitation',
            with: [
                'meetingUrl' => $this->meetingUrl,
                'message' => $this->message,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
