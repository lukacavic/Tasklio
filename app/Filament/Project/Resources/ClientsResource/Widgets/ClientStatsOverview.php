<?php

namespace App\Filament\Project\Resources\ClientsResource\Widgets;

use App\Models\Client;
use App\Models\Lead;
use App\TaskStatus;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClientStatsOverview extends BaseWidget
{
    public ?Client $record = null;

    function divnum($numerator, $denominator)
    {
        return $denominator == 0 ? 0 : ($numerator / $denominator);
    }

    protected function getStats(): array
    {
        $totalTasks = $this->record->tasks()->count();
        $completedTasks = $this->record->tasks()->completed()->count();
        $notCompletedTasks = $this->record->tasks()->notCompleted()->count();
        $inProgressTasks = $this->record->tasks()->whereIntegerInRaw('status_id', [TaskStatus::InProgress->value, TaskStatus::Testing->value, TaskStatus::AwaitingFeedback->value])->count();
        $percentageNotCompleted = number_format($this->divnum($notCompletedTasks, $totalTasks) * 100, 2);
        $percentageCompleted = number_format($this->divnum($completedTasks, $totalTasks) * 100, 2);

        return [
            Stat::make('Ukupno zadataka', $totalTasks)
                ->description($inProgressTasks . ' u izradi')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),

            Stat::make('Riješeni zadaci', $completedTasks)
                ->description($percentageCompleted . '%')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Neriješeni zadaci', $notCompletedTasks)
                ->description($percentageNotCompleted . '%')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),
        ];
    }
}
