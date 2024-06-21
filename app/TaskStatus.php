<?php

namespace App;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum TaskStatus: int implements HasLabel, HasColor, HasIcon
{
    case Created = 1;
    case InProgress = 2;
    case Testing = 3;
    case AwaitingFeedback = 4;
    case Completed = 5;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Created => 'Kreiran',
            self::InProgress => 'U izradi',
            self::Testing => 'Testiranje',
            self::AwaitingFeedback => 'Čeka se komentar',
            self::Completed => 'Završen',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Created => Color::Yellow,
            self::InProgress => Color::Blue,
            self::Testing => Color::Orange,
            self::AwaitingFeedback => Color::Gray,
            self::Completed => Color::Green,
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Created => 'heroicon-o-user-plus',
            self::InProgress => 'heroicon-o-code-bracket',
            self::Testing => 'heroicon-o-computer-desktop',
            self::AwaitingFeedback => 'heroicon-o-pencil-square',
            self::Completed => 'heroicon-o-check',
        };
    }
}
