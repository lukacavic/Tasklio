<?php

namespace App\Providers\Filament;

use App\Livewire\AccountSettingsPage;
use App\Livewire\CustomPersonalInfo;
use App\Livewire\ProfileAddressComponent;
use App\Models\Organisation;
use Awcodes\FilamentGravatar\GravatarPlugin;
use DutchCodingCompany\FilamentDeveloperLogins\FilamentDeveloperLoginsPlugin;
use Filament\Forms\Components\FileUpload;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use RickDBCN\FilamentEmail\FilamentEmail;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('app')
            ->default()
            ->tenantMenu()
            ->databaseNotifications()
            ->path('app')
            ->spa()
            ->login()
            ->viteTheme('resources/css/filament/app/theme.css')
            ->font('Poppins')
            ->passwordReset()
            ->authGuard('web')
            ->unsavedChangesAlerts()
            ->databaseTransactions()
            ->globalSearchKeyBindings(['command+f', 'ctrl+f'])
            ->globalSearchDebounce('750ms')
            ->globalSearchFieldKeyBindingSuffix()
            ->tenant(Organisation::class)
            ->tenantMenu(false)
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverResources(in: app_path('Filament/Shared/Resources'), for: 'App\\Filament\\Shared\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->pages([
                //Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
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
            ->authMiddleware([
                Authenticate::class,
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
                    ->selectable(true),
                FilamentApexChartsPlugin::make(),
                FilamentEmail::make(),
            ]);
    }
}
