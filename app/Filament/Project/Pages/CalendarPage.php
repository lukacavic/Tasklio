<?php

namespace App\Filament\Project\Pages;

use App\Filament\Project\Widgets\CalendarWidget;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class CalendarPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static string $view = 'filament.app.pages.calendar-page';

    protected static ?string $slug = 'calendar';

    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $navigationLabel = 'Kalendar';

    protected static ?string $title = 'Kalendar';

    protected static ?string $navigationGroup = 'CRM';

    protected function getHeaderWidgets(): array
    {
        return [
            CalendarWidget::make()
        ];
    }
}
