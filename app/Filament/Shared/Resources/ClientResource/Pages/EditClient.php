<?php

namespace App\Filament\Shared\Resources\ClientResource\Pages;

use App\Filament\Shared\Resources\ClientResource;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClient extends EditRecord
{
    use HasPageSidebar;

    protected static string $resource = ClientResource::class;

    protected static ?string $title = 'Izmjena klijenta';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->hiddenLabel()
                ->icon('heroicon-o-trash'),
        ];
    }
}
