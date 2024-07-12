<?php

namespace App\Filament\Shared\Resources\ClientResource\Pages;

use App\Filament\Shared\Resources\ClientResource;
use App\Filament\Shared\Tasks;
use App\Models\Client;
use App\TaskPriority;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;

class ClientTasks extends ManageRelatedRecords
{
    use HasPageSidebar;

    protected static string $resource = ClientResource::class;

    protected static string $relationship = 'tasks';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $title = 'Zadaci';

    public function form(Form $form): Form
    {
        return Tasks::getForm($form);
    }

    public function table(Table $table): Table
    {
        return Tasks::getTable($table)
            ->headerActions([
                CreateAction::make()
                    ->label('Dodaj')
                    ->fillForm(function ($data) {
                        $data['related_type'] = Client::class;
                        $data['related_id'] = $this->getRecord()->id;
                        $data['priority_id'] = TaskPriority::Normal->value;
                        $data['project_id'] = Filament::getTenant()->id;

                        return $data;
                    })
            ]);
    }
}
