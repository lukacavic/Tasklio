<?php

namespace App\Filament\Project\Resources\TaskResource\Pages;

use App\Filament\Project\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
          Actions\Action::make('test')
          ->label('Primjer')
        ];
    }
}
