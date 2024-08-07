<?php

namespace App\Filament\Project\Resources;

use App\Filament\Project\Resources\DocumentResource\Pages;
use App\Filament\Project\Resources\DocumentResource\RelationManagers;
use App\Models\Document;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\MediaLibrary\Support\MediaStream;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-clip';

    protected static ?string $navigationLabel = 'Dokumenti';

    protected static ?string $label = 'Dokument';

    protected static ?string $pluralLabel = 'Dokumenti';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Naslov')
                    ->required()
                    ->maxLength(255),
                Forms\Components\SpatieMediaLibraryFileUpload::make('attachments')
                    ->multiple()
                    ->required()
                    ->label('Privitci')
                    ->downloadable(),

                Forms\Components\SpatieTagsInput::make('tags')
                    ->label('Oznake')
                    ->columnSpanFull(),

            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Nema učitanih dokumenata')
            ->emptyStateDescription('Učitajte novi dokument za projekt')
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->description(function (Document $record) {
                        return 'Ukupno ' . $record->media()->count() . ' dokumenata';
                    })
                    ->label('Naslov'),

                Tables\Columns\ImageColumn::make('user.avatar')
                    ->label('Dodao')
                    ->circular(),

                Tables\Columns\SpatieTagsColumn::make('tags')
                    ->label('Oznake'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Vrijeme kreiranja')
                    ->dateTime()
                    ->description(function (Document $record) {
                        return $record->created_at->diffForHumans();
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->hiddenLabel()
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (Document $record) {
                        $downloads = $record->getMedia();

                        return MediaStream::create('attachments.zip')->addMedia($downloads);
                    }),
                Tables\Actions\EditAction::make()
                    ->modalHeading('Izmjena dokumenta')
                    ->hiddenLabel(),
                Tables\Actions\DeleteAction::make()
                    ->hiddenLabel(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
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
            'index' => Pages\ListDocuments::route('/'),
            // 'create' => Pages\CreateDocument::route('/create'),
            //'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }
}
