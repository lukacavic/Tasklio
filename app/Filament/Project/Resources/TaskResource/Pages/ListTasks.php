<?php

namespace App\Filament\Project\Resources\TaskResource\Pages;

use App\Filament\Project\Pages\TasksKanbanBoard;
use App\Filament\Project\Resources\TaskResource;
use App\Models\Task;
use App\TaskStatus;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;

class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    public function getDefaultActiveTab(): string|int|null
    {
        return 'my-tasks';
    }

    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make('Svi')
                ->badge(Filament::getTenant()->tasks()->count()),

            'not-assigned' => Tab::make('Nedodojeljeni')
                ->badge(Filament::getTenant()->tasks()->whereDoesntHave('members')->count())
                ->modifyQueryUsing(function ($query) {
                    $query->whereDoesntHave('members');
                })
        ];

        $tabs['my-tasks'] = Tab::make('Moji zadaci')
            ->badge(function () {
                return Task::query()
                    ->where('project_id', Filament::getTenant()->id)
                    ->whereHas('members', function ($query) {
                        return $query->where('user_id', auth()->id());
                    })->count();
            })
            ->modifyQueryUsing(function ($query) {
                $query->whereHas('members', function ($query) {
                    return $query->where('user_id', auth()->id());
                });
            });

        foreach (TaskStatus::cases() as $taskStatus) {
            $name = $taskStatus->getLabel();
            $slug = str($name)->slug()->toString();

            $tabs[$slug] = Tab::make($name)
                ->badge(Filament::getTenant()->tasks()->where('status_id', $taskStatus->value)->count())
                ->modifyQueryUsing(function ($query) use ($taskStatus) {
                    if ($taskStatus != null) {
                        return $query->where('status_id', $taskStatus->value);
                    }
                    return $query;
                });
        }

        return $tabs;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('kanban')
                ->hiddenLabel()
                ->icon('heroicon-o-rectangle-group')
                ->tooltip('Kanban prikaz')
                ->url(fn(): string => TasksKanbanBoard::getUrl()),
            Actions\CreateAction::make()
                ->label('Novi zadatak')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
