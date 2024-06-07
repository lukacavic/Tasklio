<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ClientResource\Pages\ClientContacts;
use App\Filament\App\Resources\ClientResource\Pages\ClientNotes;
use App\Filament\Resources\ClientResource\Pages;
use App\Models\Client;
use AymanAlhattami\FilamentPageWithSidebar\FilamentPageSidebar;
use AymanAlhattami\FilamentPageWithSidebar\PageNavigationItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Klijenti';

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

    public static function sidebar(Model $record): FilamentPageSidebar
    {
        return FilamentPageSidebar::make()
            ->setTitle($record->name)
            ->sidebarNavigation()
            ->setDescription('KLIJENT')
            ->setNavigationItems([
                PageNavigationItem::make('Osnovne informacije')
                    ->icon('heroicon-o-information-circle')
                    ->url(function () use ($record) {
                        return static::getUrl('edit', ['record' => $record->id]);
                    })
                    ->isActiveWhen(function () {
                        return request()->routeIs(\App\Filament\App\Resources\ClientResource\Pages\EditClient::getRouteName()) || request()->routeIs(\App\Filament\App\Resources\ClientResource\Pages\EditClient::getRouteName());
                    }),
                PageNavigationItem::make('Kontakti')
                    ->icon('heroicon-o-users')
                    ->isActiveWhen(function () {
                        return request()->routeIs(ClientContacts::getRouteName());
                    })
                    ->url(function () use ($record) {
                        return static::getUrl('contacts', ['record' => $record->id]);
                    }),
                PageNavigationItem::make('Dokumenti')
                    ->icon('heroicon-o-paper-clip')
                    ->isActiveWhen(function () {
                        return request()->routeIs(\App\Filament\App\Resources\ClientResource\Pages\ClientDocuments::getRouteName());
                    })
                    ->url(function () use ($record) {
                        return static::getUrl('documents', ['record' => $record->id]);
                    }),
                PageNavigationItem::make('Napomene')
                    ->icon('heroicon-o-pencil')
                    ->badge(function () use ($record) {
                        return $record->notes->count();
                    })
                    ->isActiveWhen(function () {
                        return request()->routeIs(ClientNotes::getRouteName());
                    })
                    ->url(function () use ($record) {
                        return static::getUrl('notes', ['record' => $record->id]);
                    }),
                PageNavigationItem::make('Trezor')
                    ->icon('heroicon-o-lock-open')
                    ->badge(function () use ($record) {
                        return $record->vaults->count();
                    })
                    ->isActiveWhen(function () {
                        return request()->routeIs(\App\Filament\App\Resources\ClientResource\Pages\ClientVault::getRouteName());
                    })
                    ->url(function () use ($record) {
                        return static::getUrl('vaults', ['record' => $record->id]);
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\App\Resources\ClientResource\Pages\ListClients::route('/'),
            'contacts' => ClientContacts::route('/{record}/contacts}'),
            // 'create' => Pages\CreateClient::route('/create'),
            'edit' => \App\Filament\App\Resources\ClientResource\Pages\EditClient::route('/{record}/edit'),
            'documents' => \App\Filament\App\Resources\ClientResource\Pages\ClientDocuments::route('/{record}/documents'),
            'vaults' => \App\Filament\App\Resources\ClientResource\Pages\ClientVault::route('/{record}/vaults'),
            'notes' => ClientNotes::route('/{record}/notes'),
        ];
    }
}
