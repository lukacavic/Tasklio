<?php

namespace App\Filament\Project\Resources\MeetingsResource\Pages;

use App\Filament\Project\Resources\MeetingsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMeetings extends EditRecord
{
    protected static string $resource = MeetingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
