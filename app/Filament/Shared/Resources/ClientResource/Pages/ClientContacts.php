<?php

namespace App\Filament\Shared\Resources\ClientResource\Pages;

use App\Filament\Shared\Resources\ClientResource;
use App\Models\Contact;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClientContacts extends ManageRelatedRecords
{
    use HasPageSidebar;

    protected static string $resource = ClientResource::class;

    protected static string $relationship = 'contacts';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $title = 'Kontakti';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('full_name')
                    ->label('Ime i prezime')
                    ->required()
                    ->maxLength(255),
                TextInput::make('position')
                    ->label('Pozicija/Titula')
                    ->maxLength(255),
                TextInput::make('email')
                    ->unique(Contact::class, 'email', ignoreRecord: true)
                    ->prefixIcon('heroicon-o-at-symbol')
                    ->label('Email')
                    ->email()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->prefixIcon('heroicon-o-phone')
                    ->label('Telefon')
                    ->maxLength(255),

            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Nema učitanih kontakata')
            ->emptyStateDescription('Dodajte novi kontakt za klijenta')
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('full_name')
                    ->description(function (Contact $record) {
                        return $record->position;
                    })
                    ->label('Ime i prezime'),

                TextColumn::make('email')
                    ->label('Email')
                    ->copyable()
                    ->copyMessage('Email adresa kopirana'),
                TextColumn::make('phone')
                    ->label('Telefon'),

                TextColumn::make('created_at')
                    ->label('Vrijeme kreiranja')
                    ->since(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Dodaj')
                    ->modalHeading('Novi kontakt'),
            ])
            ->actions([
                EditAction::make()
                    ->modalHeading('Izmjena kontakta'),
                DeleteAction::make()
                    ->modalHeading('Brisanje kontakta?')
                    ->hiddenLabel(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
}
