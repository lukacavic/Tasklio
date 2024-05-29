<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ProjectResource\Pages\EditProject;
use App\Filament\App\Resources\ProjectResource\Pages\ListProjects;
use App\Filament\App\Resources\ProjectResource\Pages\ProjectDocuments;
use App\Filament\App\Resources\ProjectResource\Pages\ProjectTasks;
use App\Filament\App\Resources\ProjectResource\Pages\ProjectVault;
use App\Filament\Resources\ProjectResource\Pages\ProjectOverview;
use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use AymanAlhattami\FilamentPageWithSidebar\FilamentPageSidebar;
use AymanAlhattami\FilamentPageWithSidebar\PageNavigationItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationLabel = 'Projekti';

    protected static ?string $navigationIcon = 'heroicon-o-command-line';

    protected static ?string $recordTitleAttribute = 'name';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Naziv')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Toggle::make('internal')
                    ->label('Interni projekt')
                    ->live()
                    ->required(),

                Forms\Components\Select::make('client_id')
                    ->label('Klijent')
                    ->reactive()
                    ->options(Client::get()->pluck('name', 'id'))
                    ->visible(function (Get $get) {
                        return !$get('internal');
                    })
                    ->required(function (Get $get) {
                        return !$get('internal');
                    })
                    ->native(false)
                    ->options(Client::get()->pluck('name', 'id')),

                Forms\Components\Select::make('clients')
                    ->relationship('clients')
                    ->reactive()
                    ->options(Client::get()->pluck('name', 'id'))
                    ->visible(function (Get $get) {
                        return $get('internal');
                    })
                    ->multiple()
                    ->label('Vezani klijenti'),

                Forms\Components\Select::make('users')
                    ->required()
                    ->relationship('users')
                    ->options(User::get()->pluck('fullName', 'id'))
                    ->multiple()
                    ->label('Djelatnici'),

                Forms\Components\DatePicker::make('deadline_at')
                    ->label('Rok završetka'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(function($record) {
                return ProjectOverview::getUrl([$record->id]);
            })
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Projekt')
                    ->searchable(),
                Tables\Columns\IconColumn::make('internal')
                    ->label('Interni projekt')
                    ->boolean(),
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Klijent'),
                Tables\Columns\TextColumn::make('deadline_at')
                    ->label('Rok završetka')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => ListProjects::route('/'),
            // 'create' => Pages\CreateProject::route('/create'),
            'edit' => EditProject::route('/{record}/edit'),
            'documents' => ProjectDocuments::route('/{record}/documents'),
            'overview' => ProjectOverview::route('/{record}/overview'),
            'vaults' => ProjectVault::route('/{record}/vaults'),
            'tasks' => ProjectTasks::route('/{record}/tasks'),
        ];
    }

    public static function sidebar(Model $record): FilamentPageSidebar
    {
        return FilamentPageSidebar::make()
            ->sidebarNavigation()
            ->setTitle($record->name)
            ->setDescription('PROJEKT')
            ->setNavigationItems([
                PageNavigationItem::make('Pregled')
                    ->icon('heroicon-o-information-circle')
                    ->url(function () use ($record) {
                        return static::getUrl('overview', ['record' => $record->id]);
                    })
                    ->isActiveWhen(function () {
                        return request()->routeIs(ProjectOverview::getRouteName());
                    }),
              PageNavigationItem::make('Zadaci')
                    ->icon('heroicon-o-rectangle-stack')
                    ->badge(function () use ($record) {
                        return $record->tasks->count();
                    })
                    ->isActiveWhen(function () {
                        return request()->routeIs(ProjectTasks::getRouteName());
                    })
                    ->url(function () use ($record) {
                        return static::getUrl('tasks', ['record' => $record->id]);
                    }),
                /*  PageNavigationItem::make('Upiti')
                    ->icon('heroicon-o-lifebuoy')
                    ->badge(function () use ($record) {
                        return $record->tickets->count();
                    })
                    ->isActiveWhen(function () {
                        return request()->routeIs(Pages\ProjectTickets::getRouteName());
                    })
                    ->url(function () use ($record) {
                        return static::getUrl('tickets', ['record' => $record->id]);
                    }),*/
                PageNavigationItem::make('Dokumenti')
                    ->icon('heroicon-o-paper-clip')
                    ->badge(function () use ($record) {
                        return $record->documents->count();
                    })
                    ->isActiveWhen(function () {
                        return request()->routeIs(ProjectDocuments::getRouteName());
                    })
                    ->url(function () use ($record) {
                        return static::getUrl('documents', ['record' => $record->id]);
                    }),
                PageNavigationItem::make('Trezor')
                    ->icon('heroicon-o-key')
                    ->badge(function () use ($record) {
                        return $record->vaults->count();
                    })
                    ->isActiveWhen(function () {
                        return request()->routeIs(ProjectVault::getRouteName());
                    })
                    ->url(function () use ($record) {
                        return static::getUrl('vaults', ['record' => $record->id]);
                    })

            ]);
    }
}
