<?php

namespace App\Filament\Project\Resources;

use App\Filament\Project\Resources\TaskResource\Pages\CreateTask;
use App\Filament\Shared\Components\AvatarColumn;
use App\Models\Task;
use App\Models\User;
use App\TaskPriority;
use App\TaskStatus;
use Awcodes\FilamentBadgeableColumn\Components\Badge;
use Awcodes\FilamentBadgeableColumn\Components\BadgeableColumn;
use Awcodes\Shout\Components\Shout;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Facades\Filament;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
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
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    protected static ?string $label = 'Zadatak';

    protected static ?string $pluralModelLabel = 'Zadaci';

    protected static ?string $navigationLabel = 'Zadaci';

    protected static ?string $recordTitleAttribute = 'title';

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

                Select::make('members')
                    ->label('Djelatnici')
                    ->relationship('members')
                    ->options(function () {
                        $projectId = Filament::getTenant()->id;
                        return User::whereHas('projects', function ($query) use ($projectId) {
                            $query->where('projects.id', $projectId);
                        })->get()->pluck('fullName', 'id');
                    })
                    ->multiple(),

                ToggleButtons::make('priority_id')
                    ->label('Prioritet')
                    ->default(1)
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
                    ->hintAction(function(){
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

                AvatarColumn::make('creator.avatar_url')
                    ->tooltip(function (Task $record) {
                        return $record->fullName;
                    })
                    ->label('Dodao')
                    ->sortable(),

                TextColumn::make('members.first_name')
                    ->label('Djelatnici')
                    ->sortable(),

                SelectColumn::make('status_id')
                    ->label('Status')
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
                TrashedFilter::make()
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
                    ExportBulkAction::make()
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
}
