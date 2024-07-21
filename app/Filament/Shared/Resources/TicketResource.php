<?php

namespace App\Filament\Shared\Resources;

use App\Filament\Shared\Resources\TicketResource\Pages;
use App\Filament\Shared\Resources\TicketResource\RelationManagers;
use App\Models\Ticket;
use App\TicketStatus;
use Filament\Facades\Filament;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canAccess(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Podaci o upitu')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->required()
                                ->label('Naslov'),

                            Select::make('contact_id')
                                ->native(false)
                                ->relationship('contact', 'full_name')
                                ->label('Kontakt'),

                            Select::make('department_id')
                                ->required()
                                ->native(false)
                                ->relationship('department', 'name')
                                ->label('Odjel'),

                            SpatieTagsInput::make('tags')
                                ->label('Oznake'),

                            Select::make('assigned_user_id')
                                ->label('Dodjeljeno')
                                ->native(false)
                                ->options(Filament::getTenant()->users()->get()->pluck('fullName', 'id'))
                                ->relationship('assignedUser', 'first_name'),

                            TinyEditor::make('content')
                                ->required()
                                ->columnSpanFull()
                                ->label('Sadržaj')
                        ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->description(function(Ticket $record) {
                        return strip_tags(\Str::limit($record->content, 40));
                    })
                    ->label('Naslov'),

                Tables\Columns\SpatieTagsColumn::make('tags')
                    ->label('Oznake'),

                Tables\Columns\TextColumn::make('department.name')
                    ->label('Odjel'),

                Tables\Columns\TextColumn::make('contact.full_name')
                    ->label('Kontakt'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge(),

                Tables\Columns\TextColumn::make('last_reply_at')
                    ->label('Zadnji odgovor')
                    ->dateTime()
                    ->description(function (Ticket $record) {
                        return $record->last_reply_at != null ? $record->last_reply_at->diffForHumans() : null;
                    })

            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
