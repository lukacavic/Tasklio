<?php

namespace App\Filament\App\Resources\ProjectResource\Pages;

use App\Filament\App\Resources\ProjectResource;
use App\Filament\Shared\Vaults;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectVault extends ManageRelatedRecords
{
    use HasPageSidebar;

    protected static string $resource = ProjectResource::class;

    protected static string $relationship = 'vaults';

    protected static ?string $navigationIcon = 'heroicon-o-lock-open';

    protected static ?string $title = 'Trezor';

    public static function getNavigationLabel(): string
    {
        return 'Vaults';
    }

    public function form(Form $form): Form
    {
        return Vaults::getForm($form);
    }

    public function table(Table $table): Table
    {
        return Vaults::getTable($table);
    }
}
