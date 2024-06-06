<?php

namespace App\Filament\Project\Resources\TaskResource\Pages;

use App\Filament\Project\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Colors\Color;

class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    public function getTabs(): array
    {
        $tabs = [
            'my' => Tab::make('Moji zadaci')->badge($this->getModel()::count()),
            'created' => Tab::make('Kreirani')->badge($this->getModel()::count()),
            'in_progress' => Tab::make('U izradi')->badge($this->getModel()::count()),
            'testing' => Tab::make('Testiranje')->badge($this->getModel()::count()),
            'awaiting_feedback' => Tab::make('Čeka se odgovor')->badge($this->getModel()::count())
                ->badge($this->getModel()::count()),
            'completed' => Tab::make('Završeni')
                ->badge($this->getModel()::count())
        ];

        return $tabs;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Novi zadatak')
            ->icon('heroicon-o-plus-circle'),
        ];
    }
}
