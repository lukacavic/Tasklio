<?php

namespace App\Filament\Project\Resources\MeetingsResource\Pages;

use App\Filament\Project\Resources\MeetingsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMeetings extends ListRecords
{
    protected static string $resource = MeetingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
