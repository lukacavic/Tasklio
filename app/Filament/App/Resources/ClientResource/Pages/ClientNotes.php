<?php

namespace App\Filament\App\Resources\ClientResource\Pages;

use App\Filament\App\Resources\ClientResource;
use App\Models\Client;
use App\Models\Document;
use App\Models\Note;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class ClientNotes extends ManageRelatedRecords
{
    use HasPageSidebar;

    protected static string $resource = ClientResource::class;

    protected static string $relationship = 'notes';

    protected static ?string $navigationIcon = 'heroicon-o-pencil';

    protected static ?string $title = 'Napomene';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Naslov')
                    ->required()
                    ->maxLength(255),
                Forms\Components\ToggleButtons::make('priority')
                    ->boolean()->label('Bitna napomena')
                    ->inline()
                ->default(false)->columns(1),
                TinyEditor::make('content')
                    ->label('Sadržaj')
                    ->required(),
                Forms\Components\SpatieMediaLibraryFileUpload::make('attachments')
                    ->multiple()
                    ->label('Privitci')
                    ->downloadable()
            ])->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Nema učitanih napomena')
            ->emptyStateDescription('Učitajte novu za početak')
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->icon(function(Note $record) {
                        if($record->media()->exists()) {
                            return 'heroicon-o-paper-clip';
                        }

                        return null;
                    })
                    ->description(function(Model $record) {
                        return Str::limit(strip_tags($record->content), 40);
                    })
                    ->label('Naslov'),
                Tables\Columns\TextColumn::make('user.fullName')
                    ->label('Dodao'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Vrijeme kreiranja')
                    ->since(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('send')
                    ->hiddenLabel()
                    ->icon('heroicon-o-envelope'),
                Tables\Actions\EditAction::make(),
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