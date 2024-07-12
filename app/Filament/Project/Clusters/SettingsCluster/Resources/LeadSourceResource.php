<?php

namespace App\Filament\Project\Clusters\SettingsCluster\Resources;

use App\Filament\Project\Clusters\SettingsCluster;
use App\Filament\Project\Clusters\SettingsCluster\Resources\LeadSourceResource\Pages;
use App\Filament\Project\Clusters\SettingsCluster\Resources\LeadSourceResource\RelationManagers;
use App\Models\LeadSource;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeadSourceResource extends Resource
{
    protected static ?string $model = LeadSource::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Lead izvori';

    protected static ?string $cluster = SettingsCluster::class;

    protected static ?string $label = 'Lead izvor';

    protected static ?string $pluralLabel = 'Lead izvori';

    public static function canAccess(): bool
    {
        return Filament::getTenant()->settings()->get('leads-managements-enabled');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Naziv')
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Naziv')
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
            'index' => Pages\ManageLeadSources::route('/'),
        ];
    }
}
