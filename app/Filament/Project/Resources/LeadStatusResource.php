<?php

namespace App\Filament\Project\Resources;

use App\Filament\Project\Clusters\SettingsCluster;
use App\Filament\Project\Resources\LeadStatusResource\Pages\ManageLeadStatuses;
use App\Models\LeadStatus;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LeadStatusResource extends Resource
{
    protected static ?string $model = LeadStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $label = 'Lead status';

    protected static ?string $pluralLabel = 'Lead statusi';

    protected static ?string $cluster = SettingsCluster::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Naziv')
                            ->required(),
                        ColorPicker::make('color')
                            ->label('Boja')
                            ->required(),
                        TextInput::make('order')
                            ->label('Poredak')
                            ->numeric()
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Naziv'),
                ColorColumn::make('color')->label('Boja'),
                TextColumn::make('order')->label('Poredak')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageLeadStatuses::route('/'),
        ];
    }
}

