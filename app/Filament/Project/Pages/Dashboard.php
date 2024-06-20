<?php

namespace App\Filament\Project\Pages;

use App\Filament\App\Widgets\TasksByProjectChart;
use App\Filament\Project\Widgets\UpcommingEventsWidget;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $slug = 'dashboard';

    protected function getHeaderWidgets(): array
    {
        return [];
    }

    public function getWidgets(): array
    {
        return [TasksByProjectChart::class, UpcommingEventsWidget::class];
    }
}
