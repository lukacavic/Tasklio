<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\LeadResource\Pages\ListLeads;
use App\Models\Lead;
use App\Models\Project;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Table;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $navigationLabel = 'Potencijalni klijenti';

    protected static ?string $label = 'Potencijalni klijenti';

    protected static ?string $navigationGroup = 'CRM';

    protected static ?string $pluralModelLabel = 'Potencijalni klijenti';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->label('Ime')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->label('Prezime')
                    ->maxLength(255),
                Forms\Components\TextInput::make('position')
                    ->maxLength(255)
                    ->label('Pozicija'),
                Forms\Components\TextInput::make('company')
                    ->required()
                    ->label('Tvrtka')
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->maxLength(255)
                    ->label('Adresa'),
                Forms\Components\TextInput::make('city')
                    ->maxLength(255)
                    ->label('Grad'),
                Forms\Components\TextInput::make('zip_code')
                    ->maxLength(255)->label('Poštanski broj'),

                Forms\Components\TextInput::make('country')
                    ->label('Država')
                    ->maxLength(255),

                Forms\Components\TextInput::make('website')
                    ->maxLength(255)->label('Web stranica')
                    ->prefix('http://'),

                Forms\Components\TextInput::make('mobile')
                    ->maxLength(255)->label('Mobitel'),

                Forms\Components\TextInput::make('phone')
                    ->tel()->label('Telefon')
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->email()->label('Email')
                    ->maxLength(255),

                Forms\Components\Select::make('status_id')
                    ->label('Status'),

                Forms\Components\Select::make('source_id')
                    ->label('Izvor'),

                Forms\Components\Select::make('assigned_user_id')
                    ->options(User::get()->pluck('fullName', 'id'))
                    ->label('Djelatnik'),

                Forms\Components\Select::make('project_id')
                    ->label('Projekt')
                    ->reactive()
                    ->options(Project::get()->pluck('name', 'id'))
                    ->native(false),

                Forms\Components\DatePicker::make('last_contact_at')
                    ->label('Zadnji kontakt'),

                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull()->label('Opis'),
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
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
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
                    ->label('Status'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Vrijeme dodavanja')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('last_contact_at')
                    ->label('Zadnji kontakt')
                    ->dateTime()
                    ->searchable(),

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
            //'create' => Pages\CreateLead::route('/create'),
            //'edit' => Pages\EditLead::route('/{record}/edit'),
        ];
    }
}
