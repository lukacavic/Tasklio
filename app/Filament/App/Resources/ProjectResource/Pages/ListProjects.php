<?php

namespace App\Filament\App\Resources\ProjectResource\Pages;

use App\Filament\App\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProjects extends ListRecords
{
    protected static string $resource = ProjectResource::class;
    protected static ?string $title = 'Projekti';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Novi projekt')
                ->modalHeading('Novi projekt')
                ->icon('heroicon-o-plus'),
        ];
    }
}
