<?php

namespace App\Filament\Project\Resources\ProjectMilestoneResource\RelationManagers;

use App\Filament\Project\Resources\TaskResource;
use App\TaskPriority;
use App\TaskStatus;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    protected static ?string $label = 'Zadaci';

    protected static ?string $pluralLabel = 'Zadatak';

    protected static ?string $title = 'Zadaci';

    protected $listeners = ['refreshRelation' => '$refresh'];

    protected function getTableHeaderActions(): array
    {
        return [
            CreateAction::make()
        ];
    }

    public function isReadOnly(): bool
    {
        return false;
    }


    public function form(Form $form): Form
    {
        return TaskResource::form($form);
    }

    public function table(Table $table): Table
    {
        return TaskResource::table($table)
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(function ($record) {
                        return TaskResource\Pages\ViewTask::getUrl(['record' => $record]);
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_id')
                ->label('Status')
                ->options(TaskStatus::class)
                ->searchable(),

                Tables\Filters\SelectFilter::make('members')
                    ->label('Djelatnici')
                    ->relationship('members', 'first_name')
                    ->multiple()
                    ->options(Filament::getTenant()->users()->get()->pluck('fullName', 'id'))
                    ->searchable()
            ])
            ->headerActions([
                \Filament\Tables\Actions\CreateAction::make()
                    ->label('Dodaj')
                    ->fillForm(function ($data) {
                        $data['project_milestone_id'] = $this->getOwnerRecord()->id;
                        $data['project_id'] = Filament::getTenant()->id;
                        $data['priority_id'] = TaskPriority::Normal->value;

                        return $data;
                    })
            ]);;
    }
}
