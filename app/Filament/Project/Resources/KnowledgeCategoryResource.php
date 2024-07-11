<?php

namespace App\Filament\Project\Resources;

use App\Filament\Project\Clusters\KnowledgeBase;
use App\Models\KnowledgeCategory;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KnowledgeCategoryResource extends Resource
{
    protected static ?string $model = KnowledgeCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $label = 'Kategorija';

    protected static ?string $pluralLabel = 'Kategorije';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Naziv')
                    ->required()
                    ->columnSpanFull(),

                ColorPicker::make('color')
                    ->label('Boja')
                    ->columnSpanFull(),

                Textarea::make('short_description')
                    ->label('Kratki opis')
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ColorColumn::make('color')
                    ->label(''),

                Tables\Columns\TextColumn::make('title')
                    ->label('Naziv')
                    ->description(function (KnowledgeCategory $record) {
                        return $record->short_description;
                    })
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(function ($record) {
                        return $record->articles()->exists();
                    })
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
            'index' => \App\Filament\Project\Resources\KnowledgeCategoryResource\Pages\ListKnowledgeCategories::route('/'),
            //'create' => \App\Filament\Project\Resources\KnowledgeCategoryResource\Pages\CreateKnowledgeCategory::route('/create'),
            //'edit' => \App\Filament\Project\Resources\KnowledgeCategoryResource\Pages\EditKnowledgeCategory::route('/{record}/edit'),
        ];
    }
}
