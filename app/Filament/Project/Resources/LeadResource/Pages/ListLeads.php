<?php

namespace App\Filament\Project\Resources\LeadResource\Pages;

use App\Filament\Project\Pages\TasksKanbanBoard;
use App\Filament\Project\Resources\LeadResource;
use App\Filament\Project\Widgets\LeadsKanbanBoard;
use App\Models\LeadStatus;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListLeads extends ListRecords
{
    protected static string $resource = LeadResource::class;

    public function getTabs(): array
    {
        $tabs = ['all' => Tab::make('Svi')->badge(Filament::getTenant()->leads()->count())];

        $leadStatuses = Filament::getTenant()->leadStatuses()->orderBy('order', 'asc')
            ->withCount('leads')
            ->get();

        foreach ($leadStatuses as $leadStatus) {
            $name = $leadStatus->name;
            $slug = str($name)->slug()->toString();

            $tabs[$slug] = Tab::make($name)
                ->badge($leadStatus->leads_count)
                ->modifyQueryUsing(function ($query) use ($leadStatus) {
                    if ($leadStatus != null) {
                        return $query->where('status_id', $leadStatus->id);
                    }
                    return $query;
                });
        }

        return $tabs;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('kanban')
                ->hiddenLabel()
                ->icon('heroicon-o-rectangle-group')
                ->tooltip('Kanban prikaz')
                ->url(fn(): string => LeadsKanbanBoard::getUrl()),

        ];
    }
}
