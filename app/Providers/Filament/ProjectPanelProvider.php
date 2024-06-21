<?php

namespace App\Providers\Filament;

use App\Filament\Project\Pages\Dashboard;
use App\Filament\Project\Pages\TasksKanbanBoard;
use App\Filament\Project\Widgets\LeadsKanbanBoard;
use App\Models\Project;
use Awcodes\FilamentQuickCreate\QuickCreatePlugin;
use DutchCodingCompany\FilamentDeveloperLogins\FilamentDeveloperLoginsPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;

class ProjectPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('project')
            ->path('project')
            ->tenantMenu()
            ->font('Poppins')
            ->spa()
            ->login()
            ->authGuard('web')
            ->databaseTransactions()
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->tenant(Project::class)
            ->globalSearchKeyBindings(['command+f', 'ctrl+f'])
            ->globalSearchDebounce('750ms')
            ->globalSearchFieldKeyBindingSuffix()
            /*->renderHook(
                PanelsRenderHook::TOPBAR_START,
                fn() => view('filament.hook.topbar'),
            )*/
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Project/Resources'), for: 'App\\Filament\\Project\\Resources')
            ->discoverPages(in: app_path('Filament/Project/Pages'), for: 'App\\Filament\\Project\\Pages')
            ->discoverClusters(in: app_path('Filament/Project/Clusters'), for: 'App\\Filament\\Project\\Clusters')
            ->pages([
                Dashboard::class,
                TasksKanbanBoard::class,
                LeadsKanbanBoard::class
            ])
            ->discoverWidgets(in: app_path('Filament/Project/Widgets'), for: 'App\\Filament\\Project\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentFullCalendarPlugin::make()
                    ->editable(true)
                    ->selectable(),

                FilamentApexChartsPlugin::make(),
                QuickCreatePlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
