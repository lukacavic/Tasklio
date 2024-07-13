<?php

namespace App\Filament\Project\Resources\ProjectMilestoneResource\Pages;

use App\Filament\Project\Resources\ProjectMilestoneResource;
use App\Models\ProjectMilestone;
use App\Models\Task;
use App\TaskStatus;
use Awcodes\Shout\Components\Shout;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Colors\Color;

class ViewMilestone extends ViewRecord
{
    protected static string $resource = ProjectMilestoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('move-tasks')
                ->requiresConfirmation()
                ->after(function ($livewire) {
                    $livewire->dispatch('refreshRelation');
                })
                ->form(function ($record) {
                    return [
                        Shout::make('so-important')
                            ->content('Odaberite plan u koji želite prebaciti sve zadatke koji nisu završeni. ')
                            ->color('warning'),
                        Select::make('milestone_id')
                            ->native(false)
                            ->required()
                            ->label('Plan (Milestone)')
                            ->options(ProjectMilestone::whereNot('id', $record->id)->get()->pluck('name', 'id'))
                    ];
                })
                ->action(function ($record, $data) {
                    $tasks = Task::where('project_milestone_id', $record->id)
                        ->where('status_id', '!=', TaskStatus::Completed->value)
                        ->get();

                    foreach ($tasks as $task) {
                        $task->update([
                            'project_milestone_id' => $data['milestone_id'],
                        ]);
                    }

                    Notification::make()
                        ->success()
                        ->title('Zadaci su prebačeni.')
                        ->send();
                })
                ->icon('heroicon-o-arrow-right-circle')
                ->label('Prebaci neodrađene'),

            Actions\EditAction::make()
                ->hiddenLabel()
                ->icon('heroicon-o-pencil'),

            Actions\DeleteAction::make()->hiddenLabel()
                ->icon('heroicon-o-trash')
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Osnovne informacije')->schema([

                TextEntry::make('name')
                    ->label('Naziv'),

                TextEntry::make('start_at')
                    ->label('Početak'),

                TextEntry::make('deadline_at')
                    ->label('Rok za završetak'),

                TextEntry::make('description')
                    ->label('Opis')
                    ->columnSpanFull(),

            ])->columns(3)
        ]);
    }
}
