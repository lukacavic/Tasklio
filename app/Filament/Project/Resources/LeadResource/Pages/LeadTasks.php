<?php

namespace App\Filament\Project\Resources\LeadResource\Pages;

use App\Filament\Project\Resources\LeadResource;
use App\Models\Lead;
use App\Models\Task;
use App\Models\User;
use App\TaskStatus;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Facades\Filament;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class LeadTasks extends ManageRelatedRecords
{
    use HasPageSidebar;

    protected static string $resource = LeadResource::class;

    protected static string $relationship = 'tasks';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $title = 'Zadaci';

    protected function getHeaderActions(): array
    {
        return LeadResource\Helpers\Actions\HeaderActions::getHeaderActions();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Naslov')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                DateTimePicker::make('start_at')
                    ->label('Početak rada')
                    ->default(now())
                    ->required(),
                DateTimePicker::make('deadline_at')
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
                Select::make('priority_id')
                    ->options([
                        1 => 'Niski',
                        2 => 'Srednji',
                        3 => 'Visoki'
                    ])
                    ->required()
                    ->default(1)
                    ->label('Prioritet'),
                RichEditor::make('description')
                    ->label('Opis')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                FileUpload::make('attachments')
                    ->label('Privitci')
                    ->columnSpanFull()
            ])->columns(1);
    }

    public function table(Table $table): Table
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
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Dodaj')
                    ->mutateFormDataUsing(function($data) {
                        $data['related_id'] = $this->record->id;
                        $data['related_type'] = Lead::class;
                        $data['project_id'] = Filament::getTenant()->id;

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hiddenLabel(),
                Tables\Actions\DeleteAction::make()
                    ->hiddenLabel(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }
}
