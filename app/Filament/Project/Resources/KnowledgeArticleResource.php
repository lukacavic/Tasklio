<?php

namespace App\Filament\Project\Resources;

use App\Filament\Project\Clusters\KnowledgeBase;
use App\Filament\Project\Resources\KnowledgeArticleResource\Pages;
use App\Models\KnowledgeArticle;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class KnowledgeArticleResource extends Resource
{
    protected static ?string $model = KnowledgeArticle::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $label = 'Članak';

    protected static ?string $pluralLabel = 'Članci';

    protected static ?string $cluster = KnowledgeBase::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->label('Naslov'),

                Select::make('category_id')
                    ->required()
                    ->relationship('category', 'title')
                    ->label('Kategorija'),

                TinyEditor::make('content')
                    ->label('Sadržaj')
                    ->minHeight(600)
                    ->columnSpanFull()
                    ->required(),

            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
               Tables\Columns\TextColumn::make('title')
                ->label('Naziv'),

                Tables\Columns\TextColumn::make('category.title')
                ->label('Kategorija')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKnowledgeArticles::route('/'),
            //'create' => Pages\CreateKnowledgeArticle::route('/create'),
            //'edit' => Pages\EditKnowledgeArticle::route('/{record}/edit'),
        ];
    }
}
