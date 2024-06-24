<?php

namespace App\Filament\Project\Resources;

use App\Filament\Project\Resources\MeetingResource\Pages\ViewMeeting;
use App\Filament\Project\Resources\MeetingsResource\Pages;
use App\Models\Meeting;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Infolists\Components\SpatieTagsEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class MeetingsResource extends Resource
{
    protected static ?string $model = Meeting::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?string $label = 'Sastanak';

    protected static ?string $pluralLabel = 'Sastanci';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return 'Sastanci';
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Naslov' => $record->title,
        ];
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Naslov')
                        ->required()
                        ->columnSpanFull(),

                    Forms\Components\DateTimePicker::make('meeting_from')
                        ->label('Početak')
                        ->required(),

                    Forms\Components\DateTimePicker::make('meeting_to')
                        ->label('Kraj'),

                    Forms\Components\Textarea::make('description')
                        ->label('Opis sastanka')
                        ->columnSpanFull(),

                    Forms\Components\Select::make('userParticipants')
                        ->label('Djelatnici')
                        ->relationship('userParticipants')
                        ->options(Filament::getTenant()->users()->get()->pluck('fullName', 'id'))
                        ->multiple()
                        ->native(false),

                    Forms\Components\SpatieTagsInput::make('tags')
                        ->label('Oznake'),

                    TinyEditor::make('remarks')
                        ->label('Zapažanja sa sastanka')
                        ->maxHeight(500)
                        ->columnSpanFull(),

                    SpatieMediaLibraryFileUpload::make('media')
                        ->collection('meeting')
                        ->multiple()
                        ->label('Privitci')
                        ->columnSpanFull()
                ])->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Naslov')
                    ->description(function (Meeting $record) {
                        return Str::limit($record->description, 40);
                    }),

                Tables\Columns\TextColumn::make('userCreated.first_name')
                    ->label('Kreirao')
                    ->description(function (Meeting $record) {
                        return $record->created_at->diffForHumans();
                    }),

                Tables\Columns\TextColumn::make('meeting_from')
                    ->label('Vrijeme sastanka')
                    ->dateTime(),

                Tables\Columns\TextColumn::make('userParticipants.first_name')
                    ->label('Djelatnici'),

                Tables\Columns\SpatieTagsColumn::make('tags')
                    ->label('Oznake'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMeetings::route('/'),
            'create' => Pages\CreateMeetings::route('/create'),
            'edit' => Pages\EditMeetings::route('/{record}/edit'),
            'view' => ViewMeeting::route('/{record}'),
        ];
    }
}
