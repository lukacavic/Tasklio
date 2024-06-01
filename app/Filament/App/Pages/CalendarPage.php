<?php

namespace App\Filament\App\Pages;

use App\Filament\App\Widgets\CalendarWidget;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class CalendarPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static string $view = 'filament.app.pages.calendar-page';
    protected static ?string $navigationLabel = 'Kalendar';

    protected static ?string $title = 'Kalendar';

    protected function getHeaderWidgets(): array
    {
        return [CalendarWidget::make(['projectId', Filament::getTenant()->id])];
    }
}
