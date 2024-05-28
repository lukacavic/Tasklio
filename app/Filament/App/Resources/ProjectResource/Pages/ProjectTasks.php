<?php

namespace App\Filament\App\Resources\ProjectResource\Pages;

use App\Filament\App\Resources\ProjectResource;
use App\Filament\Shared\Tasks;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectTasks extends ManageRelatedRecords
{
    use HasPageSidebar;

    protected static string $resource = ProjectResource::class;

    protected static string $relationship = 'tasks';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $title = 'Zadaci';

    public static function getNavigationLabel(): string
    {
        return 'Tasks';
    }

    public function form(Form $form): Form
    {
        return Tasks::getForm($form);
    }

    public function table(Table $table): Table
    {
        return Tasks::getTable($table);
    }
}
