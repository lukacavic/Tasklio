<?php

namespace App\Filament\Shared\Resources\ClientResource\Pages;

use App\Filament\Shared\Resources\ClientResource;
use App\Models\Contact;
use AymanAlhattami\FilamentPageWithSidebar\Traits\HasPageSidebar;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use http\Client;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class ClientContacts extends ManageRelatedRecords
{
    use HasPageSidebar;

    protected static string $resource = ClientResource::class;

    protected static string $relationship = 'contacts';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $title = 'Kontakti';

    public function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Nema učitanih kontakata')
            ->emptyStateDescription('Dodajte novi kontakt za klijenta')
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('full_name')
                    ->description(function (Contact $record) {
                        return $record->position;
                    })
                    ->label('Ime i prezime'),

                TextColumn::make('email')
                    ->label('Email')
                    ->copyable()
                    ->copyMessage('Email adresa kopirana'),

                TextColumn::make('primaryContactForClient')
                    ->label('Primarni kontakt')
                    ->formatStateUsing(function($state, $record) {
                        return $record ? 'Primarni' : null;
                    })
                    ->badge(function ($state, $record) {
                        return $record ? 'heroicon-o-check' : null;
                    }),

                TextColumn::make('phone')
                    ->label('Telefon'),

                TextColumn::make('created_at')
                    ->label('Vrijeme kreiranja')
                    ->since(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Dodaj')
                    ->modalHeading('Novi kontakt'),
            ])
            ->actions([
                Action::make('make-primary')
                    ->hiddenLabel()
                    ->icon('heroicon-o-user-circle')
                    ->tooltip('Postavi kao primarni kontakt')
                    ->action(function ($record) {
                        $client = \App\Models\Client::find($record->client_id);

                        $client->update(['primary_contact_id' => $record->id]);
                    }),
                EditAction::make()
                    ->modalHeading('Izmjena kontakta'),
                DeleteAction::make()
                    ->modalHeading('Brisanje kontakta?')
                    ->hiddenLabel(),
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

    public function getSendEmailAction(): Action
    {
        return Action::make('send-mail')
            ->modalHeading('Pošalji email')
            ->form([
                TagsInput::make('receiver')
                    ->required()
                    ->hintActions([
                        \Filament\Forms\Components\Actions\Action::make('show-cc')
                            ->label('CC primatelji')
                            ->link()
                    ])
                    ->placeholder('Unesite email primatelja')
                    ->label('Primatelj/i'),

                TextInput::make('subject')
                    ->required()
                    ->placeholder('Naslov email-a')
                    ->label('Naslov'),

                TinyEditor::make('content')
                    ->label('Sadržaj')
                    ->required()
                    ->placeholder('Unesite poruku za slanje')
                    ->minHeight(400)
            ])
            ->action(function (array $data) {

            })
            ->hiddenLabel()
            ->icon('heroicon-o-at-symbol');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('full_name')
                    ->label('Ime i prezime')
                    ->required()
                    ->maxLength(255),
                TextInput::make('position')
                    ->label('Pozicija/Titula')
                    ->maxLength(255),
                TextInput::make('email')
                    ->unique(Contact::class, 'email', ignoreRecord: true)
                    ->prefixIcon('heroicon-o-at-symbol')
                    ->label('Email')
                    ->email()
                    ->maxLength(255),
                PhoneInput::make('phone')
                    ->label('GSM'),

            ])->columns(2);
    }
}
