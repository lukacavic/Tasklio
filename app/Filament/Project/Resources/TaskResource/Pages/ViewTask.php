<?php

namespace App\Filament\Project\Resources\TaskResource\Pages;

use App\Filament\Project\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskCompleted;
use App\TaskPriority;
use App\TaskStatus;
use Filament\Actions;
use Filament\Actions\ActionGroup;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\SpatieTagsEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Colors\Color;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;
use JaOcero\ActivityTimeline\Components\ActivityDate;
use JaOcero\ActivityTimeline\Components\ActivityDescription;
use JaOcero\ActivityTimeline\Components\ActivityIcon;
use JaOcero\ActivityTimeline\Components\ActivitySection;
use JaOcero\ActivityTimeline\Components\ActivityTitle;
use RyanChandler\Comments\Models\Comment;
use Spatie\MediaLibrary\Support\MediaStream;

class ViewTask extends ViewRecord implements HasForms
{
    protected static string $resource = TaskResource::class;

    protected static string $view = 'filament.resources.task-resource.view';

    public  $selectedCommentId;

    protected $listeners = ['doDeleteComment'];

    public function editComment(int $commentId): void
    {
        $this->form->fill([
            'comment' => $this->record->comments->where('id', $commentId)->first()?->content
        ]);

        $this->selectedCommentId = $commentId;
    }

    public function submitComment(): void
    {
        $data = $this->form->getState();

        if ($this->selectedCommentId) {
            Comment::where('id', $this->selectedCommentId)
                ->update([
                    'content' => $data['comment']
                ]);
        } else {
            $this->getRecord()->comment($data['comment']);
        }

        $this->record->refresh();
        $this->cancelEditComment();

        Notification::make()
            ->title('Spremljeno')
            ->success()
            ->send();
    }

    public function cancelEditComment(): void
    {
        $this->form->fill();
        $this->selectedCommentId = null;
    }

    public function doDeleteComment(int $commentId): void
    {
        Comment::where('id', $commentId)->delete();

        $this->record->refresh();
    }

    public function deleteComment(int $commentId): void
    {
        Notification::make()
            ->warning()
            ->title('Potvrda brisanja?')
            ->body('Jeste li sigurni da želite izbrisati komentar?')
            ->actions([
                \Filament\Notifications\Actions\Action::make('confirm')
                    ->label('Da, izbriši')
                    ->color('danger')
                    ->button()
                    ->close()
                    ->dispatch('doDeleteComment', compact('commentId')),
                \Filament\Notifications\Actions\Action::make('cancel')
                    ->label('Odustani')
                    ->close()
            ])
            ->persistent()
            ->send();
    }

    public function mount($record): void
    {
        parent::mount($record);
        $this->form->fill();
    }

    public function getRecordTitle(): string|Htmlable
    {
        return $this->record->title;
    }

    protected function getActions(): array
    {
        return [
            $this->getMarkAsCompletedAction(),
            $this->getSwitchStatusActions(),
            ActionGroup::make([
                $this->getActivityLogActions(),
                $this->getChangeAssignedUsersAction(),
                Actions\EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->hiddenLabel(),
                Actions\DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->hiddenLabel(),
            ])
                ->hiddenLabel()
                ->button()

        ];
    }

    public function getMarkAsCompletedAction(): Actions\Action
    {
        return Actions\Action::make('mark-completed')
            ->visible(function (Task $record) {
                return $record->status_id != TaskStatus::Completed->value;
            })
            ->hiddenLabel()
            ->tooltip('Označi zadatak završenim')
            ->requiresConfirmation()
            ->modalHeading('Označiti zadatak završenim?')
            ->color(Color::Green)
            ->icon('heroicon-o-check')
            ->action(function (Task $record) {
                $record->update([
                    'status_id' => TaskStatus::Completed,
                    'completed_at' => now()
                ]);

                $record->addLog('Označio zadatak riješenim');

                \Illuminate\Support\Facades\Notification::send($record->usersToNotify(), new TaskCompleted($record));

                $this->getRecord()->refresh();
            });
    }

