<?php

namespace App\Notifications;

use App\Models\User;
use App\Services\Jitsi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JitsiMeetingInvitation extends Notification implements ShouldQueue
{
    use Queueable;

    private string $roomName;

    public function __construct(String $roomName)
    {
        $this->roomName = $roomName;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Poštovani, pozvani ste na video konferenciju. Kliknite na link ispod za ptvaranje sastanka.')
                    ->action('Pridruži se sastanku', url('/'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
