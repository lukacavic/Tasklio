<?php

namespace App\Filament\Project\Widgets;

use App\Filament\Project\Resources\LeadResource;
use App\Filament\Project\Resources\TaskResource;
use App\Models\Lead;
use App\Models\LeadStatus;
use App\Models\Task;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Widgets\Widget;
use Mokhosh\FilamentKanban\Pages\KanbanBoard;

class LeadsKanbanBoard extends KanbanBoard
{
    protected static ?string $slug = 'leads-kanban';

    protected static ?string $title = 'Leadovi';

    protected static string $model = Lead::class;

    protected static string $recordTitleAttribute = 'name';

    protected static string $recordStatusAttribute = 'status_id';

    protected static bool $shouldRegisterNavigation = false;

    protected function statuses(): \Illuminate\Support\Collection
    {
        return LeadStatus::get(['id', 'name'])->map(function ($leadStatus) {
            return [
                'id' => $leadStatus->id,
                'title' => $leadStatus->name,
            ];
        });
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('kanban')
                ->hiddenLabel()
                ->icon('heroicon-o-table-cells')
                ->tooltip('Kanban prikaz')
                ->url(fn(): string => LeadResource::getUrl()),

            CreateAction::make()
                ->model(Lead::class)
                ->mutateFormDataUsing(function ($data) {
                    $data['user_id'] = auth()->id();

                    return $data;
                })
        ];
    }

    protected function records(): \Illuminate\Support\Collection
    {
        return Lead::all();
    }
}