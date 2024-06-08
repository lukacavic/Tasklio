<?php

namespace App\Filament\App\Resources\ClientResource\Pages;

use App\Filament\App\Resources\ClientResource;
use App\Models\Client;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Actions;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Colors\Color;
use JaOcero\ActivityTimeline\Components\ActivityDate;
use JaOcero\ActivityTimeline\Components\ActivityDescription;
use JaOcero\ActivityTimeline\Components\ActivityIcon;
use JaOcero\ActivityTimeline\Components\ActivitySection;
use JaOcero\ActivityTimeline\Components\ActivityTitle;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

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
