<?php

namespace App\Filament\Shared\Resources\TicketResource\Pages;

use App\Filament\Shared\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;
}
