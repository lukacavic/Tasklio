<?php

namespace App\Filament\Project\Resources\MeetingResource\Pages;

use App\Filament\Project\Resources\MeetingsResource;
use App\Models\KnowledgeArticle;
use App\Models\Meeting;
use Awcodes\Shout\Components\Shout;
use Filament\Actions;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Infolists\Components\Card;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\Support\Htmlable;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class ViewMeeting extends ViewRecord
{
    protected static string $resource = MeetingsResource::class;

    public function getRecordTitle(): string|Htmlable
    {
        return $this->getRecord()->name;
    }

    protected function getHeaderActions(): array
    {
        return [
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
                ])->columns(1)

        ]);
    }
}
