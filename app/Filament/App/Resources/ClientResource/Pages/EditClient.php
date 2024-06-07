<?php

namespace App\Filament\App\Resources\ClientResource\Pages;

use App\Filament\App\Resources\ClientResource;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Colors\Color;
use JaOcero\ActivityTimeline\Components\ActivityDate;
use JaOcero\ActivityTimeline\Components\ActivityDescription;
use JaOcero\ActivityTimeline\Components\ActivityIcon;
use JaOcero\ActivityTimeline\Components\ActivitySection;
use JaOcero\ActivityTimeline\Components\ActivityTitle;

class EditClient extends EditRecord
{
    use HasPageSidebar;

    protected static string $resource = ClientResource::class;

    protected static ?string $title = 'Izmjena klijenta';

    protected function getHeaderActions(): array
    {
        return [
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
                            'activities' =>$this->getRecord()->activities
                        ])
                        ->schema([
                            ActivitySection::make('activities')
                                ->schema([
                                    ActivityTitle::make('title')
                                        ->placeholder('No title is set')
                                        ->allowHtml(),
                                    ActivityDescription::make('description')
                                        ->placeholder('No description is set')
                                        ->allowHtml(),
                                    ActivityDescription::make('causer.fullName')
                                        ->allowHtml(),
                                    ActivityDate::make('created_at')
                                        ->date('F j, Y', 'Asia/Manila')
                                        ->placeholder('No date is set.'),
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
                                ->showItemsCount(10)
                                ->showItemsLabel('Prikaži starije')
                                ->showItemsIcon('heroicon-m-chevron-down')
                                ->showItemsColor('gray')
                                ->aside(true)
                                ->headingVisible(false)
                        ]);
                }),
            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash'),
        ];
    }
}
