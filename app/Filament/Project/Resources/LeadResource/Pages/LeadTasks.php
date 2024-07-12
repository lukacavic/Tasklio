<?php

namespace App\Filament\Project\Resources\LeadResource\Pages;

use App\Filament\Project\Resources\LeadResource;
use App\Filament\Project\Resources\TaskResource;
use App\Models\Lead;
use App\Models\Task;
use App\Models\User;
use App\TaskStatus;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class LeadTasks extends ManageRelatedRecords
{
    use HasPageSidebar;

    protected static string $resource = LeadResource::class;

    protected static string $relationship = 'tasks';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $title = 'Zadaci';

    protected function getHeaderActions(): array
    {
        return LeadResource\Helpers\Actions\HeaderActions::getHeaderActions();
    }

    public function form(Form $form): Form
    {
        return TaskResource::form($form);
    }

    public function table(Table $table): Table
    {
        return TaskResource::table($table);
    }
}
