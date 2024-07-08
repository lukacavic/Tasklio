<?php

namespace App\Filament\Project\Resources;

use App\Filament\Project\Clusters\SettingsCluster\Resources\LeadSourceResource;
use App\Filament\Project\Resources\LeadResource\Pages\LeadDocuments;
use App\Filament\Project\Resources\LeadResource\Pages\LeadNotes;
use App\Filament\Project\Resources\LeadResource\Pages\LeadOverview;
use App\Filament\Project\Resources\LeadResource\Pages\LeadTasks;
use App\Filament\Project\Resources\LeadResource\Pages\ListLeads;
use App\Models\Lead;
use App\Models\LeadSource;
use AymanAlhattami\FilamentPageWithSidebar\FilamentPageSidebar;
use AymanAlhattami\FilamentPageWithSidebar\PageNavigationItem;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\Tables\PhoneColumn;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $navigationLabel = 'Potencijalni klijenti';

    protected static ?string $label = 'Potencijalni klijent';

    protected static ?string $pluralModelLabel = 'Potencijalni klijenti';

    protected static ?string $recordTitleAttribute = 'company';

    protected static ?string $navigationGroup = 'CRM';

    public static function getGloballySearchableAttributes(): array
    {
        return ['company', 'first_name', 'last_name', 'email'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Kontakt' => $record->fullName,
            'E' => $record->email,
            'M' => $record->mobile
        ];
    }

    public static function sidebar(Model $record): FilamentPageSidebar
    {
        return FilamentPageSidebar::make()
            ->setTitle($record->fullName)
            ->sidebarNavigation()
            ->setDescription(function (Lead $record) {
                return $record->lost ? 'IZGUBLJEN' : 'POTENCIJALNI KLIJENT';
            })
            ->setNavigationItems([
                PageNavigationItem::make('Pregled')
                    ->icon('heroicon-o-information-circle')
                    ->url(function () use ($record) {
                        return static::getUrl('overview', ['record' => $record->id]);
                    })
                    ->isActiveWhen(function () {
                        return request()->routeIs(LeadOverview::getRouteName());
                    }),

                PageNavigationItem::make('Dokumenti')
                    ->icon('heroicon-o-paper-clip')
                    ->badge(function () use ($record) {
                        return $record->media->count();
                    })
                    ->isActiveWhen(function () {
                        return request()->routeIs(LeadDocuments::getRouteName());
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
                        return request()->routeIs(LeadNotes::getRouteName());
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
                        return request()->routeIs(LeadTasks::getRouteName());
                    })
                    ->url(function () use ($record) {
                        return static::getUrl('tasks', ['record' => $record->id]);
                    }),

                /*PageNavigationItem::make('Kontakti')
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
                    }),*/
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('tabs')
                    ->columnSpanFull()
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Osnovne informacije')
                            ->columns(2)
                            ->schema([
                                Forms\Components\TextInput::make('first_name')
                                    ->label('Ime')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('last_name')
                                    ->label('Prezime')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('company')
                                    ->required()
                                    ->label('Tvrtka')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('position')
                                    ->maxLength(255)
                                    ->label('Pozicija'),

                                Forms\Components\TextInput::make('website')
                                    ->maxLength(255)
                                    ->label('Web stranica')
                                    ->prefix('https://'),

                                PhoneInput::make('mobile')
                                    ->live()
                                    ->unique('leads', 'mobile', ignoreRecord: true, modifyRuleUsing: function ($rule) {
                                        return $rule->where('organisation_id', auth()->user()->organisation_id);
                                    })
                                    ->afterStateUpdated(function (Forms\Contracts\HasForms $livewire, PhoneInput $component) {
                                        $livewire->validateOnly($component->getStatePath());
                                    })
                                    ->label('Mobitel'),

                                PhoneInput::make('phone')
                                    ->label('Telefon'),

                                Forms\Components\TextInput::make('email')
                                    ->prefixIcon('heroicon-o-at-symbol')
                                    ->live()
                                    ->email()
                                    ->unique('leads', 'email', ignoreRecord: true, modifyRuleUsing: function ($rule) {
                                        return $rule->where('organisation_id', auth()->user()->organisation_id);
                                    })
                                    ->afterStateUpdated(function (Forms\Contracts\HasForms $livewire, TextInput $component) {
                                        $livewire->validateOnly($component->getStatePath());
                                    })
                                    ->label('Email')
                                    ->maxLength(255),

                                Forms\Components\Select::make('status_id')
                                    ->native(false)
                                    ->required()
                                    ->options(Filament::getTenant()->leadStatuses->pluck('name', 'id'))
                                    ->label('Status'),

                                Forms\Components\Select::make('source_id')
                                    ->native(false)
                                    ->required()
                                    ->createOptionUsing(function (array $data) {
                                        $leadSource = LeadSource::query()->create([
                                            'project_id' => Filament::getTenant()->id,
                                            'name' => $data['name']
                                        ]);

                                        return $leadSource->getKey();
                                    })
                                    ->createOptionForm(fn(Form $form) => LeadSourceResource::form($form))
                                    ->options(Filament::getTenant()->leadSources->pluck('name', 'id'))
                                    ->label('Izvor'),

                                Forms\Components\Select::make('assigned_user_id')
                                    ->default(auth()->user()->id)
                                    ->options(Filament::getTenant()->users()->get()->pluck('fullName', 'id'))
                                    ->native(false)
                                    ->label('Djelatnik'),

                                Forms\Components\DatePicker::make('last_contact_at')
                                    ->label('Zadnji kontakt'),

                                SpatieTagsInput::make('tags')
                                    ->label('Oznake'),

                                Forms\Components\ToggleButtons::make('important')
                                    ->label('Bitan klijent')
                                    ->boolean()
                                    ->options([
                                        true => 'Da',
                                        false => 'Ne'
                                    ])
                                    ->colors([
                                        true => Color::Red,
                                        false => Color::Green
                                    ])
                                    ->default(false)
                                    ->inline(),

                                Forms\Components\Textarea::make('description')
                                    ->maxLength(65535)
                                    ->columnSpanFull()->label('Napomena'),
                            ]),

                        Forms\Components\Tabs\Tab::make('Adresa')
                            ->columns(2)
                            ->schema([
                                Forms\Components\TextInput::make('address')
                                    ->maxLength(255)
                                    ->label('Adresa'),

                                Forms\Components\TextInput::make('city')
                                    ->maxLength(255)
                                    ->label('Grad'),

                                Forms\Components\TextInput::make('zip_code')
                                    ->maxLength(255)
                                    ->label('Poštanski broj'),

                                Country::make('country')
                                    ->label('Država')
                                    ->default('HR'),
                            ])
                    ]),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(
                fn(Model $record): string => LeadOverview::getUrl([$record->id]),
            )
            ->columns([
                Tables\Columns\TextColumn::make('fullName')
                    ->label('Kontakt')
                    ->description(function (Lead $record) {
                        return $record->company;
                    })
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->copyable()
                    ->copyMessage('Email adresa je kopirana')
                    ->searchable(),

                PhoneColumn::make('phone')
                    ->searchable()
                    ->label('Telefon'),

                Tables\Columns\TextColumn::make('assignedUser.fullName')
                    ->label('Djelatnik')
                    ->searchable(),

                Tables\Columns\TextColumn::make('source.name')
                    ->label('Izvor')
                    ->badge()
                    ->searchable(),

                Tables\Columns\SelectColumn::make('status_id')
                    ->options(Filament::getTenant()->leadStatuses()->get()->pluck('name', 'id'))
                    ->searchable()
                    ->disabled(function (Lead $record) {
                        return $record->client_id != null;
                    })
                    ->rules(['required'])
                    ->label('Status'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Vrijeme dodavanja')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('last_contact_at')
                    ->label('Zadnji kontakt')
                    ->date()
                    ->description(function (Lead $record) {
                        if ($record->last_contact_at != null) {
                            return $record->last_contact_at->diffForHumans();
                        }

                        return null;
                    })
                    ->searchable(),

                SpatieTagsColumn::make('tags')
                    ->label('Oznake'),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
            'index' => ListLeads::route('/'),
            //'create' => CreateLead::route('/create'),
            //'edit' => EditLead::route('/{record}/edit'),
            'overview' => LeadOverview::route('/{record}/overview'),
            'documents' => LeadDocuments::route('/{record}/documents'),
            'notes' => LeadNotes::route('/{record}/notes'),
            'tasks' => LeadTasks::route('/{record}/tasks'),
        ];
    }
}
