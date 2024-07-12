<?php

namespace App\Filament\Shared\Resources\ClientResource\Pages;

use App\Filament\Shared\Resources\ClientResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClients extends ListRecords
{
    protected static string $resource = ClientResource::class;

    protected static ?string $title = 'Klijenti';

    protected function getHeaderActions(): array
    {


        return [
            Actions\CreateAction::make()
                ->label('Novi klijent')
                ->modalHeading('Novi klijent')
                ->icon('heroicon-o-plus'),
        ];
    }
}
