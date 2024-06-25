<?php

namespace App\Filament\Project\Widgets;

use App\Filament\Project\Resources\MeetingsResource;
use App\Models\Event;
use App\Models\Meeting;
use App\Models\Project;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Saade\FilamentFullCalendar\Data\EventData;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    public string|null|\Illuminate\Database\Eloquent\Model $model = Event::class;

    public function eventDidMount(): string
    {
        return <<<JS
        function({ event, timeText, isStart, isEnd, isMirror, isPast, isFuture, isToday, el, view }){
            el.setAttribute("x-tooltip", "tooltip");
            el.setAttribute("x-data", "{ tooltip: '"+event.title+"' }");
        }
    JS;
    }

    public function onEventClick(array $event): void
    {
        if ($event['extendedProps']['model'] == Event::class) {
            $this->model = Event::class;
            $this->record = $this->resolveRecord($event['id']);
        } else if ($event['extendedProps']['model'] == Meeting::class) {
            $this->model = Meeting::class;
            $this->record = $this->resolveRecord($event['id']);
        }

        $this->mountAction('view', [
            'type' => 'click',
            'event' => $event,
        ]);
    }

    public function onEventDrop(array $event, array $oldEvent, array $relatedEvents, array $delta, ?array $oldResource, ?array $newResource): bool
    {
        if ($this->getModel()) {
            $this->record = $this->resolveRecord($event['id']);
        }

        $diffInHours = Carbon::parse($this->record->start_at)->diffInHours($this->record->end_at);

        $this->record->update([
            'start_at' => Carbon::parse($event['start']),
            'end_at' => Carbon::parse($event['start'])->addHours($diffInHours),
        ]);

        Notification::make()
            ->title('Promjena vremena događaja: ' . $this->record->title . '. Novo vrijeme: ' . Carbon::parse($event['start'])->toFormattedDateString())
            ->success()
            ->sendToDatabase($this->record->users);

        Notification::make()
            ->title('Vrijeme događaja promjenjeno')
            ->success()
            ->send();

        return false;
    }

    public function config(): array
    {
        return [
            'firstDay' => 1,
            'height' => 'auto',
            'editable' => true,
        ];
    }

    private function fetchEventsForCalendar(array $fetchInfo): \Illuminate\Database\Eloquent\Collection|array
    {
        return Event::query()
            ->where('project_id', Filament::getTenant()->id)
            ->where('start_at', '>=', $fetchInfo['start'])
            ->where('end_at', '<=', $fetchInfo['end'])
            ->get();
    }

    public function fetchEvents(array $fetchInfo): array
    {
        $meetings = $this->fetchMeetingsForCalendar($fetchInfo)->map(
            fn(Meeting $event) => EventData::make()
                ->id($event->id)
                ->extraProperties([
                    'model' => Meeting::class
                ])
                ->allDay(false)
                ->borderColor("")
                ->title($event->name)
                ->url(MeetingsResource::getUrl('view', ['record' => $event]), true)
                ->start($event->meeting_from)
                ->end($event->meeting_to)
        );

        $events = $this->fetchEventsForCalendar($fetchInfo)->map(
            fn(Event $event) => EventData::make()
                ->id($event->id)
                ->allDay(false)
                ->backgroundColor($event->color ?? 'gray')
                ->extraProperties([
                    'model' => Event::class
                ])
                ->borderColor("")
                ->title($event->title)
                ->start($event->start_at)
                ->end($event->end_at)
        );

        return collect([$meetings, $events])->flatten(1)->toArray();
    }

    public function fetchMeetingsForCalendar(array $fetchInfo): array|\Illuminate\Database\Eloquent\Collection
    {
        return Meeting::query()
            ->where('project_id', Filament::getTenant()->id)
            ->where('meeting_from', '>=', $fetchInfo['start'])
            ->where(function ($query) use ($fetchInfo) {
                $query->where('meeting_to', '<=', $fetchInfo['end'])
                    ->orWhereNull('meeting_to');
            })
            ->get();
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

    public function form(Form $form): Form
    {
        if ($this->model == Event::class) {
            return parent::form($form);
        } else if ($this->model == Meeting::class) {
            return MeetingsResource::form($form);
        }
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
                        ->required()
                        ->label('Početak'),
                    DateTimePicker::make('end_at')
                        ->required()
                        ->label('Kraj'),
                ]),

            Grid::make(2)->schema([
                Select::make('users')
                    ->multiple()
                    ->relationship('users')
                    ->label('Djelatnici')
                    ->options(Filament::getTenant()->users()->get()->pluck('full_name', 'id'))
                    ->native(false),

                SpatieTagsInput::make('tags')
                    ->label('Oznake'),
            ]),

            RichEditor::make('description')
                ->label('Opis')
        ];

    }

}
