<?php

namespace App\Filament\App\Resources\ProjectResource\Pages;

use App\Filament\App\Resources\ProjectResource;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;

class ProjectDocuments extends ManageRelatedRecords
{
    use HasPageSidebar;

    protected static string $resource = ProjectResource::class;

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
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Naslov'),
                Tables\Columns\TextColumn::make('user.fullName')
                    ->label('Upisao'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Vrijeme kreiranja'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Učitaj dokument')
                    ->icon('heroicon-o-paper-clip'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
