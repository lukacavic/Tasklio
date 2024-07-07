<?php

namespace App\Notifications;

use App\Filament\Project\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use Filament\Actions\Action;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    public Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toDatabase(User $notifiable): array
    {
        $currentUser = auth()->user();

        return \Filament\Notifications\Notification::make()
            ->title('Zadatak riješen')
            ->icon('heroicon-o-check-circle')
            ->success()
            ->body("{$currentUser->first_name} je riješio zadatak. {$this->task->title}")
            ->actions([
                \Filament\Notifications\Actions\Action::make('view')
                    ->link()
                    ->label('Pregled')
                    ->icon('heroicon-s-eye')
                    ->url(fn() => TaskResource::getUrl('view', ['record' => $this->task])),
            ])
            ->getDatabaseMessage();
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }


}
