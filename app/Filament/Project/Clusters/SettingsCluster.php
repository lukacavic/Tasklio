<?php

namespace App\Filament\Project\Clusters;

use Filament\Clusters\Cluster;
use Filament\Facades\Filament;

class SettingsCluster extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $navigationLabel = 'Postavke';

    protected static ?int $navigationSort = 9999;

    public static function canAccess(): bool
    {
        $projectLeader = Filament::getTenant()->projectLeader;

        if($projectLeader == null) return true;

        return auth()->id() === $projectLeader->id;
    }
}
