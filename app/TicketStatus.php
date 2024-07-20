<?php

namespace App;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum TicketStatus : int implements HasLabel, HasColor, HasIcon
{
    case Open = 1;
    case InProgress = 2;
    case Answered = 3;
    case OnHold = 4;
    case Closed = 5;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Open => 'Otvoreno',
            self::InProgress => 'U izradi',
            self::Answered => 'Odgovoreno',
            self::OnHold => 'Na čekanju',
            self::Closed => 'Zatvoreno',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Open => Color::Yellow,
            self::InProgress => Color::Blue,
            self::Answered => Color::Orange,
            self::OnHold => Color::Gray,
            self::Closed => Color::Green,
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Open => 'heroicon-o-user-plus',
            self::InProgress => 'heroicon-o-code-bracket',
            self::Answered => 'heroicon-o-computer-desktop',
            self::OnHold => 'heroicon-o-pencil-square',
            self::Closed => 'heroicon-o-check',
        };
    }
}
