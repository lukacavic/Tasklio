<?php

namespace App\Filament\Project\Clusters\SettingsCluster;

use App\Filament\Project\Clusters\SettingsCluster;
use App\ProjectSettingsItems;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Quadrubo\FilamentModelSettings\Pages\Contracts\HasModelSettings;
use Quadrubo\FilamentModelSettings\Pages\ModelSettingsPage;

class ProjectSettings extends ModelSettingsPage implements HasModelSettings
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Postavke';

    protected static ?int $navigationSort = 99;

    protected static ?string $cluster = SettingsCluster::class;

    public static function getSettingRecord(): ?\Illuminate\Database\Eloquent\Model
    {
        return Filament::getTenant();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tabs\Tab::make('Potencijalni klijenti')
                            ->schema([
                                Forms\Components\Toggle::make(ProjectSettingsItems::LEADS_MANAGEMENT_ENABLED->value)
                                    ->label('Upravljanje potencijalnim klijenta')
                            ]),
                    ])
            ]);
    }
}
