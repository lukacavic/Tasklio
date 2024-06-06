<?php

namespace App\Providers;

use BezhanSalleh\PanelSwitch\PanelSwitch;
use Filament\Actions\CreateAction;
use Filament\Support\Colors\Color;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch) {
            $panelSwitch->slideOver();
        });

        CreateAction::configureUsing(function (CreateAction $action) {
            $action->icon('heroicon-o-plus');
            $action->label('Dodaj');
            $action->slideOver();
            $action->color(Color::Green);
        });
    }
}
