<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ProjectResource\Pages\EditProject;
use App\Filament\App\Resources\ProjectResource\Pages\ListProjects;
use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationLabel = 'Projekti';

    protected static ?string $navigationIcon = 'heroicon-o-command-line';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Naziv')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Toggle::make('internal')
                    ->label('Interni projekt')
                    ->live()
                    ->required(),

                Forms\Components\Select::make('client_id')
                    ->label('Klijent')
                    ->reactive()
                    ->options(Client::get()->pluck('name', 'id'))
                    ->visible(function (Get $get) {
                        return !$get('internal');
                    })
                    ->required(function (Get $get) {
                        return !$get('internal');
                    })
                    ->native(false)
                    ->options(Client::get()->pluck('name', 'id')),

                Forms\Components\Select::make('leader_id')
                    ->label('Voditelj projekta')
                    ->reactive()
                    ->options(User::get()->pluck('fullName', 'id'))
                    ->native(false),

                Forms\Components\Select::make('clients')
                    ->relationship('clients')
                    ->reactive()
                    ->options(Client::get()->pluck('name', 'id'))
                    ->visible(function (Get $get) {
                        return $get('internal');
                    })
                    ->multiple()
                    ->label('Vezani klijenti'),

                Forms\Components\Select::make('users')
                    ->required()
                    ->relationship('users')
                    ->options(User::get()->pluck('fullName', 'id'))
                    ->multiple()
                    ->label('Djelatnici'),

                Forms\Components\DatePicker::make('deadline_at')
                    ->label('Rok završetka'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Projekt')
                    ->searchable(),
                Tables\Columns\IconColumn::make('internal')
                    ->label('Interni projekt')
                    ->boolean(),
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Klijent'),
                Tables\Columns\TextColumn::make('deadline_at')
                    ->label('Rok završetka')
                    ->date()
                    ->sortable(),
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
            'index' => ListProjects::route('/'),
        ];
    }
}
