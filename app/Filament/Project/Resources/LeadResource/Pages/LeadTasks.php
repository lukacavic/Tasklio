<?php

namespace App\Filament\Project\Resources\LeadResource\Pages;

use App\Filament\Project\Resources\LeadResource;
use App\Filament\Project\Resources\TaskResource;
use App\Models\Lead;
use App\TaskPriority;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;

class LeadTasks extends ManageRelatedRecords
{
    use HasPageSidebar;

    protected static string $resource = LeadResource::class;

    protected static string $relationship = 'tasks';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $title = 'Zadaci';

    public function form(Form $form): Form
    {
        return TaskResource::form($form);
    }

    public function table(Table $table): Table
    {
        return TaskResource::table($table)
            ->headerActions([
                CreateAction::make()
                    ->label('Dodaj')
                    ->fillForm(function ($data) {
                        $data['related_type'] = Lead::class;
                        $data['related_id'] = $this->getRecord()->id;
                        $data['priority_id'] = TaskPriority::Normal->value;
                        $data['project_id'] = Filament::getTenant()->id;

                        return $data;
                    })
            ]);
    }

    protected function getHeaderActions(): array
    {
        return LeadResource\Helpers\Actions\HeaderActions::getHeaderActions();
    }
}
