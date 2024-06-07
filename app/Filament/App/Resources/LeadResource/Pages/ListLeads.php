<?php

namespace App\Filament\App\Resources\LeadResource\Pages;

use App\Filament\App\Resources\LeadResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeads extends ListRecords
{
    protected static string $resource = LeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Dodaj')
            ->modalHeading('Novi pot. klijent'),
        ];
    }
}
