<?php

namespace App\Filament\Project\Resources\TaskResource\Pages;

use App\Filament\Project\Pages\TasksKanbanBoard;
use App\Filament\Project\Resources\TaskResource;
use App\TaskStatus;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Colors\Color;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    public function getTabs(): array
    {

        foreach (TaskStatus::cases() as $taskStatus)
            $tabs = [
                'created' => Tab::make('Kreiran')
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->where('status_id', TaskStatus::Created);
                    })
                    ->badge($this->getModel()::where('status_id', TaskStatus::Created)->count()),

                'in_progress' => Tab::make('U izradi')
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->where('status_id', TaskStatus::InProgress);
                    })
                    ->badge($this->getModel()::where('status_id', TaskStatus::InProgress)->count()),

                'testing' => Tab::make('Testiranje')
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->where('status_id', TaskStatus::Testing);
                    })
                    ->badge($this->getModel()::where('status_id', TaskStatus::Testing)->count()),

                'awaiting_feedback' => Tab::make('Čeka se komentar')
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->where('status_id', TaskStatus::AwaitingFeedback);
                    })
                    ->badge($this->getModel()::where('status_id', TaskStatus::AwaitingFeedback)->count()),

                'completed' => Tab::make('Završeno')
                    ->modifyQueryUsing(function (Builder $query) {
                        return $query->where('status_id', TaskStatus::Completed);
                    })
                    ->badge($this->getModel()::where('status_id', TaskStatus::Completed)->count())
            ];

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
