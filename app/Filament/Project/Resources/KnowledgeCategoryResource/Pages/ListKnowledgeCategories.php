<?php

namespace App\Filament\Project\Resources\KnowledgeCategoryResource\Pages;

use App\Filament\Project\Resources\KnowledgeArticleResource;
use App\Filament\Project\Resources\KnowledgeCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKnowledgeCategories extends ListRecords
{
    protected static string $resource = KnowledgeCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('articles')
            ->hiddenLabel()
                ->tooltip('Članci')
            ->icon('heroicon-o-adjustments-horizontal')
            ->url(KnowledgeArticleResource::getUrl('index')),

            Actions\CreateAction::make(),
        ];
    }
}
