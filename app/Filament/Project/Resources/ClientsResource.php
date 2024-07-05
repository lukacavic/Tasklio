<?php

namespace App\Filament\Project\Resources;

use App\Filament\Project\Resources\ClientsResource\Pages;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;

class ClientsResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Klijenti';

    protected static ?string $label = 'klijent';

    protected static ?string $pluralLabel = 'klijenti';

    protected static ?string $navigationGroup = 'CRM';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Klijent')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label('Telefon')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('website')
                    ->label('Web stranica')
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->label('Adresa')
                    ->maxLength(255),
                Forms\Components\TextInput::make('city')
                    ->label('Mjesto/Grad')
                    ->maxLength(255),
                Forms\Components\TextInput::make('zip_code')
                    ->label('Poštanski broj')
                    ->maxLength(255),
                Country::make('country')
                    ->label('Država'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Klijent')
                    ->searchable(),
                Tables\Columns\TextColumn::make('primaryContact.full_name')
                    ->label('Primarni kontakt')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('website')
                    ->label('Web stranica')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fullAddress')
                    ->label('Adresa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Vrijeme kreiranja')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListClients::route('/'),
            //'create' => Pages\CreateClients::route('/create'),
            //'edit' => Pages\EditClients::route('/{record}/edit'),
        ];
    }
}
