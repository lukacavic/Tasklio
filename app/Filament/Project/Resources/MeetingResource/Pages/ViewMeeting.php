<?php

namespace App\Filament\Project\Resources\MeetingResource\Pages;

use App\Filament\Project\Resources\MeetingsResource;
use App\Mail\JitsiMeetingInvitation;
use App\Models\Meeting;
use Awcodes\Shout\Components\Shout;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Colors\Color;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;
use Spatie\MediaLibrary\Support\MediaStream;

class ViewMeeting extends ViewRecord
{
    protected static string $resource = MeetingsResource::class;

    public function getRecordTitle(): string|Htmlable
    {
        return $this->getRecord()->name;
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make()->schema([
                TextEntry::make('name')
                    ->label('Naslov'),

                TextEntry::make('meeting_from')
                    ->label('Datum i vrijeme sastanka')
                    ->dateTime(),

                TextEntry::make('finished_at')
                    ->label('Sastanak završio')
                    ->dateTime(),

                TextEntry::make('userCreated.fullName')
                    ->label('Kreirao'),

                TextEntry::make('userParticipants.first_name')
                    ->badge()
                    ->label('Djelatnici na sastanku'),

                TextEntry::make('description')
                    ->label('Opis sastanka')
                    ->columnSpanFull()
            ])->columns(3),

            Section::make('Zapažanja na sastanku')
                ->schema([
                    TextEntry::make('remarks')
                        ->html()
                        ->hiddenLabel()
                ])->columns(1),

            RepeatableEntry::make('media')
                ->visible(function (Meeting $record) {
                    return $record->media()->count() > 0;
                })
                ->label('Privitci')
                ->hintActions([
                    \Filament\Infolists\Components\Actions\Action::make('download')
                        ->action(function (Meeting $record) {
                            $downloads = $record->getMedia('meeting');

                            return MediaStream::create('attachments.zip')->addMedia($downloads);
                        })
                        ->label('Preuzmi sve')
                        ->icon('heroicon-m-arrow-down-tray'),

                ])
                ->columnSpanFull()
                ->grid(3)
                ->schema(function (Meeting $meeting) {
                    return [
                        TextEntry::make('name')
                            ->columnSpan(2)
                            ->label('Datoteka')
                            ->hintAction(
                                \Filament\Infolists\Components\Actions\Action::make('download')
                                    ->label('Preuzmi')
                                    ->action(function ($record) use ($meeting) {
                                        return response()->download($record->getPath(), $record->file_name);
                                    })
                                    ->hiddenLabel()
                                    ->icon('heroicon-m-arrow-down-tray')
                            )
                    ];
                })

        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('jitsi')
                ->label('Novi video poziv')
                ->visible(function (Meeting $record) {
                    return $record->finished_at == null;
                })
                ->icon('heroicon-o-video-camera')
                ->form([
                    Select::make('participants')
                        ->label('Sudionici')
                        ->multiple()
                        ->required()
                        ->options([
                            'luka.cavic@rinels.hr'=> 'luka.cavic@rinels.hr',
                            'nikolina.cavic@rinels.hr'=> 'nikolina.cavic@rinels.hr'
                        ])
                        ->native(false),
                ])
                ->action(function (array $data) {
                    $roomName = Str::random(10);

                    foreach ($data['participants'] as $key => $participant) {
                        Mail::to($participant)->send(new JitsiMeetingInvitation($participant, $roomName));
                    }

                    self::redirectRoute('jitsi.view-room', ['room' => $roomName]);
                })
                ->url(function () {
                    //return URL::route('jitsi.view-room', ['room' => 'mojasoba'], false);
                })->openUrlInNewTab(true),
            Actions\Action::make('edit-remarks')
                ->label('Uredi zapažanja')
                ->form([
                    TinyEditor::make('remarks')
                        ->label('Zapažanja')
                        ->required()
                ])
                ->modalHeading('Pošalji email')
                ->fillForm(fn(Meeting $record): array => [
                    'remarks' => $record->remarks,
                ])
                ->action(function (Meeting $record, array $data) {
                    $record->update([
                        'remarks' => $data['remarks']
                    ]);
                })
                ->icon('heroicon-o-pencil'),

            Actions\Action::make('mark-completed')
                ->label('Sastanak završen')
                ->visible(function (Meeting $record) {
                    return $record->finished_at == null;
                })
                ->form([
                    Shout::make('so-important')
                        ->content('Unesite zapažanja, zaključe sa sastanka. Označavanjem tipke Pošalji email definirajte primatelje. Email šalje zapisnik sastanka i upisani zaključak.')
                        ->color(Color::Orange),
                    TinyEditor::make('finished_note')
                        ->label('Zaključak sastanka')
                        ->required()
                        ->columnSpan(3),

                    Toggle::make('send-email')
                        ->live()
                        ->label('Pošalji email?'),

                    TagsInput::make('receivers')
                        ->label('Primatelji')
                        ->columnSpanFull()
                        ->visible(function (Get $get) {
                            return $get('send-email');
                        })
                        ->required(function (Get $get) {
                            return $get('send-email');
                        })
                        ->prefixIcon('heroicon-o-at-symbol')
                ])
                ->icon('heroicon-o-check')
                ->action(function (Meeting $record) {
                    $record->update([
                        'finished_at' => now()
                    ]);
                })
                ->color(Color::Green),

            Actions\EditAction::make()
                ->hiddenLabel()
                ->slideOver()
                ->icon('heroicon-o-pencil'),

            Actions\DeleteAction::make()
                ->hiddenLabel()
                ->icon('heroicon-o-trash')
        ];
    }
}
