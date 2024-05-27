<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Actions;
use Filament\Panel\Concerns\HasSidebar;
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
                ->icon('heroicon-o-trash'),
        ];
    }
}
