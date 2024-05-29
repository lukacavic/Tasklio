<?php

namespace App\Filament\Shared;

use App\Models\Task;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class Tasks
{
    public static function getForm(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Naslov')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                DateTimePicker::make('start_at')
                    ->label('Početak rada')
                    ->default(now())
                    ->required(),
                DateTimePicker::make('deadline_at')
                    ->label('Rok završetka'),
                Select::make('members')
                    ->label('Djelatnici')
                    ->relationship('members')
                    ->columnSpanFull()
                    ->options(User::get()->pluck('fullName', 'id'))
                    ->multiple(),
                Select::make('priority_id')
                    ->options([
                        1 => 'Niski',
                        2 => 'Srednji',
                        3 => 'Visoki'
                    ])
                    ->required()
                    ->default(1)
                    ->label('Prioritet'),
                TagsInput::make('tags')
                    ->label('Oznake/Tags'),
                RichEditor::make('description')
                    ->label('Opis')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                FileUpload::make('attachments')
                    ->label('Privitci')
                    ->columnSpanFull()
            ])->columns(2);
    }

    public static function getTable(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->emptyStateHeading('Trenutno nema upisanih zadataka')
            ->columns([
                TextColumn::make('title')
                    ->description(function (Task $record) {
                        return strip_tags($record->description);
                    })
                    ->icon(function (Task $record) {
                        if ($record->priority_id == 3) {
                            return 'heroicon-m-exclamation-triangle';
                        }

                        return null;
                    })
                    ->iconColor(Color::Red)
                    ->iconPosition(IconPosition::After)
                    ->label('Naziv')
                    ->searchable(),
                ImageColumn::make('creator.avatar')
                    ->circular()
                    ->stacked()
                    ->label('Dodao')
                    ->sortable(),
                ImageColumn::make('members.avatar')
                    ->circular()
                    ->stacked()
                    ->label('Djelatnici')
                    ->sortable(),
                SelectColumn::make('status_id')
                    ->label('Status')
                    ->options([
                        1 => 'Kreiran',
                        2 => 'U izradi',
                        3 => 'Završen'
                    ])
                    ->sortable(),
                TextColumn::make('start_at')
                    ->label('Početak rada')
                    ->dateTime()
                    ->sortable(),

                ViewColumn::make('custom')
                    ->view('custom-stack'),
                TextColumn::make('deadline_at')
                    ->label('Rok završetka')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make()
            ])

            ->actions([
                ViewAction::make()->hiddenLabel(),
                EditAction::make()->hiddenLabel(),
                DeleteAction::make()->hiddenLabel(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
}
