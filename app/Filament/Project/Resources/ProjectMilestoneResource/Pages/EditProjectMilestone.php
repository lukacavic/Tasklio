<?php

namespace App\Filament\Project\Resources\ProjectMilestoneResource\Pages;

use App\Filament\Project\Resources\ProjectMilestoneResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProjectMilestone extends EditRecord
{
    protected static string $resource = ProjectMilestoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
