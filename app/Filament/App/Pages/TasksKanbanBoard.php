<?php

namespace App\Filament\App\Pages;

use App\Models\Task;
use App\Models\User;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Actions\CreateAction;
use Mokhosh\FilamentKanban\Pages\KanbanBoard;

class TasksKanbanBoard extends KanbanBoard
{
    protected static ?string $title='Zadaci';
    protected static string $model = Task::class;
    protected static string $recordTitleAttribute = 'title';
    protected static string $recordStatusAttribute = 'status_id';
    protected static string $headerView = 'tasks-kanban.kanban-header';
   // protected static string $statusView = 'tasks-kanban.kanban-status';
    protected static string $recordView = 'tasks-kanban.kanban-record';
    protected function statuses(): \Illuminate\Support\Collection
    {
        return collect([
            ['id' => '1', 'title' => 'Kreiran'],
            ['id' => '2', 'title' => 'U izradi'],
            ['id' => '3', 'title' => 'Završen'],
        ]);
    }
    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make()
                ->model(Task::class)
                ->form([
                    TextInput::make('title'),
                    Textarea::make('description'),
                ])
                ->mutateFormDataUsing(function ($data) {
                    $data['user_id'] = auth()->id();

                    return $data;
                })
        ];
    }
    protected function records(): \Illuminate\Support\Collection
    {
        return Task::all();
    }
}
