<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\App\Resources\ProjectResource;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class ProjectOverview extends Page
{
    use HasPageSidebar, InteractsWithRecord;

    protected static string $resource = ProjectResource::class;

    protected static ?string $title = 'Pregled';

    protected static string $view = 'filament.app.resources.project-resource.pages.project-overview';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

}
