<?php

namespace App\Filament\Project\Clusters\SettingsCluster\Resources\TicketDepartmentResource\Pages;

use App\Filament\Project\Clusters\SettingsCluster\Resources\TicketDepartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTicketDepartments extends ManageRecords
{
    protected static string $resource = TicketDepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