    private function getSwitchStatusActions(): ActionGroup
    {
        return ActionGroup::make($this->getDynamicStatusActions())
            ->tooltip('Promjena statusa zadatka')
            ->label(function () {
                return TaskStatus::from($this->getRecord()->status_id)->getLabel();
            })->color(function () {
                return TaskStatus::from($this->getRecord()->status_id)->getColor();
            })->icon(TaskStatus::from($this->record->status_id)->getIcon())
            ->button();
    }

    private function getDynamicStatusActions(): array
    {
        $actions = [];

        foreach (TaskStatus::cases() as $taskStatus) {
            if ($taskStatus->value == $this->record->status_id) continue;

            $action = new Actions\Action($taskStatus->getLabel());
            $action->label($taskStatus->getLabel());
            $action->color($taskStatus->getColor());
            $action->action(function ($data) use ($taskStatus) {
                $this->record->updateTaskStatus($taskStatus->value);
            });
            $action->icon($taskStatus->getIcon());

            $actions[$taskStatus->getLabel()] = $action;
        }

        return $actions;
    }

    private function getActivityLogActions()
    {
        return Actions\Action::make('activity_log')
            ->hiddenLabel()
            ->label('Log aktivnosti')
            ->icon('heroicon-o-information-circle')
            ->modalSubmitAction(false)
            ->color(Color::Cyan)
            ->tooltip('Povijest aktivnosti')
            ->modalCancelAction(false)
            ->modalHeading('Povijest aktivnosti')
            ->slideOver()
            ->infolist(function (Infolist $infolist) {
                return $infolist
                    ->state([
                        'activities' => $this->getRecord()->activities()->with('causer')->latest()->get()
                    ])
                    ->schema([
                        ActivitySection::make('activities')
                            ->schema([
                                ActivityTitle::make('causer.fullName')
                                    ->placeholder('No title is set')
                                    ->allowHtml(),
                                ActivityDescription::make('description')
                                    ->placeholder('No description is set')
                                    ->allowHtml(),
                                ActivityDate::make('created_at')
                                    ->date('F j, Y g:i A', 'Asia/Manila'),
                                ActivityIcon::make('status')
                                    ->icon(fn(string|null $state): string|null => match ($state) {
                                        'ideation' => 'heroicon-m-light-bulb',
                                        'drafting' => 'heroicon-m-bolt',
                                        'reviewing' => 'heroicon-m-document-magnifying-glass',
                                        'published' => 'heroicon-m-rocket-launch',
                                        default => null,
                                    })
                                    ->color(fn(string|null $state): string|null => match ($state) {
                                        'ideation' => 'purple',
                                        'drafting' => 'info',
                                        'reviewing' => 'warning',
                                        'published' => 'success',
                                        default => 'gray',
                                    }),
                            ])
                            ->showItemsCount(10)
                            ->emptyStateHeading('Nema aktivnosti.')
                            ->emptyStateDescription('Trenutno nema aktivnosti na zadatku, provjerite kasnije :)')
                            ->emptyStateIcon('heroicon-o-bolt-slash')
                            ->showItemsLabel('Prikaži starije')
                            ->showItemsIcon('heroicon-m-chevron-down')
                            ->showItemsColor('gray')
                            ->aside(true)
                            ->headingVisible(false)
                    ]);
            });
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Fieldset::make('Osnovne informacije')
                ->schema([
                    TextEntry::make('title')
                        ->label('Naziv'),

                    TextEntry::make('userCreated.fullName')
                        ->label('Kreirao'),

                    SpatieTagsEntry::make('tags')
                        ->label('Oznake'),

                    TextEntry::make('priority_id')
                        ->badge()
                        ->color(TaskPriority::class)
                        ->formatStateUsing(function (Task $record) {
                            return TaskPriority::from($record->priority_id);
                        })
                        ->label('Prioritet'),

                    TextEntry::make('members.first_name')
                        ->label('Djelatnici na zadatku')
                        ->separator(','),

                    TextEntry::make('status_id')
                        ->label('Status')
                        ->formatStateUsing(function (Task $record) {
                            return TaskStatus::from($record->status_id);
                        }),

                    TextEntry::make('deadline_at')
                        ->label('Rok završetka')
                        ->date()
                        ->since(),

                    TextEntry::make('description')
                        ->label('Opis zadatka')
                        ->formatStateUsing(function (Task $record) {
                            return strip_tags($record->description);
                        })
                        ->columnSpanFull()
                ])->columns(4),

            Fieldset::make('Privitci')
                ->key(Str::random())
                ->visible($this->record->media()->exists())
                ->columnSpanFull()
                ->label(function () {
                    return 'Privitci (' . $this->record->media()->count() . ' datoteka).';
                })
                ->schema([
                    RepeatableEntry::make('media')
                        ->label('Privitci')
                        ->hintActions([
                            /*Action::make('upload')
                                ->label('Učitaj privitke')
                                ->form([
                                    SpatieMediaLibraryFileUpload::make('attachments')
                                        ->collection('task')
                                        ->multiple()
                                        ->required()
                                        ->label('Privitci')
                                        ->columnSpanFull()
                                ])
                                ->action(function (Task $record, array $data, Action $action) {
                                    dd($action->getFormData());
                                })
                                ->icon('heroicon-m-paper-clip'),*/

                            Action::make('download')
                                ->action(function (Task $record) {
                                    $downloads = $record->getMedia('task');

                                    $record->addLog('Napravio download svih dokumenata zadatka.');

                                    return MediaStream::create('attachments.zip')->addMedia($downloads);
                                })
                                ->label('Preuzmi sve')
                                ->icon('heroicon-m-arrow-down-tray'),

                        ])
                        ->columnSpanFull()
                        ->grid(3)
                        ->schema(function (Task $task) {
                            return [
                                TextEntry::make('name')
                                    ->columnSpan(2)
                                    ->label('Datoteka')
                                    ->hintAction(
                                        Action::make('download')
                                            ->label('Preuzmi')
                                            ->action(function ($record) use ($task) {
                                                $task->addLog('Napravio download datoteke: ' . $record->name);

                                                return response()->download($record->getPath(), $record->file_name);
                                            })
                                            ->hiddenLabel()
                                            ->icon('heroicon-m-arrow-down-tray')
                                    )
                            ];
                        })
                ])
        ]);
    }

    private function getChangeAssignedUsersAction(): Actions\Action
    {
        return Actions\Action::make('updatTaskUsers')
            ->label('Promjena djelatnika')
            ->visible(function (Task $record) {
                return $record->status_id != TaskStatus::Completed->value;
            })
            ->hiddenLabel()
            ->tooltip('Promjena djelatnika na zadatku')
            ->modalHeading('Promjena djelatnika na zadatku')
            ->fillForm(fn(Task $record): array => [
                'new-members' => array_values($record->members->pluck('id')->toArray())
            ])
            ->form([
                Select::make('new-members')
                    ->required()
                    ->options(Filament::getTenant()->users()->get()->pluck('fullName', 'id'))
                    ->native(false)
                    ->multiple(true)
                    ->label('Djelatnici na zadatku')
                    ->columnSpanFull()
            ])
            ->action(function (array $data, Task $record): void {
                $record->members()->sync($data['new-members']);

                Notification::make()
                    ->info()
                    ->body('Dodjeljeni ste na zadatak: ' . $record->title)
                    ->title('Novi zadatak')
                    ->sendToDatabase(User::find($data['new-members']));
            })
            ->icon('heroicon-o-user');
    }

    public function form(Form $form): Form
    {
        return $form->disabled(false)->schema([
            Textarea::make('comment')
                ->label('Dodaj komentar')
                ->columnSpanFull()
        ]);
    }
}
