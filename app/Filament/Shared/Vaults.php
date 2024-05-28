<?php

namespace App\Filament\Shared;

use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Vaults
{
    public static function getForm(Form $form)
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Naziv')
                    ->required()
                    ->maxLength(255),
                RichEditor::make('content')
                    ->label('Sadržaj')
                    ->required()
                    ->maxLength(255),
                Radio::make('visibility')
                    ->label('Vidljivost')
                    ->options([
                        1 => 'Djelatnik koji je kreirao',
                        2 => 'Svi djelatnici projekta'
                    ])
            ])->columns(1);
    }

    public static function getTable(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Naslov'),
                TextColumn::make('content')
                    ->label('Sadržaj')
            ])
            ->filters([
                TrashedFilter::make()
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Dodaj')
                    ->icon('heroicon-o-plus'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
