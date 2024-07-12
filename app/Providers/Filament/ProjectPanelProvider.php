<?php

namespace App\Providers\Filament;

use App\Filament\Project\Pages\Dashboard;
use App\Filament\Project\Pages\TasksKanbanBoard;
use App\Filament\Project\Widgets\LeadsKanbanBoard;
use App\Livewire\AccountSettingsPage;
use App\Livewire\CustomPersonalInfo;
use App\Livewire\ProfileAddressComponent;
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
use Jeffgreco13\FilamentBreezy\BreezyCore;
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
            ->tenant(Project::class)
            ->font('Poppins')
            ->spa()
            ->colors([
                'primary' => '#24517a'
            ])
            ->login()
            ->viteTheme('resources/css/filament/project/theme.css')
            ->authGuard('web')
            ->databaseTransactions()
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->tenant(Project::class)
            ->globalSearchKeyBindings(['command+f', 'ctrl+f'])
            ->globalSearchDebounce('750ms')
            ->globalSearchFieldKeyBindingSuffix()
            ->discoverResources(in: app_path('Filament/Project/Resources'), for: 'App\\Filament\\Project\\Resources')
            ->discoverResources(in: app_path('Filament/Shared/Resources'), for: 'App\\Filament\\Shared\\Resources')
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
                BreezyCore::make()
                    ->myProfileComponents([
                        'personal_info' => CustomPersonalInfo::class,
                    ])
                    ->myProfile(
                        hasAvatars: true,
                        slug: 'edit-profile',
                        navigationGroup: 'Settings'
                    ),
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
