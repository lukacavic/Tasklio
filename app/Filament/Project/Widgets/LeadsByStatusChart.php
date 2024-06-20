<?php

namespace App\Filament\Project\Widgets;

use App\Models\LeadStatus;
use Filament\Facades\Filament;
use Filament\Widgets\Widget;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class LeadsByStatusChart extends ApexChartWidget
{
    protected static ?string $heading = 'Pot. klijenti po statusu';

    protected function getOptions(): array
    {
        $leadCounts = LeadStatus::withCount('leads')
            ->where('project_id', Filament::getTenant()->id)
            ->get();

        // Prepare data for the chart
        $labels = $leadCounts->pluck('name')->toArray();
        $counts = $leadCounts->pluck('leads_count')->toArray();

        return [
            'chart' => [
                'height' => '350px',
                'type' => 'pie',
            ],
            'series' => $counts,
            'labels' => $labels,
        ];
    }
}
