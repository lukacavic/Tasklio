<?php

namespace App\Filament\Project\Resources\LeadResource\Pages;

use App\Filament\Project\Resources\LeadResource;
use Awcodes\Shout\Components\ShoutEntry;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use CodeWithDennis\SimpleAlert\SimpleAlert;
use Filament\Actions\Concerns\HasInfolist;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class LeadOverview extends Page implements  HasForms, HasInfolists
{
    use HasPageSidebar, InteractsWithRecord;

    use InteractsWithInfolists;
    use InteractsWithForms;

    protected static ?string $title = 'Pregled';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    protected static string $resource = LeadResource::class;

    protected static string $view = 'filament.project.resources.lead-resource.pages.lead-overview';

    public function leadInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record)
            ->schema([
                ShoutEntry::make('alert-is-converted')
                    ->visible(function($record){
                        return $record->client_id != null;
                    })
                ->color('warning')
                ->content("Lead je pretvoren u klijenta.")
                ->hintAction(function(){
                    Action::make('goto-client')
                        ->label('Prikaži klijenta');
                })
            ]);
    }

    protected function getHeaderActions(): array
    {
        return LeadResource\Helpers\Actions\HeaderActions::getHeaderActions();
    }

}
