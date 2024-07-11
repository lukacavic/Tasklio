<?php

namespace App\Livewire;

use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Validation\Rule;
use Jeffgreco13\FilamentBreezy\Livewire\PersonalInfo;

class CustomPersonalInfo extends PersonalInfo
{
    public static $sort = 1;

    public array $only = ['first_name', 'last_name', 'email_signature', 'email', 'avatar_url', 'name'];

    public bool $hasAvatars = true;

    protected function sendNotification(): void
    {
        Notification::make()
            ->success()
            ->title('Spremljeno.')
            ->send();
    }

    protected function getProfileFormSchema(): array
    {
        $groupFields = Group::make([
            Forms\Components\Grid::make(2)
            ->schema([
                TextInput::make('first_name')
                    ->label('Ime')
                    ->required(),

                TextInput::make('last_name')
                    ->required()
                    ->label('Prezime'),
            ]),

            Forms\Components\Grid::make(2)->schema([
                $this->getEmailComponent(),

                $this->getNameComponent(),
            ]),


            Forms\Components\RichEditor::make('email_signature')
                ->label('Email potpis'),
        ])->columnSpanFull();

        return ($this->hasAvatars)
            ? [filament('filament-breezy')->getAvatarUploadComponent(), $groupFields]
            : [$groupFields];
    }

    protected function getNameComponent(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('name')
            ->required()
            ->rules(Rule::unique('users', 'name')->ignore(auth()->user()->id, 'id')
                ->where('organisation_id', filament()->getTenant()->id)
            )
            ->label('Korisničko ime');
    }
}
