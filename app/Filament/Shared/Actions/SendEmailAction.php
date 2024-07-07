<?php

namespace App\Filament\Shared\Actions;

use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Mail;

class SendEmailAction extends Action
{
    protected ?string $name = 'send_email';
    private array|Closure|null $receivers = null;
    protected array|Closure|null $ccReceivers = null;
    protected ?string $subject = null;
    protected ?string $message = null;

    public function withReceivers(array|Closure|null $receivers): static
    {
        $this->extraAttributes['receivers'] = implode(',', $receivers);

        return $this;
    }

    public function withCcReceivers(array $ccReceivers): static
    {
        $this->ccReceivers = $ccReceivers;

        return $this;
    }

    public function withSubject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function withMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this
            ->label('Pošalji email')
            ->form([
                TextInput::make('receivers')
                    ->required()
                    ->label('Primatelji')
                    ->required()
                    ->placeholder('Enter receiver emails, separated by commas')
                    ->default(implode(',', $this->receivers ?? [])),
                TextInput::make('cc_receivers')
                    ->label('CC Primatelji')
                    ->placeholder('Enter CC emails, separated by commas')
                    ->default(implode(',', $this->ccReceivers ?? [])),
                TextInput::make('subject')
                    ->required()
                    ->label('Naslov')
                    ->required()
                    ->default($this->subject),
                Textarea::make('message')
                    ->required()
                    ->label('Sadržaj')
                    ->required()
                    ->default($this->message),
            ])
            ->action(function (array $data) {
                $receivers = array_map('trim', explode(',', $data['receivers']));
                $ccReceivers = $data['cc_receivers'] ? array_map('trim', explode(',', $data['cc_receivers'])) : [];
                $subject = $data['subject'];
                $message = $data['message'];

                Mail::raw($message, function ($mail) use ($receivers, $ccReceivers, $subject) {
                    $mail->to($receivers)
                        ->cc($ccReceivers)
                        ->subject($subject);
                });
            });
    }
}

