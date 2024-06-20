<?php

namespace App;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TaskPriority: int implements HasLabel, HasColor
{
    case Low = 1;
    case Normal = 2;
    case High = 3;
    case Urgent = 4;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Low => 'Niski',
            self::Normal => 'Normalni',
            self::High => 'Visoki',
            self::Urgent => 'Hitno',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Low => Color::Green,
            self::Normal => Color::Gray,
            self::High => Color::Orange,
            self::Urgent => Color::Red,
        };
    }
}
