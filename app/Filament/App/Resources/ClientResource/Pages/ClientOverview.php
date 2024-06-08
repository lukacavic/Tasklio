<?php

namespace App\Filament\App\Resources\ClientResource\Pages;

use App\Filament\App\Resources\ClientResource;
use App\Filament\App\Widgets\CalendarWidget;
use App\Models\Client;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Form;
use Filament\Actions;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Support\Colors\Color;
use Illuminate\Database\Eloquent\Model;
use JaOcero\ActivityTimeline\Components\ActivityDate;
use JaOcero\ActivityTimeline\Components\ActivityDescription;
use JaOcero\ActivityTimeline\Components\ActivityIcon;
use JaOcero\ActivityTimeline\Components\ActivitySection;
use JaOcero\ActivityTimeline\Components\ActivityTitle;

class ClientOverview extends Page
{
    use HasPageSidebar, InteractsWithRecord;

    protected static string $resource = ClientResource::class;

    protected static string $view = 'filament.app.resources.client-resource.pages.client-overview';

    protected static ?string $title = 'Pregled';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    protected function getHeaderWidgets(): array
    {
        return [

        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('log_activity')
                ->hiddenLabel()
                ->icon('heroicon-o-plus-circle')
                ->color(Color::Blue)
                ->tooltip('Dodaj aktivnost')
                ->modalSubmitActionLabel('Spremi')
                ->modalHeading('Dodaj aktivnost')
                ->slideOver()
                ->form(function (Form $form) {
                    return $form
                        ->schema([
                            RichEditor::make('activity')
                                ->required()
                                ->columnSpanFull()
                                ->grow(true)
                                ->hiddenLabel()
                        ]);
                })
                ->action(function (array $data, Client $record): void {
                    activity()
                        ->causedBy(auth()->user())
                        ->performedOn($record)
                        ->log($data['activity']);
                }),
            Actions\Action::make('activity_log')
                ->hiddenLabel()
                ->icon('heroicon-o-information-circle')
                ->modalSubmitAction(false)
                ->color(Color::Cyan)
                ->tooltip('Povijest aktivnosti')
                ->modalCancelAction(false)
                ->modalHeading('Povijest aktivnosti')
                ->slideOver()
                ->infolist(function (Infolist $infolist) {
                    return $infolist
                        ->state([
                            'activities' => $this->getRecord()->activities()->with('causer')->latest()->get()
                        ])
                        ->schema([
                            ActivitySection::make('activities')
                                ->schema([
                                    ActivityTitle::make('causer.fullName')
                                        ->placeholder('No title is set')
                                        ->allowHtml(),
                                    ActivityDescription::make('description')
                                        ->placeholder('No description is set')
                                        ->allowHtml(),
                                    ActivityDate::make('created_at')
                                        ->date('F j, Y g:i A', 'Asia/Manila'),
                                    ActivityIcon::make('status')
                                        ->icon(fn(string|null $state): string|null => match ($state) {
                                            'ideation' => 'heroicon-m-light-bulb',
                                            'drafting' => 'heroicon-m-bolt',
                                            'reviewing' => 'heroicon-m-document-magnifying-glass',
                                            'published' => 'heroicon-m-rocket-launch',
                                            default => null,
                                        })
                                        ->color(fn(string|null $state): string|null => match ($state) {
                                            'ideation' => 'purple',
                                            'drafting' => 'info',
                                            'reviewing' => 'warning',
                                            'published' => 'success',
                                            default => 'gray',
                                        }),
                                ])
                                ->showItemsCount(2)
                                ->emptyStateHeading('No activities yet.')
                                ->emptyStateDescription('Check back later for activities that have been recorded.')
                                ->emptyStateIcon('heroicon-o-bolt-slash')
                                ->showItemsLabel('Prikaži starije')
                                ->showItemsIcon('heroicon-m-chevron-down')
                                ->showItemsColor('gray')
                                ->aside(true)
                                ->headingVisible(false)
                        ]);
                }),
            Actions\Action::make('edit')
            ->icon('heroicon-o-pencil')
            ->hiddenLabel()
            ->url(function () {
                return EditClient::getUrl(['record' => $this->getRecord()]);
            }),
            Actions\DeleteAction::make()
                ->hiddenLabel()
                ->icon('heroicon-o-trash'),
        ];
    }
}
