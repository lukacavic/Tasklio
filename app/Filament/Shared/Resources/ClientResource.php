<?php

namespace App\Filament\Shared\Resources;

use App\Filament\Project\Resources\LeadResource\Pages\LeadTasks;
use App\Filament\Shared\Resources\ClientResource\Pages\ClientContacts;
use App\Filament\Shared\Resources\ClientResource\Pages\ClientDocuments;
use App\Filament\Shared\Resources\ClientResource\Pages\ClientNotes;
use App\Filament\Shared\Resources\ClientResource\Pages\ClientOverview;
use App\Filament\Shared\Resources\ClientResource\Pages\ClientTasks;
use App\Filament\Shared\Resources\ClientResource\Pages\ClientVault;
use App\Models\Client;
use App\Models\Contact;
use AymanAlhattami\FilamentPageWithSidebar\FilamentPageSidebar;
use AymanAlhattami\FilamentPageWithSidebar\PageNavigationItem;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

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

                PhoneInput::make('phone')
                    ->label('Telefon'),

                Forms\Components\TextInput::make('website')
                    ->label('Web stranica')
                    ->prefix('https://')
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

                Forms\Components\Select::make('project')
                    ->disabled(function () {
                        return Filament::getCurrentPanel()->getId() == 'project';
                    })
                    ->default(function () {
                        if (Filament::getCurrentPanel()->getId() == 'project') {
                            return [Filament::getTenant()->id];
                        }

                        return null;
                    })
                    ->label('Projekt')
                    ->multiple()
                    ->relationship('projects', 'name')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(
                fn(Model $record): string => ClientOverview::getUrl([$record->id]),
            )
            ->modifyQueryUsing(function ($query) {
                if (Filament::getCurrentPanel()->getId() == 'project') {
                    return Client::query()->whereHas('projects', function ($query) {
                        return $query->where('project_id', Filament::getTenant()->id);
                    });
                }

                return Client::query();
            })
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Klijent')
                    ->description(function(Client $record) {
                        return $record->fullAddress;
                    })
                    ->searchable(),

                Tables\Columns\TextColumn::make('primaryContact.full_name')
                    ->label('Primarni kontakt')
                    ->description(function(Client $record) {
                        return $record->primaryContact?->position;
                    })
                    ->searchable(),

                Tables\Columns\TextColumn::make('primaryContact.email')
                    ->label('Primarni email')
                    ->copyable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('primaryContact.phone')
                    ->label('Telefon')
                    ->copyable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('website')
                    ->label('Web stranica')
                    ->searchable(),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Vrijeme kreiranja')
                    ->description(function(Client $record){
                        return $record->created_at->diffForHumans();
                    })
                    ->date()
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

    public static function sidebar(Model $record): FilamentPageSidebar
    {
        return FilamentPageSidebar::make()
            ->setTitle($record->name)
            ->sidebarNavigation()
            ->setDescription('KLIJENT')
            ->setNavigationItems([
                PageNavigationItem::make('Pregled')
                    ->icon('heroicon-o-information-circle')
                    ->url(function () use ($record) {
                        return static::getUrl('overview', ['record' => $record->id]);
                    })
                    ->isActiveWhen(function () {
                        return request()->routeIs(ClientOverview::getRouteName());
                    }),
                PageNavigationItem::make('Kontakti')
                    ->icon('heroicon-o-users')
                    ->badge(function () use ($record) {
                        return $record->contacts->count();
                    })
                    ->isActiveWhen(function () {
                        return request()->routeIs(ClientContacts::getRouteName());
                    })
                    ->url(function () use ($record) {
                        return static::getUrl('contacts', ['record' => $record->id]);
                    }),
                PageNavigationItem::make('Dokumenti')
                    ->icon('heroicon-o-paper-clip')
                    ->badge(function () use ($record) {
                        return $record->media->count();
                    })
                    ->isActiveWhen(function () {
                        return request()->routeIs(ClientDocuments::getRouteName());
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

                PageNavigationItem::make('Zadaci')
                    ->icon('heroicon-o-check-circle')
                    ->badge(function () use ($record) {
                        return $record->tasks->count();
                    })
                    ->isActiveWhen(function () {
                        return request()->routeIs(ClientTasks::getRouteName());
                    })
                    ->url(function () use ($record) {
                        return static::getUrl('tasks', ['record' => $record->id]);
                    }),

                PageNavigationItem::make('Trezor')
                    ->icon('heroicon-o-lock-open')
                    ->badge(function () use ($record) {
                        return $record->vaults->count();
                    })
                    ->isActiveWhen(function () {
                        return request()->routeIs(ClientVault::getRouteName());
                    })
                    ->url(function () use ($record) {
                        return static::getUrl('vaults', ['record' => $record->id]);
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Shared\Resources\ClientResource\Pages\ListClients::route('/'),
            'contacts' => ClientContacts::route('/{record}/contacts}'),
            // 'create' => Pages\CreateClient::route('/create'),
            'overview' => \App\Filament\Shared\Resources\ClientResource\Pages\ClientOverview::route('/{record}/overview'),
            'documents' => \App\Filament\Shared\Resources\ClientResource\Pages\ClientDocuments::route('/{record}/documents'),
            'edit' => \App\Filament\Shared\Resources\ClientResource\Pages\EditClient::route('/{record}/edit'),
            'vaults' => \App\Filament\Shared\Resources\ClientResource\Pages\ClientVault::route('/{record}/vaults'),
            'notes' => ClientNotes::route('/{record}/notes'),
            'tasks' => ClientTasks::route('/{record}/tasks'),
        ];
    }
}
