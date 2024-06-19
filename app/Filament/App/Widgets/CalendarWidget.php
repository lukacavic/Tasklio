<?php

namespace App\Filament\App\Widgets;

use App\Models\Event;
use App\Models\Project;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Saade\FilamentFullCalendar\Data\EventData;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    public string|null|\Illuminate\Database\Eloquent\Model $model = Event::class;

    public int $projectId;

    public function eventDidMount(): string
    {
        return <<<JS
        function({ event, timeText, isStart, isEnd, isMirror, isPast, isFuture, isToday, el, view }){
            el.setAttribute("x-tooltip", "tooltip");
            el.setAttribute("x-data", "{ tooltip: '"+event.title+"' }");
        }
    JS;
    }

    public function config(): array
    {
        return [
            'resourceAreaColumns' => [
                [
                    'field' => 'fname',
                    'headerContent' => 'First Name'
                ],
                [
                    'field' => 'lname',
                    'headerContent' => 'Last Name'
                ]
            ],
            'locale' => 'hr',
            'dragScroll' => true,
            'firstDay' => 1,
            'editable' => true,
            'dayMinWidth' => '500',
            'headerToolbar' => [
                'left' => 'dayGridWeek,dayGridDay',
                'center' => 'title',
                'right' => 'prev,next today',
            ],
            'initialView' => 'resourceTimeGridDay',
            'resources' => [
                [
                    'id' => 1, 'title' => 'Room A', 'fname' => 'John',
                    'lname' => 'Smith'
                ],
                ['id' => 2, 'title' => 'Room C'],
                ['id' => 3, 'title' => 'Room B'],
            ],
        ];
    }

    public function fetchEvents(array $fetchInfo): array
    {
        return Event::query()
            ->where('start_at', '>=', $fetchInfo['start'])
            ->where('end_at', '<=', $fetchInfo['end'])
            ->get()
            ->map(
                fn(Event $event) => EventData::make()
                    ->id($event->id)
                    ->resourceId(rand(1, 3))
                    ->borderColor("")
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
                    $projectId = Filament::getTenant() instanceof Project ? Filament::getTenant()->id : null;

                    return [
                        ...$data,
                        'project_id' => $projectId
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
                    ->required()
                    ->label('Boja'),
            ]),
            Grid::make()
                ->schema([
                    DateTimePicker::make('start_at')
                        ->required()
                        ->label('Početak'),
                    DateTimePicker::make('end_at')
                        ->required()
                        ->label('Kraj'),
                ]),
            RichEditor::make('description')
                ->label('Opis')
        ];
    }
}
