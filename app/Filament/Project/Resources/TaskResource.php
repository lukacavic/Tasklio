<?php

namespace App\Filament\Project\Resources;

use App\Filament\Project\Resources\TaskResource\Pages\CreateTask;
use App\Models\Task;
use App\Models\User;
use App\TaskStatus;
use Awcodes\FilamentBadgeableColumn\Components\Badge;
use Awcodes\FilamentBadgeableColumn\Components\BadgeableColumn;
use Awcodes\Shout\Components\Shout;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
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
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    protected static ?string $label = 'Zadatak';

    protected static ?string $pluralModelLabel = 'Zadaci';

    protected static ?string $navigationLabel = 'Zadaci';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Naslov')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                DatePicker::make('start_at')
                    ->label('Početak rada')
                    ->default(now())
                    ->required(),
                DatePicker::make('deadline_at')
                    ->label('Rok završetka'),
                Select::make('members')
                    ->label('Djelatnici')
                    ->relationship('members')
                    ->options(function() {
                        $projectId = Filament::getTenant()->id;
                        return User::whereHas('projects', function($query) use ($projectId) {
                            $query->where('projects.id', $projectId);
                        })->get()->pluck('fullName', 'id');
                    })
                    ->multiple(),
                ToggleButtons::make('priority_id')
                    ->label('Prioritet')
                    ->default(1)
                    ->options([
                        1 => 'Niski',
                        2 => 'Srednji',
                        3 => 'Visoki'
                    ])
                    ->colors([
                        1 => 'warning',
                        2 => 'info',
                        3 => 'danger',
                    ])->inline(),
                RichEditor::make('description')
                    ->label('Opis')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                SpatieTagsInput::make('tags')
                    ->columnSpanFull()
                ->suggestions(['marko','ivan'])
                ->color(Color::Gray),
                FileUpload::make('attachments')
                    ->label('Privitci')
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordClasses(fn (Model $record) => match ($record->status_id) {
                1 => 'bg-primary',
                2 => 'border-s-2 border-orange-600 dark:border-orange-300',
                3 => 'border-s-2 border-green-600 dark:border-green-300',
                default => null,
            })
            ->recordTitleAttribute('name')
            ->emptyStateHeading('Trenutno nema upisanih zadataka')
            ->columns([
                TextColumn::make('title')
                    ->description(function (Task $record) {
                        return strip_tags($record->description);
                    })
                    ->icon(function (Task $record) {
                        if ($record->priority_id == 3) {
                            return 'heroicon-m-exclamation-triangle';
                        }

                        return null;
                    })
                    ->iconColor(Color::Orange)
                    ->iconPosition(IconPosition::After)
                    ->label('Naziv')
                    ->searchable(),
                TextColumn::make('creator.fullName')
                    ->label('Dodao')
                    ->sortable(),
                TextColumn::make('members.first_name')
                    ->label('Djelatnici')
                    ->sortable(),
                SelectColumn::make('status_id')
                    ->label('Status')
                    ->options(TaskStatus::class)
                    ->sortable(),
                SpatieTagsColumn::make('tags'),
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
            // 'view' => TaskResource\Pages\ViewTask::route('/{record}'),
            //'create' =>CreateTask::route('/create'),
            //'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
