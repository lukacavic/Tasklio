<?php

namespace App\Filament\Project\Resources;

use App\Filament\Project\Resources\LeadResource\Pages\ListLeads;
use App\Models\Lead;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\Project;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;
use Ysfkaya\FilamentPhoneInput\Tables\PhoneColumn;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $navigationLabel = 'Potencijalni klijenti';

    protected static ?string $label = 'Potencijalni klijent';

    protected static ?string $pluralModelLabel = 'Potencijalni klijenti';

    protected static ?string $recordTitleAttribute = 'company';

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Kontakt' => $record->fullName,
            'E' => $record->email,
            'M' => $record->mobile
        ];
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
                                ->required()
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
                                ->label('Mobitel'),

                            PhoneInput::make('phone')
                                ->label('Telefon'),

                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->label('Email')
                                ->maxLength(255),

                            Forms\Components\Select::make('status_id')
                                ->native(false)
                                ->required()
                                ->options(LeadStatus::get()->pluck('name', 'id'))
                                ->label('Status'),

                            Forms\Components\Select::make('source_id')
                                ->native(false)
                                ->required()
                                ->options(LeadSource::get()->pluck('name', 'id'))
                                ->label('Izvor'),

                            Forms\Components\Select::make('assigned_user_id')
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
                                ->columnSpanFull()->label('Opis'),
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

                SelectColumn::make('status.name')
                    ->options(Filament::getTenant()->leadStatuses()->get()->pluck('name', 'id'))
                    ->searchable()
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
        ];
    }
}
