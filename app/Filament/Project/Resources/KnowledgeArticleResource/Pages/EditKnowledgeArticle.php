<?php

namespace App\Filament\Project\Resources\KnowledgeArticleResource\Pages;

use App\Filament\Project\Resources\KnowledgeArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKnowledgeArticle extends EditRecord
{
    protected static string $resource = KnowledgeArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
