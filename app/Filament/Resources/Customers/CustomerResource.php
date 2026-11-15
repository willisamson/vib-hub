<?php

namespace App\Filament\Resources\Customers;

use App\Filament\Resources\Customers\Pages\CreateCustomer;
use App\Filament\Resources\Customers\Pages\EditCustomer;
use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Filament\Resources\Customers\RelationManagers\InquiriesRelationManager;
use App\Models\Customer;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|UnitEnum|null $navigationGroup = 'Kunden';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Kunde';

    protected static ?string $pluralModelLabel = 'Kunden';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'company', 'email'];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Kundenkonto')
                ->description('Kunden registrieren sich selbst unter /konto. Hier können Sie Angaben korrigieren oder ein Konto sperren.')
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    TextInput::make('name')->label('Name')->required()->maxLength(120),
                    TextInput::make('company')->label('Firma')->maxLength(160),
                    TextInput::make('email')->label('E-Mail')->email()->required()->maxLength(190)->unique(ignoreRecord: true),
                    TextInput::make('phone')->label('Telefon')->tel()->maxLength(60),
                    Select::make('locale')->label('Sprache')->options(['de' => 'Deutsch', 'en' => 'English'])->default('de')->native(false),
                    Toggle::make('is_active')->label('Anmeldung erlaubt')->default(true)->inline(false)
                        ->helperText('Aus = Konto gesperrt, der Kunde kann sich nicht mehr anmelden.'),
                    TextInput::make('password')
                        ->label(fn ($livewire): string => $livewire instanceof CreateRecord ? 'Passwort' : 'Neues Passwort')
                        ->password()->revealable()
                        ->minLength(10)
                        ->required(fn ($livewire): bool => $livewire instanceof CreateRecord)
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->helperText('Leer lassen, um das bisherige Passwort zu behalten.'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')->label('Name')->searchable()->sortable()->weight(FontWeight::Bold)
                    ->description(fn (Customer $record): ?string => $record->company),
                TextColumn::make('email')->label('E-Mail')->searchable()->copyable(),
                TextColumn::make('phone')->label('Telefon')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('inquiries_count')->label('Anfragen')->counts('inquiries')->badge()->color('gray')->sortable(),
                TextColumn::make('created_at')->label('Registriert')->date('d.m.Y')->sortable(),
                TextColumn::make('last_login_at')->label('Letzte Anmeldung')->since()->sortable()->placeholder('–'),
                ToggleColumn::make('is_active')->label('Aktiv'),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Anmeldung erlaubt'),
            ])
            ->recordActions([
                Action::make('mail')
                    ->label('E-Mail')
                    ->icon(Heroicon::OutlinedEnvelope)
                    ->url(fn (Customer $record): string => 'mailto:'.$record->email),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            InquiriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomers::route('/'),
            'create' => CreateCustomer::route('/create'),
            'edit' => EditCustomer::route('/{record}/edit'),
        ];
    }
}
