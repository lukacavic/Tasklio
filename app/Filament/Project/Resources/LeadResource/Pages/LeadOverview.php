<?php

namespace App\Filament\Project\Resources\LeadResource\Pages;

use App\Filament\App\Resources\ClientResource;
use App\Filament\App\Resources\ClientResource\Pages\EditClient;
use App\Filament\Project\Resources\LeadResource;
use App\Models\Client;
use App\Models\Lead;
use Awcodes\Shout\Components\Shout;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use CodeWithDennis\SimpleAlert\SimpleAlert;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Support\Colors\Color;
use JaOcero\ActivityTimeline\Components\ActivityDate;
use JaOcero\ActivityTimeline\Components\ActivityDescription;
use JaOcero\ActivityTimeline\Components\ActivityIcon;
use JaOcero\ActivityTimeline\Components\ActivitySection;
use JaOcero\ActivityTimeline\Components\ActivityTitle;

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
