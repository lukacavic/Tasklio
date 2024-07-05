<?php

namespace App\Filament\Project\Resources\LeadResource\Helpers\Actions;

use App\Filament\Project\Resources\LeadResource;
use App\Models\Lead;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Support\Colors\Color;
use JaOcero\ActivityTimeline\Components\ActivityDate;
use JaOcero\ActivityTimeline\Components\ActivityDescription;
use JaOcero\ActivityTimeline\Components\ActivityIcon;
use JaOcero\ActivityTimeline\Components\ActivitySection;
use JaOcero\ActivityTimeline\Components\ActivityTitle;

class HeaderActions
{
    public static function getHeaderActions(): array
    {
        return [
            Action::make('log_activity')
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
                ->action(function (array $data, Lead $record): void {
                    activity()
                        ->causedBy(auth()->user())
                        ->performedOn($record)
                        ->log($data['activity']);
                }),

            Action::make('activity_log')
                ->hiddenLabel()
                ->icon('heroicon-o-information-circle')
                ->modalSubmitAction(false)
                ->color(Color::Cyan)
                ->tooltip('Povijest aktivnosti')
                ->modalCancelAction(false)
                ->modalHeading('Povijest aktivnosti')
                ->slideOver()
                ->infolist(function (Infolist $infolist, Lead $record) {
                    return $infolist
                        ->state([
                            'activities' => $record->activities()->with('causer')->latest()->get()
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

            ActionGroup::make([
                Action::make('mark-as-lost')
                    ->label(function (Lead $record) {
                        return $record->lost ? 'Nije izgubljen' : 'Označi kao izgubljen';
                    })
                    ->action(function (Lead $lead) {
                        $logMessage = 'Označen kao ' . $lead->lost ? 'nije izgubljen' : 'izgubljen';

                        $lead->addLog($logMessage);

                        $lead->update([
                            'lost' => !$lead->lost
                        ]);

                    })
                    ->color(function (Lead $record) {
                        return $record->lost ? Color::Green : Color::Red;
                    })
                    ->icon('heroicon-o-user-minus'),

                EditAction::make()
                    ->hiddenLabel()
                    ->slideOver()
                    ->form(function (Form $form) {
                        return LeadResource::form($form);
                    })
                    ->icon('heroicon-o-pencil'),

                DeleteAction::make()
                    ->successRedirectUrl(LeadResource::getUrl('index'))
                    ->hiddenLabel()
                    ->icon('heroicon-o-trash'),
            ])->button()
                ->hiddenLabel()
                ->icon('heroicon-o-bars-4'),


        ];
    }
}
