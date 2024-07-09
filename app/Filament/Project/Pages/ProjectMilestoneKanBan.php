<?php

namespace App\Filament\Project\Pages;

use App\Filament\Project\Resources\LeadResource;
use App\Filament\Project\Resources\ProjectMilestoneResource;
use App\Filament\Project\Resources\TaskResource;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\Task;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Form;
use Mokhosh\FilamentKanban\Pages\KanbanBoard;

class ProjectMilestoneKanBan extends KanbanBoard
{
    protected static ?string $slug = 'project-milestone-kanban';

    protected static ?string $title = 'Prekretnice (Milestones)';

    protected static string $model = Task::class;

    protected static string $recordTitleAttribute = 'name';

    protected static string $recordStatusAttribute = 'project_milestone_id';

    protected static bool $shouldRegisterNavigation = false;

    protected static string $headerView = 'project-milestone-kanban.kanban-header';

    protected static string $recordView = 'project-milestone-kanban.kanban-record';

    public function form(Form $form): Form
    {
        return TaskResource::form($form)
            ->statePath('editModalFormState')
            ->model($this->editModalRecordId ? static::$model::find($this->editModalRecordId) : static::$model);
    }

    public function onStatusChanged(int $recordId, string $status, array $fromOrderedIds, array $toOrderedIds): void
    {
        $task = Task::find($recordId);

        $milestone = ProjectMilestone::find($status);

        $task->addLog("Prebačen u milestone {$milestone} ");

        $task->update(['project_milestone_id' => $status]);
    }

    protected function statuses(): \Illuminate\Support\Collection
    {
        return ProjectMilestone::all(['id', 'name'])->map(function ($projectMilestone) {
                return [
                    'id' => $projectMilestone->id,
                    'title' => $projectMilestone->name,
                ];
            });
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('table')
                ->hiddenLabel()
                ->icon('heroicon-o-table-cells')
                ->tooltip('Prikaz u tablici')
                ->url(fn(): string => ProjectMilestoneResource::getUrl()),
        ];
    }

    protected function records(): \Illuminate\Support\Collection
    {
        return Filament::getTenant()->tasks()->whereHas('projectMilestone')->get();
    }
}
