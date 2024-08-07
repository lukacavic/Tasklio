<?php

namespace App\Filament\App\Resources;

use App\Filament\Clusters\Settings;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $pluralModelLabel = 'Djelatnici';

    protected static ?string $navigationLabel = 'Djelatnici';

    protected static ?string $navigationGroup = 'Šifrarnici';

    public static function canAccess(): bool
    {
        return auth()->user() && auth()->user()->administrator;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(fn($record) => [
                Forms\Components\TextInput::make('first_name')
                    ->label('Ime')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('last_name')
                    ->label('Prezime')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('name')
                    ->label('Korisničko ime')
                    ->live()
                    ->rules(Rule::unique('users', 'name')
                        ->where('organisation_id', filament()->getTenant()->id)
                    )
                    ->afterStateUpdated(function (Forms\Contracts\HasForms $livewire, TextInput $component) {
                        $livewire->validateOnly($component->getStatePath());
                    })
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->live()
                    ->email()
                    ->unique('users', 'email', ignoreRecord: true, modifyRuleUsing: function ($rule) {
                        return $rule->where('organisation_id', auth()->user()->organisation_id);
                    })
                    ->afterStateUpdated(function (Forms\Contracts\HasForms $livewire, TextInput $component) {
                        $livewire->validateOnly($component->getStatePath());
                    })
                    ->prefixIcon('heroicon-o-at-symbol')
                    ->required()
                    ->maxLength(255),

                TextInput::make('password')
                    ->password()
                    ->minValue(6)
                    ->maxValue(20)
                    ->confirmed()
                    ->required($record == null)
                    ->visible($record == null)
                    ->revealable()
                    ->label('Lozinka'),

                TextInput::make('password_confirmation')
                    ->password()
                    ->revealable()
                    ->minValue(6)
                    ->maxValue(20)
                    ->required($record == null)
                    ->visible($record == null)
                    ->label('Potvrda lozinke'),

                Forms\Components\Toggle::make('administrator')
                    ->default(false)
                    ->disabled(function () {
                        return !auth()->user()->administrator;
                    }),

                Forms\Components\Toggle::make('active')
                    ->default(true)
                    ->label('Aktivni djelatnik'),

                TinyEditor::make('email_signature')
                    ->profile('default')
                    ->label('Email potpis')
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->circular()
                    ->label('')
                    ->defaultImageUrl(function (User $record) {
                        return 'http://ui-avatars.com/api?name=' . $record->fullName;
                    }),

                Tables\Columns\TextColumn::make('fullName')
                    ->label('Djelatnik')
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Korisničko ime')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->iconPosition(IconPosition::After)
                    ->iconColor(function ($record) {
                        return $record->email_verified_at != null ? Color::Green : Color::Red;
                    })
                    ->icon(function ($record) {
                        return $record->email_verified_at != null ? 'heroicon-o-check-circle' : 'heroicon-o-no-symbol';
                    }),

                Tables\Columns\ToggleColumn::make('active')
                    ->label('Aktivan')
                    ->disabled(function (User $record) {
                        return $record->administrator;
                    })
                    ->sortable(),

                Tables\Columns\IconColumn::make('administrator')
                    ->label('Administrator')
                    ->visible(function () {
                        return auth()->user()->administrator;
                    })
                    ->boolean(),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
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
                Tables\Actions\EditAction::make()
                    ->modalHeading('Izmjena djelatnika')
                    ->visible(function (User $record) {
                        return auth()->user()->administrator;
                    }),

                Tables\Actions\DeleteAction::make()
                    ->visible(function (User $record) {
                        return auth()->user()->administrator;
                    }),
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
            'index' => \App\Filament\App\Resources\UserResource\Pages\ListUsers::route('/'),
            //'create' => Pages\CreateUser::route('/create'),
            //'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
