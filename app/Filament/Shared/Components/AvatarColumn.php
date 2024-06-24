<?php

namespace App\Filament\Shared\Components;

use App\Models\User;
use Filament\Tables\Columns\ImageColumn;

class AvatarColumn extends ImageColumn
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->circular();
        $this->defaultImageUrl(function (User $record) {
            return 'https://ui-avatars.com/api/?name=' . $record->first_name . '+' . $record->lastName;
        });
    }
}
