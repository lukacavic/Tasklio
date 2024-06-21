<?php

namespace App\Filament\Project\Resources\KnowledgeCategoryResource\Pages;

use App\Filament\Project\Resources\KnowledgeArticleResource;
use App\Models\KnowledgeArticle;
use App\Models\Task;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Contracts\Support\Htmlable;
use Spatie\MediaLibrary\Support\MediaStream;

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
                ->columnSpanFull(),

            RepeatableEntry::make('media')
                ->label('Privitci')
                ->hintActions([
                    \Filament\Infolists\Components\Actions\Action::make('download')
                        ->action(function (KnowledgeArticle $record) {
                            $downloads = $record->getMedia('task');

                            return MediaStream::create('attachments.zip')->addMedia($downloads);
                        })
                        ->label('Preuzmi sve')
                        ->icon('heroicon-m-arrow-down-tray'),

                ])
                ->columnSpanFull()
                ->grid(3)
                ->schema(function (Task $task) {
                    return [
                        TextEntry::make('name')
                            ->columnSpan(2)
                            ->label('Datoteka')
                            ->hintAction(
                                \Filament\Infolists\Components\Actions\Action::make('download')
                                    ->label('Preuzmi')
                                    ->action(function ($record) use ($task) {
                                        $task->addLog('Napravio download datoteke: ' . $record->name);

                                        return response()->download($record->getPath(), $record->file_name);
                                    })
                                    ->hiddenLabel()
                                    ->icon('heroicon-m-arrow-down-tray')
                            )
                    ];
                })

        ])->columns(3);
    }
}
