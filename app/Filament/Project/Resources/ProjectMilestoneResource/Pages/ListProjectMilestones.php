<?php

namespace App\Filament\Project\Resources\ProjectMilestoneResource\Pages;

use App\Filament\Project\Pages\ProjectMilestoneKanBan;
use App\Filament\Project\Pages\TasksKanbanBoard;
use App\Filament\Project\Resources\ProjectMilestoneResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListProjectMilestones extends ListRecords
{
    protected static string $resource = ProjectMilestoneResource::class;

    public function getDefaultActiveTab(): string|int|null
    {
        return 'active';
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Svi')
                ->badge(Filament::getTenant()->projectMilestones()->count()),

            'active' => Tab::make('Aktivni')
                ->badge(Filament::getTenant()->projectMilestones()->current()->count())
                ->modifyQueryUsing(function ($query) {
                    $query->current();
                }),

            'expired' => Tab::make('Prošao rok')
                ->badge(Filament::getTenant()->projectMilestones()->past()->count())
                ->modifyQueryUsing(function ($query) {
                    $query->past();
                }),

            'future' => Tab::make('U planu')
                ->badge(Filament::getTenant()->projectMilestones()->future()->count())
                ->modifyQueryUsing(function ($query) {
                    $query->future();
                })
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('kanban-project-milestone')
                ->hiddenLabel()
                ->icon('heroicon-o-rectangle-group')
                ->tooltip('Kanban prikaz')
                ->url(fn(): string => ProjectMilestoneKanBan::getUrl()),
            Actions\CreateAction::make()
                ->label('Dodaj'),
        ];
    }

}
