<?php

namespace App\Filament\Project\Resources\KnowledgeArticleResource\Pages;

use App\Filament\Project\Resources\KnowledgeArticleResource;
use App\Filament\Project\Resources\KnowledgeCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKnowledgeArticles extends ListRecords
{
    protected static string $resource = KnowledgeArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('categories')
                ->hiddenLabel()
                ->tooltip('Kategorije')
                ->icon('heroicon-o-tag')
                ->url(KnowledgeCategoryResource::getUrl('index')),

            Actions\CreateAction::make(),
        ];
    }
}
