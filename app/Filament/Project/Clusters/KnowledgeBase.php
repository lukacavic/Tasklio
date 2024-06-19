<?php

namespace App\Filament\Project\Clusters;

use Filament\Actions\Action;
use Filament\Clusters\Cluster;

class KnowledgeBase extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $title = 'Baza znanja';

}
