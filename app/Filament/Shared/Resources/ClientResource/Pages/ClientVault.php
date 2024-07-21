<?php

namespace App\Filament\Shared\Resources\ClientResource\Pages;

use App\Filament\Shared\Resources\ClientResource;
use App\Filament\Shared\Vaults;
use App\Models\Client;
use App\Models\Project;
use App\Models\Vault;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables\Table;
use JulioMotol\FilamentPasswordConfirmation\RequiresPasswordConfirmation;

class ClientVault extends ManageRelatedRecords
{
    use HasPageSidebar, RequiresPasswordConfirmation;

    protected static string $resource = ClientResource::class;

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
        return Vaults::getTable($table)
            ->query($this->getQuery());
    }

    private function getQuery()
    {
        $user = auth()->user();

        $query = Vault::query()
            ->where('related_type', Client::class)
            ->where('related_id', $this->getRecord()->id);

        if ($user->administrator) {
            $query->where(function ($q) use ($user) {
                $q->where('visibility', 3);
                $q->orWhere('user_id', auth()->user()->id);
            });
        } else {
            $query->where(function ($q) use ($user) {
                $q->where('visibility', 4);
                $q->orWhere('user_id', auth()->user()->id);
            });
        }

        return $query;
    }
}
