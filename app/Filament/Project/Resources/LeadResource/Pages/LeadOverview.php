<?php

namespace App\Filament\Project\Resources\LeadResource\Pages;

use App\Filament\Project\Resources\LeadResource;
use App\Filament\Project\Resources\LeadsResource\Widgets\LeadStatsOverview;
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
use JaOcero\ActivityTimeline\Components\ActivityDate;
use JaOcero\ActivityTimeline\Components\ActivityDescription;
use JaOcero\ActivityTimeline\Components\ActivityIcon;
use JaOcero\ActivityTimeline\Components\ActivitySection;
use JaOcero\ActivityTimeline\Components\ActivityTitle;

class LeadOverview extends Page implements HasForms, HasInfolists
{
    use HasPageSidebar, InteractsWithRecord;

    use InteractsWithInfolists;
    use InteractsWithForms;

    protected static ?string $title = 'Pregled';

    protected static string $resource = LeadResource::class;

    protected static string $view = 'filament.project.resources.lead-resource.pages.lead-overview';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    protected function getHeaderWidgets(): array
    {
        return [
            LeadStatsOverview::make()
        ];
    }

    public function leadInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record)
            ->state([
                'activities' => $this->record->activities()->with('causer')->latest()->get()
            ])
            ->schema([
                ShoutEntry::make('alert-is-converted')
                    ->visible(function ($record) {
                        return $record->client_id != null;
                    })
                    ->color('warning')
                    ->content("Lead je pretvoren u klijenta.")
                    ->hintAction(function () {
                        Action::make('goto-client')
                            ->label('Prikaži klijenta');
                    }),
                ActivitySection::make('activities')
                    ->label('Povijest aktivnosti')
                    ->description('Prikaz zadnjih aktivnosti za lead.')
                    ->schema([
                        ActivityTitle::make('causer.fullName')
                            ->placeholder('No title is set')
                            ->allowHtml(),
                        ActivityDescription::make('description')
                            ->placeholder('No description is set')
                            ->allowHtml(),
                        ActivityDate::make('created_at')
                            ->date('F j, Y', 'Asia/Manila')
                            ->placeholder('No date is set.'),
                        ActivityIcon::make('status')
                            ->color(fn (string | null $state): string | null => match ($state) {
                                'ideation' => 'purple',
                                'drafting' => 'info',
                                'reviewing' => 'warning',
                                'published' => 'success',
                                default => 'gray',
                            }),
                    ])
                    ->showItemsCount(10)
                    ->showItemsLabel('Prikaži starije')
                    ->showItemsIcon('heroicon-m-chevron-down')
                    ->showItemsColor('gray')
                    ->headingVisible(true)
                    ->extraAttributes(['class'=>'my-new-class'])
            ]);
    }

    protected function getHeaderActions(): array
    {
        return LeadResource\Helpers\Actions\HeaderActions::getHeaderActions();
    }

}
