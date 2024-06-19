<?php

namespace App\Filament\Project\Resources\LeadResource\Pages;

use App\Filament\Project\Resources\LeadResource;
use App\Models\LeadStatus;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListLeads extends ListRecords
{
    protected static string $resource = LeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = ['all' => Tab::make('Svi')->badge($this->getModel()::count())];

        $leadStatuses = LeadStatus::orderBy('order', 'asc')
            ->withCount('leads')
            ->get();

        foreach ($leadStatuses as $leadStatus) {
            $name = $leadStatus->name;
            $slug = str($name)->slug()->toString();

            $tabs[$slug] = Tab::make($name)
                ->badge($leadStatus->leads_count)
                ->modifyQueryUsing(function ($query) use ($leadStatus) {
                    return $query->where('status_id', $leadStatus->id);
                });
        }

        return $tabs;
    }
}
