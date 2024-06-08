<?php

namespace App\Filament\Project\Resources\KnowledgeCategoryResource\Pages;

use App\Filament\Project\Resources\KnowledgeArticleResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Contracts\Support\Htmlable;

class ViewKnowledgeArticle extends ViewRecord
{
    protected static string $resource = KnowledgeArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('send-email')
                ->icon('heroicon-o-at-symbol')
                ->color(Color::Blue)
                ->hiddenLabel(),

            EditAction::make()
                ->icon('heroicon-o-pencil')
                ->hiddenLabel(),

            DeleteAction::make()
                ->icon('heroicon-o-trash')
                ->hiddenLabel(),
        ];
    }

    public function getRecordTitle(): string|Htmlable
    {
        return $this->record->title;
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('title')
                ->label('Naziv'),

            TextEntry::make('category.title')
                ->label('Kategorija'),


            TextEntry::make('user.fullName')
                ->label('Kreirao'),

            TextEntry::make('content')
                ->label('Sadržaj')
                ->html(true)
                ->columnSpanFull()

        ])->columns(3);
    }
}
