<?php

namespace App\Filament\Project\Widgets;

use App\Models\Event;
use App\Models\Project;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Tables;
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
            ->emptyStateDescription('Nema nadolazećih doagađaja.')
            ->heading('Nadolazeći događaji')
            ->query(
                Event::query()
                    ->limit(5)
                    ->latest()
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
                    ->description(function (Event $record) {
                        return $record->start_at->diffForHumans();
                    })
            ]);
    }
}
