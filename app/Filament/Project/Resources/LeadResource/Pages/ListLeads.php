<?php

namespace App\Filament\Project\Resources\LeadResource\Pages;

use App\Filament\Project\Resources\LeadResource;
use App\Filament\Project\Widgets\LeadsKanbanBoard;
use App\Models\Lead;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListLeads extends ListRecords
{
    protected static string $resource = LeadResource::class;

    public function getTabs(): array
    {
        $myLeads = Lead::query()->where('assigned_user_id', auth()->user()->id);

        $tabs = [
            'all' => Tab::make('Svi')->badge(Filament::getTenant()->leads()->count())
        ];

        $tabs['my-leads'] = Tab::make('Moji leadovi')
            ->badge($myLeads->count())
            ->modifyQueryUsing(function ($query) use ($myLeads) {
                return $myLeads;
            });

        $leadStatuses = Filament::getTenant()->leadStatuses()
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
            /*ImportAction::make()
                ->importer(LeadsImporter::class),
            Actions\ExportAction::make()
                ->hiddenLabel()
                ->icon('heroicon-o-arrow-down-tray')
                ->tooltip('Izvoz u Excel')
                ->exporter(LeadsExporter::class),*/
            Actions\Action::make('kanban')
                ->hiddenLabel()
                ->icon('heroicon-o-rectangle-group')
                ->tooltip('Kanban prikaz')
                ->url(fn(): string => LeadsKanbanBoard::getUrl()),

        ];
    }
}
