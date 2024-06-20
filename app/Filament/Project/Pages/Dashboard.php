<?php

namespace App\Filament\Project\Pages;

use App\Filament\App\Widgets\TasksByProjectChart;
use App\Filament\Project\Widgets\LeadsByStatusChart;
use App\Filament\Project\Widgets\UpcommingEventsWidget;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $slug = 'dashboard';

    public function getWidgets(): array
    {
        return [
            LeadsByStatusChart::class, UpcommingEventsWidget::class,
        ];
    }
}
