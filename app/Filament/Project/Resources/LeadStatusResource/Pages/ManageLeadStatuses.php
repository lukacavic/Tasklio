<?php

namespace App\Filament\Project\Resources\LeadStatusResource\Pages;

use App\Filament\Project\Resources\LeadStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLeadStatuses extends ManageRecords
{
    protected static string $resource = LeadStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Dodaj')
            ->modalHeading('Dodaj'),
        ];
    }
}
