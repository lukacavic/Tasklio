<?php

namespace App\Filament\Project\Pages;

use App\Filament\Project\Resources\TaskResource;
use App\Filament\Shared\Tasks;
use App\Models\Task;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Mokhosh\FilamentKanban\Pages\KanbanBoard;

class TasksKanbanBoard extends KanbanBoard
{
    protected static ?string $slug = 'kanban';

    protected static ?string $title = 'Zadaci';

    protected static string $model = Task::class;

    protected static string $recordTitleAttribute = 'title';

    protected static string $recordStatusAttribute = 'status_id';

    protected static string $headerView = 'tasks-kanban.kanban-header';

    protected static string $recordView = 'tasks-kanban.kanban-record';

    protected static bool $shouldRegisterNavigation = false;

    public function recordClicked(int $recordId, array $data): void
    {
        $record = Task::find($recordId);

        $this->redirect(TaskResource::getUrl('view', ['record' => $record]));
    }

    protected function statuses(): \Illuminate\Support\Collection
    {
        return collect([
            ['id' => '1', 'title' => 'Kreiran'],
            ['id' => '2', 'title' => 'U izradi'],
            ['id' => '3', 'title' => 'Testiranje'],
            ['id' => '4', 'title' => 'Čeka se komentar'],
            ['id' => '5', 'title' => 'Završeno'],
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('tasks')
                ->hiddenLabel()
                ->icon('heroicon-o-table-cells')
                ->tooltip('Kanban prikaz')
                ->url(fn (): string => TaskResource::getUrl()),
            \Filament\Actions\CreateAction::make()
                ->model(Task::class)
                ->form(function($form) {
                    return Tasks::getForm($form);
                })
                ->mutateFormDataUsing(function ($data) {
                    $data['user_id'] = auth()->id();

                    return $data;
                })
        ];
    }

    public function form(Form $form): Form
    {
        return TaskResource::form($form)
            ->statePath('editModalFormState')
            ->model($this->editModalRecordId ? static::$model::find($this->editModalRecordId) : static::$model);
    }

    protected function records(): \Illuminate\Support\Collection
    {
        return Task::where('project_id', Filament::getTenant()->id)->get();
    }
}
