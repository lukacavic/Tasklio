<?php

namespace App\Filament\Shared\Components;

use Filament\Tables\Columns\ImageColumn;

class AvatarColumn extends ImageColumn
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->circular();
    }
}
