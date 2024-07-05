<?php

namespace App\Filament\Project\Resources\ClientsResource\Pages;

use App\Filament\Project\Resources\ClientsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClients extends EditRecord
{
    protected static string $resource = ClientsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
