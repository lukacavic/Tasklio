<?php

namespace App\Providers;

use BezhanSalleh\PanelSwitch\PanelSwitch;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
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
            $panelSwitch->labels([
                'admin' => 'Admin Panel',
                'app' => 'CRM',
                'project' => 'Projekt'
            ]);

            $panelSwitch->icons([
                'project' => 'heroicon-o-command-line',
                'app' => 'heroicon-o-globe-alt',
            ]);

            $panelSwitch->excludes([
                'admin'
            ]);

            $panelSwitch->visible(false);

            $panelSwitch->modalHeading('Način pregleda');
        });

        CreateAction::configureUsing(function (CreateAction $action) {
            $action->icon('heroicon-o-plus');
            $action->label('Dodaj');
            $action->slideOver();
            $action->color(Color::Green);
        });

        //Table actions
        \Filament\Tables\Actions\EditAction::configureUsing(function (\Filament\Tables\Actions\EditAction $action) {
            $action->icon('heroicon-o-pencil');
            $action->hiddenLabel();
            $action->slideOver();
        });

        \Filament\Tables\Actions\ViewAction::configureUsing(function (\Filament\Tables\Actions\ViewAction $action) {
            $action->icon('heroicon-o-eye');
            $action->hiddenLabel();
        });

        \Filament\Tables\Actions\CreateAction::configureUsing(function (\Filament\Tables\Actions\CreateAction $action) {
            $action->icon('heroicon-o-plus');
            $action->color(Color::Green);
            $action->label('Dodaj');
            $action->slideOver();
        });

        \Filament\Tables\Actions\DeleteAction::configureUsing(function (\Filament\Tables\Actions\DeleteAction $action) {
            $action->icon('heroicon-o-trash');
            $action->hiddenLabel();
        });
    }
}
