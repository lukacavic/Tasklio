<?php

namespace App\Filament\Project\Resources\LeadResource\Pages;

use App\Filament\Project\Resources\LeadResource;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use CodeWithDennis\SimpleAlert\SimpleAlert;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class LeadOverview extends Page
{
    use HasPageSidebar, InteractsWithRecord;

    protected static ?string $title = 'Pregled';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    protected static string $resource = LeadResource::class;

    protected static string $view = 'filament.project.resources.lead-resource.pages.lead-overview';

    protected function getHeaderActions(): array
    {
        return LeadResource\Helpers\Actions\HeaderActions::getHeaderActions();
    }

}
