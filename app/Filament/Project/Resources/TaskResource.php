<?php

namespace App\Filament\Project\Resources;

use App\Models\Client;
use App\Models\Lead;
use App\Models\Task;
use App\Models\User;
use App\ProjectSettingsItems;
use App\TaskPriority;
use App\TaskStatus;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Facades\Filament;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    protected static ?string $label = 'zadatak';

    protected static ?string $pluralModelLabel = 'zadaci';

    protected static ?string $navigationLabel = 'Zadaci';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $modelLabel = 'zadatak';

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Naziv' => $record->title,
            'Opis' => strip_tags($record->description)
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Naslov')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                DatePicker::make('deadline_at')
                    ->label('Rok završetka'),

                SpatieTagsInput::make('tags')
                    ->label('Oznake')
                    ->color(Color::Gray),

                Select::make('related_type')
                    ->label('Vezan za')
                    ->live()
                    ->native(false)
                    ->options(self::getRelatedTypeOptions()),

                Select::make('related_id')
                    ->label('Vezani modul')
                    ->required(function (Get $get) {
                        return $get('related_type') != null;
                    })
                    ->native(false)
                    ->options(function (Get $get) {
                        if ($get('related_type') == Client::class) {
                            return Client::whereHas('projects', function ($query) {
                                return $query->where('project_id', Filament::getTenant()->id);
                            })->pluck('name', 'id');
                        } else if ($get('related_type') == Lead::class) {
                            return Lead::where('project_id', Filament::getTenant()->id)->get()->pluck('company', 'id');
                        }

                        return collect();
                    }),

                Select::make('project_milestone_id')
                    ->label('Plan (Milestone)')
                    ->createOptionForm(fn(Form $form) => ProjectMilestoneResource::form($form))
                    ->createOptionUsing(function (array $data) {
                        $record = Filament::getTenant()->projectMilestones()->create([
                            'name' => $data['name'],
                            'project_id' => Filament::getTenant()->id,
                            'start_date' => $data['start_date'],
                            'due_date' => $data['due_date'],
                            'description' => $data['description'],
                        ]);

                        return $record->getKey(); //like this
                    })
                    ->native(false)
                    ->options(Filament::getTenant()->projectMilestones()->current()->orWhere(function ($query) {
                        $query->future();
                    })->get()->pluck('name', 'id')),

                Select::make('members')
                    ->label('Djelatnici')
                    ->relationship(name: 'members', modifyQueryUsing: function (Builder $query) {
                        return $query->orderBy('first_name');
                    })
                    ->getOptionLabelFromRecordUsing(fn(Model $record) => "{$record->first_name} {$record->last_name}")
                    ->searchable(['first_name', 'last_name'])
                    ->options(function () {
                        $projectId = Filament::getTenant()->id;
                        return User::whereHas('projects', function ($query) use ($projectId) {
                            $query->where('projects.id', $projectId);
                        })->get()->pluck('fullName', 'id');
                    })
                    ->multiple(),

                ToggleButtons::make('priority_id')
                    ->label('Prioritet')
                    ->default(TaskPriority::Normal->value)
                    ->grouped()
                    ->options(TaskPriority::class)
                    ->inline(),

                Placeholder::make('divider')
                    ->columnSpanFull()
                    ->hiddenLabel()
                    ->visible(false)
                    ->content(new HtmlString('<hr>')),

                TableRepeater::make('childTasks')
                    ->visible(false)
                    ->addActionLabel('Dodaj stavku')
                    ->extraItemActions([
                        Action::make('saveAsTemplate')
                            ->icon('heroicon-m-envelope')
                            ->action(function (array $arguments, Repeater $component): void {

                            }),
                    ])
                    ->showLabels()
                    ->emptyLabel('There are no users registered.')
                    ->label('Podzadaci (3/4)')
                    ->streamlined()
                    ->columnSpanFull()
                    ->renderHeader(false)
                    ->hintAction(function () {
                        return Action::make('hide-completed')
                            ->color(Color::Gray)
                            ->label('Sakrij riješeno');
                    })
                    ->headers([
                        Header::make('name')->width('250px'),
                        Header::make('assignedUser')->width('250px'),
                        Header::make('completed')->width('50px')->align(Alignment::Right),
                    ])
                    ->schema([
                        TextInput::make('name')
                            ->placeholder('Naziv zadatka...')
                            ->autofocus()
                            ->hiddenLabel()
                            ->required(),
                        Select::make('assigned_user_id')
                            ->prefixIcon(('heroicon-o-user'))
                            ->options(User::get()->pluck('fullName', 'id'))
                            ->native(false)
                            ->hiddenLabel(),
                        Toggle::make('completed')
                            ->onColor(Color::Green)
                            ->onIcon('heroicon-m-check')
                            ->inline()
                            ->label('Završeno?')
                            ->default(false),

                    ]),

                RichEditor::make('description')
                    ->label('Opis')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),

                SpatieMediaLibraryFileUpload::make('attachments')
                    ->collection('task')
                    ->multiple()
                    ->label('Privitci')
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->emptyStateHeading('Trenutno nema upisanih zadataka')
            ->columns([
                TextColumn::make('title')
                    ->description(function (Task $record) {
                        return strip_tags(Str::limit($record->description, 40));
                    })
                    ->tooltip(function (Task $record) {
                        return strip_tags($record->description);
                    })
                    ->label('Naziv')
                    ->searchable(),

                ImageColumn::make('members.avatar')
                    ->circular()
                    ->stacked()
                    ->label('Djelatnici'),

                SelectColumn::make('status_id')
                    ->label('Status')
                    ->selectablePlaceholder(false)
                    ->options(TaskStatus::class)
                    ->sortable(),

                SpatieTagsColumn::make('tags')->label('Oznake'),

                TextColumn::make('deadline_at')
                    ->label('Rok završetka')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('project_milestone_id')
                    ->label('Plan (Milestone)')
                    ->native(false)
                    ->multiple()
                    ->options(Filament::getTenant()->projectMilestones()->get()->pluck('name', 'id')),

                SelectFilter::make('user_id')
                    ->label('Kreirao')
                    ->native(false)
                    ->multiple()
                    ->options(Filament::getTenant()->users()->get()->pluck('fullName', 'id')),

                Filter::make('has_media')
                    ->label('Sadrži privitak')
                    ->baseQuery(function ($query) {
                        return $query->whereHas('media');
                    })
                    ->toggle()
            ])
            ->actions([
                ViewAction::make()->hiddenLabel(),
                EditAction::make()->hiddenLabel(),
                DeleteAction::make()->hiddenLabel(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => TaskResource\Pages\ListTasks::route('/'),
            'view' => TaskResource\Pages\ViewTask::route('/{record}'),
            //'create' =>CreateTask::route('/create'),
            //'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }

    private static function getRelatedTypeOptions(): array
    {
        $related = [];

        $related[Client::class] = 'Klijent';

        if (Filament::getTenant()->settings()->get(ProjectSettingsItems::LEADS_MANAGEMENT_ENABLED->value)) {
            $related[Lead::class] = 'Potencijalni klijent';
        }

        return $related;
    }
}
