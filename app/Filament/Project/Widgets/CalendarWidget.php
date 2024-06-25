<?php

namespace App\Filament\Project\Widgets;

use App\Models\Event;
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

    public function fetchEvents(array $fetchInfo): array
    {
        return Event::query()
            ->where('project_id', Filament::getTenant()->id)
            ->where('start_at', '>=', $fetchInfo['start'])
            ->where('end_at', '<=', $fetchInfo['end'])
            ->get()
            ->map(
                fn(Event $event) => EventData::make()
                    ->id($event->id)
                    ->resourceId(rand(1, 3))
                    ->allDay(false)
                    ->borderColor("")
                    ->title($event->title)
                    ->start($event->start_at)
                    ->backgroundColor($event->color != null ? $event->color : 'gray')
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
