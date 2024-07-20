<?php

namespace App\Filament\Project\Clusters\SettingsCluster\Resources;

use App\Filament\Project\Clusters\SettingsCluster;
use App\Filament\Project\Clusters\SettingsCluster\Resources\TicketDepartmentResource\Pages;
use App\Models\TicketDepartment;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TicketDepartmentResource extends Resource
{
    protected static ?string $model = TicketDepartment::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $label = 'odjel';

    protected static ?string $pluralLabel = 'odjeli';

    protected static ?string $navigationLabel = 'Odjeli';

    protected static ?string $cluster = SettingsCluster::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Naziv'),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->label('Email'),

                TextInput::make('imap_username')
                    ->label('IMAP Korisničko ime'),

                TextInput::make('imap_host')
                    ->label('IMAP Poslužitelj'),

                TextInput::make('imap_password')
                    ->password()
                    ->label('IMAP Lozinka'),

                Forms\Components\ToggleButtons::make('imap_encryption')
                    ->label('Enkripcija')
                    ->inline()
                    ->default('None')
                    ->options([
                        'TLS' => 'TLS',
                        'SSL' => 'SSL',
                        'None' => 'Bez enkripcije',
                    ]),

                Forms\Components\Placeholder::make('divider'),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->label('Naziv'),

                Tables\Columns\TextColumn::make('email')
                ->label('Email'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTicketDepartments::route('/'),
        ];
    }
}
