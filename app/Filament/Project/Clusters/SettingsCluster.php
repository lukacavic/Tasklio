<?php

namespace App\Filament\Project\Clusters;

use Filament\Clusters\Cluster;

class SettingsCluster extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $navigationLabel = 'Postavke';

    protected static ?int $navigationSort = 99;
}
