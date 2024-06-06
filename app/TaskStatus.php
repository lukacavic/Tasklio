<?php

namespace App;

use Filament\Support\Contracts\HasLabel;

enum TaskStatus: int implements HasLabel
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
}
