<?php

namespace App\Filament\App\Resources\TaskResource\Pages;

use App\Filament\App\Resources\TaskResource;
use Filament\Resources\Pages\ViewRecord;

class ViewTask extends ViewRecord
{
    protected static string $resource = TaskResource::class;

    public function getView(): string
    {
        return "filament.app.resources.task-resource.view-task";
    }
}
