<?php

namespace App\Filament\Project\Resources\TaskResource\Pages;

use App\Filament\Project\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use App\TaskPriority;
use App\TaskStatus;
use Filament\Actions;
use Filament\Actions\ActionGroup;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\SpatieTagsEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Colors\Color;
use Illuminate\Contracts\Support\Htmlable;

class ViewTask extends ViewRecord
{
    protected static string $resource = TaskResource::class;

    public function getRecordTitle(): string|Htmlable
    {
        return $this->record->title;
    }

    protected function getActions(): array
    {
        return [
            $this->getChangeAssignedUsersAction(),
            $this->getMarkAsCompletedAction(),
            $this->getSwitchStatusActions(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Osnovne informacije')
                ->schema([
                    TextEntry::make('title')
                        ->label('Naziv'),

                    TextEntry::make('userCreated.fullName')
                        ->label('Kreirao'),

                    SpatieTagsEntry::make('tags')
                        ->label('Oznake'),

                    TextEntry::make('priority_id')
                        ->badge()
                        ->color(TaskPriority::class)
                        ->formatStateUsing(function (Task $record) {
                            return TaskPriority::from($record->priority_id);
                        })
                        ->label('Prioritet'),

                    TextEntry::make('members.first_name')
                        ->label('Djelatnici na zadatku')
                        ->separator(','),

                    TextEntry::make('status_id')
                        ->label('Status')
                        ->formatStateUsing(function (Task $record) {
                            return TaskStatus::from($record->status_id);
                        }),

                    TextEntry::make('deadline_at')
                        ->label('Rok završetka')
                        ->date()
                        ->since(),

                    TextEntry::make('description')
                        ->label('Opis zadatka')
                        ->formatStateUsing(function (Task $record) {
                            return strip_tags($record->description);
                        })
                        ->columnSpanFull()
                ])->columns(4),
        ]);
    }

    private function getDynamicStatusActions(): array
    {
        $actions = [];

        foreach (TaskStatus::cases() as $taskStatus) {
            if ($taskStatus->value == $this->record->status_id) continue;

            $action = new Actions\Action($taskStatus->getLabel());
            $action->label($taskStatus->getLabel());
            $action->color($taskStatus->getColor());
            $action->action(function ($data) use ($taskStatus) {
                $this->record->updateTaskStatus($taskStatus->value);
            });
            $action->icon(TaskStatus::from($this->record->status_id)->getIcon());

            $actions[$taskStatus->getLabel()] = $action;
        }

        return $actions;
    }

    public function getMarkAsCompletedAction(): Actions\Action
    {
        return Actions\Action::make('mark-completed')
            ->visible(function (Task $record) {
                return $record->status_id != TaskStatus::Completed->value;
            })
            ->hiddenLabel()
            ->tooltip('Označi zadatak završenim')
            ->requiresConfirmation()
            ->modalHeading('Označiti zadatak završenim?')
            ->color(Color::Green)
            ->icon('heroicon-o-check')
            ->action(function (Task $record) {
                $record->update([
                    'status_id' => TaskStatus::Completed
                ]);

                $this->getRecord()->refresh();
            });
    }

    private function getChangeAssignedUsersAction(): Actions\Action
    {
        return Actions\Action::make('updatTaskUsers')
            ->visible(function (Task $record) {
                return $record->status_id != TaskStatus::Completed->value;
            })
            ->hiddenLabel()
            ->tooltip('Promjena djelatnika na zadatku')
            ->modalHeading('Promjena djelatnika na zadatku')
            ->fillForm(fn(Task $record): array => [
                'new-members' => array_values($record->members->pluck('id')->toArray())
            ])
            ->form([
                Select::make('new-members')
                    ->required()
                    ->options(Filament::getTenant()->users()->get()->pluck('fullName', 'id'))
                    ->native(false)
                    ->multiple(true)
                    ->label('Djelatnici na zadatku')
                    ->columnSpanFull()
            ])
            ->action(function (array $data, Task $record): void {
                $record->members()->sync($data['new-members']);

                Notification::make()
                    ->info()
                    ->body('Dodjeljeni ste na zadatak: ' . $record->title)
                    ->title('Novi zadatak')
                    ->sendToDatabase(User::find($data['new-members']));
            })
            ->icon('heroicon-o-user');
    }

    private function getSwitchStatusActions(): ActionGroup
    {
        return ActionGroup::make($this->getDynamicStatusActions())
            ->tooltip('Promjena statusa zadatka')
            ->label(function () {
                return TaskStatus::from($this->getRecord()->status_id)->getLabel();
            })->color(function () {
                return TaskStatus::from($this->getRecord()->status_id)->getColor();
            })->icon(TaskStatus::from($this->record->status_id)->getIcon())
            ->button();
    }
}
