<?php

namespace App\Filament\App\Widgets;

use App\Models\Event;
use Filament\Facades\Filament;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Saade\FilamentFullCalendar\Data\EventData;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    public string|null|\Illuminate\Database\Eloquent\Model $model = Event::class;

    public int $projectId;

    public function fetchEvents(array $fetchInfo): array
    {
        return Event::query()
            ->where('start_at', '>=', $fetchInfo['start'])
            ->where('end_at', '<=', $fetchInfo['end'])
            ->get()
            ->map(
                fn (Event $event) => EventData::make()
                    ->id($event->id)
                    ->title($event->title)
                    ->start($event->start_at)
                    ->backgroundColor($event->color)
                    ->end($event->end_at)

            )->toArray();
    }

    protected function headerActions(): array
    {
        return [
            CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    return [
                        ...$data,
                        'project_id' => Filament::getTenant()->id
                    ];
                })
                ->mountUsing(
                    function (Form $form, array $arguments) {
                        $form->fill([
                            'start_at' => $arguments['start'] ?? null,
                            'end_at' => $arguments['end'] ?? null
                        ]);
                    }
                )
        ];
    }

    public function getFormSchema(): array
    {
        return [
            Grid::make(2)->schema([
                TextInput::make('title')
                    ->label('Naziv')
                    ->required(),
                ColorPicker::make('color')
                    ->label('Boja'),
            ]),
            Grid::make()
                ->schema([
                    DateTimePicker::make('start_at')
                        ->label('Početak'),
                    DateTimePicker::make('end_at')
                        ->label('Kraj'),
                ]),
            RichEditor::make('description')
                ->label('Opis')
        ];
    }
}
