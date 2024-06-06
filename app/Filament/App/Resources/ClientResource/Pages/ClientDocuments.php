<?php

namespace App\Filament\App\Resources\ClientResource\Pages;

use App\Filament\App\Resources\ClientResource;
use App\Filament\App\Resources\ProjectResource;
use App\Models\Document;
use App\Models\Note;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;

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
                Tables\Columns\TextColumn::make('user.fullName')
                    ->label('Dodao'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Vrijeme kreiranja')
                    ->since(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalHeading('Učitaj dokument')
                    ->label('Učitaj dokument')
                    ->icon('heroicon-o-paper-clip'),
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->hiddenLabel()
                    ->icon('heroicon-o-arrow-down-tray'),
                Tables\Actions\Action::make('send')
                    ->icon('heroicon-o-envelope')
                    ->modalWidth(MaxWidth::Full)
                    ->modalHeading('Slanje preko email-a')
                    ->hiddenLabel()
                    ->form([
                        Forms\Components\TagsInput::make('receivers')
                            ->label('Primatelji')
                            ->columnSpanFull()
                            ->required(),
                        TextInput::make('subject')
                            ->label('Naslov')
                            ->required()
                            ->formatStateUsing(function (Document $record) {
                                return $record->title;
                            })
                            ->columnSpanFull(),
                        RichEditor::make('message')
                            ->required()
                            ->label('Odgovor')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\SpatieMediaLibraryFileUpload::make('attachments')
                            ->multiple()
                            ->label('Privitci')
                            ->downloadable()
                    ])
                    ->requiresConfirmation()
                    ->action(function (array $data) {
                        Notification::make()
                            ->title('Email uspješno poslan')
                            ->success()
                            ->send();
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
