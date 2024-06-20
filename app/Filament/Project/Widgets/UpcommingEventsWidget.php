<?php

namespace App\Filament\Project\Widgets;

use App\Models\Event;
use App\Models\Project;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UpcommingEventsWidget extends BaseWidget
{
    public function table(Table $table): Table
    {
        $today = Carbon::today();

        $nextWeek = $today->copy()->addDays(7);

        return $table
            ->paginated(false)
            ->emptyStateHeading('Nema događaja!')
            ->emptyStateIcon('heroicon-o-face-smile')
            ->emptyStateDescription('Super, nema nadolazećih događaja u sljedećih 7 dana.')
            ->heading('Nadolazeći događaji')
            ->query(
                Event::query()
                    ->limit(5)
                    ->latest()
                    ->where('project_id', Filament::getTenant()->id)
                    ->whereBetween('start_at', [$today, $nextWeek])
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Naziv')
                    ->description(function (Event $record) {
                        return strip_tags($record->description);
                    }),

                Tables\Columns\TextColumn::make('users.first_name')
                    ->badge()
                    ->separator(',')
                    ->label('Djelatnici'),

                Tables\Columns\TextColumn::make('start_at')
                    ->label('Vrijeme događaja')
                    ->dateTime()
                    ->description(function (Event $record) {
                        return $record->start_at->diffForHumans();
                    })
            ]);
    }
}
