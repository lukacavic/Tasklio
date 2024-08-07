<?php

namespace App\Filament\Shared\Resources\ClientResource\Pages;

use App\Filament\Shared\Resources\ClientResource;
use App\Models\Document;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\MediaLibrary\Support\MediaStream;

class ClientDocuments extends ManageRelatedRecords
{
    use HasPageSidebar;

    protected static string $resource = ClientResource::class;

    protected static string $relationship = 'documents';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $title = 'Dokumenti';

    public static function getNavigationLabel(): string
    {
        return 'Media';
    }

    public function form(Form $form): Form
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
                    ->downloadable()
            ])->columns(1);
    }

    public function table(Table $table): Table
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

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Vrijeme kreiranja')
                    ->dateTime()
                    ->description(function(Document $record) {
                        return $record->created_at->diffForHumans();
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalHeading('Učitaj dokument')
                    ->label('Dodaj'),
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->hiddenLabel()
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (Document $record, $data) {
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
}
