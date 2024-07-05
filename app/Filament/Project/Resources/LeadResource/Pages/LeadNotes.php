<?php

namespace App\Filament\Project\Resources\LeadResource\Pages;

use App\Filament\App\Resources\ClientResource;
use App\Filament\Project\Resources\LeadResource;
use App\Models\Client;
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
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class LeadNotes extends ManageRelatedRecords
{
    use HasPageSidebar;

    protected static string $resource = LeadResource::class;

    protected static string $relationship = 'notes';

    protected static ?string $navigationIcon = 'heroicon-o-pencil';

    protected static ?string $title = 'Napomene';

    protected function getHeaderActions(): array
    {
        return LeadResource\Helpers\Actions\HeaderActions::getHeaderActions();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Naslov')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('priority')
                    ->label('Bitna napomena')
                    ->inline()
                    ->default(false)
                    ->columns(1),
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
                    ->icon(function (Note $record) {
                        if ($record->media()->exists()) {
                            return 'heroicon-o-paper-clip';
                        }

                        return null;
                    })
                    ->description(function (Model $record) {
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
                Tables\Actions\CreateAction::make()
                    ->modalHeading('Nova napomena'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Izmjena napomene'),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Brisanje napomene')
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
