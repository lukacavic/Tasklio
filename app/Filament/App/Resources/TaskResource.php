<?php

namespace App\Filament\App\Resources;

use App\Filament\Shared\Tasks;
use App\Models\Task;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Table;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    protected static ?string $label = 'Zadatak';

    protected static ?string $pluralModelLabel = 'Zadaci';

    protected static ?string $navigationLabel = 'Zadaci';

    public static function form(Form $form): Form
    {
        return Tasks::getForm($form);
    }

    public static function table(Table $table): Table
    {
        return Tasks::getTable($table);
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
            'index' => TaskResource\Pages\ListTasks::route('/'),
            'view' => TaskResource\Pages\ViewTask::route('/{record}'),
            //'create' => Pages\CreateTask::route('/create'),
            //'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }

}
