<?php

namespace App\Filament\Project\Resources\KnowledgeCategoryResource\Pages;

use App\Filament\Project\Resources\KnowledgeArticleResource;
use App\Models\KnowledgeArticle;
use App\Models\Lead;
use App\Models\Task;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Infolists\Components\Card;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Validation\Rule;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;
use Spatie\MediaLibrary\Support\MediaStream;

class ViewKnowledgeArticle extends ViewRecord
{
    protected static string $resource = KnowledgeArticleResource::class;

    public function getSendEmailAction(): Action
    {
        return Action::make('send-email')
            ->modalHeading('Pošalji email')
            ->modalWidth(MaxWidth::ExtraLarge)
            ->fillForm(fn(KnowledgeArticle $record): array => [
                'content' => $record->content,
            ])
            ->icon('heroicon-o-at-symbol')
            ->form([
                TagsInput::make('receivers')
                    ->placeholder('Unesite email adresu primatelja')
                    ->suggestions(Lead::get()->pluck('email')->toArray())
                    ->prefixIcon('heroicon-o-at-symbol')
                    ->nestedRecursiveRules([
                        'email',
                    ])
                    ->required()
                    ->label('Primatelji'),

                TinyEditor::make('content')
                    ->label('Sadržaj')
                    ->columnSpanFull()
            ])
            ->requiresConfirmation()
            ->color(Color::Blue)
            ->hiddenLabel();
    }

    protected function getHeaderActions(): array
    {
        return [
            //$this->getSendEmailAction(),

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
            \Filament\Infolists\Components\Section::make()->schema([
                TextEntry::make('title')
                    ->label('Naziv'),

                TextEntry::make('category.title')
                    ->badge()
                    ->label('Kategorija'),

                TextEntry::make('userCreated.fullName')
                    ->label('Kreirao'),

                TextEntry::make('created_at')
                    ->label('Vrijeme kreiranja')
                    ->date(),

                TextEntry::make('updated_at')
                    ->label('Zadnja izmjena')
                    ->date(),
            ])->columns(5),

            \Filament\Infolists\Components\Section::make()
                ->schema([
                    TextEntry::make('content')
                        ->label('Sadržaj')
                        ->html(true)
                        ->columnSpanFull(),
                ]),

            RepeatableEntry::make('media')
                ->visible(function (KnowledgeArticle $record) {
                    return $record->media()->count() > 0;
                })
                ->label('Privitci')
                ->hintActions([
                    \Filament\Infolists\Components\Actions\Action::make('download')
                        ->action(function (KnowledgeArticle $record) {
                            $downloads = $record->getMedia('knowledge-article');

                            return MediaStream::create('attachments.zip')->addMedia($downloads);
                        })
                        ->label('Preuzmi sve')
                        ->icon('heroicon-m-arrow-down-tray'),

                ])
                ->columnSpanFull()
                ->grid(3)
                ->schema(function (KnowledgeArticle $knowledgeArticle) {
                    return [
                        TextEntry::make('name')
                            ->columnSpan(2)
                            ->label('Datoteka')
                            ->hintAction(
                                \Filament\Infolists\Components\Actions\Action::make('download')
                                    ->label('Preuzmi')
                                    ->action(function ($record) use ($knowledgeArticle) {
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
