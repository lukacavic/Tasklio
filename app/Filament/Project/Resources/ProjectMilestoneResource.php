<?php

namespace App\Filament\Project\Resources;

use App\Filament\Project\Resources\ProjectMilestoneResource\Pages;
use App\Filament\Project\Resources\ProjectMilestoneResource\RelationManagers\TasksRelationManager;
use App\Models\ProjectMilestone;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ProjectMilestoneResource extends Resource
{
    protected static ?string $model = ProjectMilestone::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Planovi (Milestones)';

    protected static ?string $label = 'Plan';

    protected static ?string $pluralLabel = 'Planovi';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Naziv' => $record->name,
            'Opis' => strip_tags($record->description)
        ];
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Naziv')
                    ->columnSpanFull()
                    ->required(),

                Forms\Components\DatePicker::make('start_date')
                    ->label('Početak')
                    ->required(),

                Forms\Components\DatePicker::make('due_date')
                    ->label('Rok za završetak')
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->label('Opis')
                    ->columnSpanFull()

            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                return $query->withCount('tasks')
                    ->withCount(['tasks' => function($query) {
                        return $query->notCompleted();
                    }]);
            })
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->description(function (ProjectMilestone $record) {
                        return $record->description;
                    }),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Početak')
                    ->description(function (ProjectMilestone $record) {
                        return $record->start_date->diffForHumans();
                    })
                    ->date(),

                Tables\Columns\TextColumn::make('due_date')
                    ->label('Rok za završetak')
                    ->date()
                    ->description(function (ProjectMilestone $record) {
                        return $record->due_date->diffForHumans();
                    })
                    ->date(),

                Tables\Columns\TextColumn::make('tasks_count')
                    ->formatStateUsing(function($state) {
                        return 'Ukupno: ' . $state;
                    })
                    ->description(function(ProjectMilestone $record) {
                        return 'Neriješeno: ' . $record->tasks()->notCompleted()->count();
                    })
                    ->label('Zadaci'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
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
            TasksRelationManager::make()
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjectMilestones::route('/'),
            //'create' => Pages\CreateProjectMilestone::route('/create'),
            //'edit' => Pages\EditProjectMilestone::route('/{record}/edit'),
            'view' => Pages\ViewMilestone::route('/{record}'),
        ];
    }
}
