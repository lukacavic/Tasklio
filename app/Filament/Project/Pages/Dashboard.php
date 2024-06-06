<?php

namespace App\Filament\Project\Pages;

use App\Filament\App\Widgets\TasksByProjectChart;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected function getHeaderWidgets(): array
    {
        return [];
    }

    public function getWidgets(): array
    {
        return [TasksByProjectChart::class];
    }
}
