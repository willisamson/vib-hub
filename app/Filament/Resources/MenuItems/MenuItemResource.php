<?php

namespace App\Filament\Resources\MenuItems;

use App\Filament\Resources\MenuItems\Pages\CreateMenuItem;
use App\Filament\Resources\MenuItems\Pages\EditMenuItem;
use App\Filament\Resources\MenuItems\Pages\ListMenuItems;
use App\Models\MenuItem;
use App\Support\Catalog;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBars3;

    protected static string|UnitEnum|null $navigationGroup = 'Website';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationLabel = 'Menüs';

    protected static ?string $modelLabel = 'Menüpunkt';

    protected static ?string $pluralModelLabel = 'Menüpunkte';

    protected static ?string $recordTitleAttribute = 'label';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Menüpunkt')
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    Select::make('location')->label('Menü')->options(Catalog::MENU_LOCATIONS)->required()->default('header')->native(false),
                    Select::make('type')->label('Ziel')->options(Catalog::MENU_TYPES)->required()->default('route')->live()->native(false),
                    TextInput::make('label')->label('Beschriftung')->required()->maxLength(60),
                    TextInput::make('label_en')->label('Beschriftung (EN)')->maxLength(60)
                        ->helperText('Leer = automatische Übersetzung oder deutscher Text.'),
                    Select::make('route')->label('Seite der Website')->options(Catalog::MENU_ROUTES)
                        ->visible(fn (Get $get): bool => $get('type') === 'route')
                        ->required(fn (Get $get): bool => $get('type') === 'route')
                        ->native(false),
                    Select::make('page_id')->label('Eigene Seite')->relationship('page', 'title')
                        ->visible(fn (Get $get): bool => $get('type') === 'page')
                        ->required(fn (Get $get): bool => $get('type') === 'page')
                        ->searchable()->preload(),
                    TextInput::make('url')->label('Adresse')->placeholder('https://… oder /pfad')
                        ->visible(fn (Get $get): bool => $get('type') === 'url')
                        ->required(fn (Get $get): bool => $get('type') === 'url')
                        ->maxLength(255)
                        ->regex('#^(https?://|/|mailto:|tel:)#i')
                        ->validationMessages(['regex' => 'Bitte mit https://, / , mailto: oder tel: beginnen.']),
                    TextInput::make('sort')->label('Reihenfolge')->numeric()->default(fn () => (int) MenuItem::query()->max('sort') + 1),
                ]),
            Section::make('Darstellung')
                ->columnSpanFull()
                ->columns(3)
                ->schema([
                    Toggle::make('is_active')->label('Sichtbar')->default(true),
                    Toggle::make('new_tab')->label('In neuem Tab öffnen'),
                    Toggle::make('highlight')->label('Hervorheben')->helperText('Nur im Hauptmenü, z. B. für die App.'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->reorderable('sort')
            ->paginated(false)
            ->columns([
                TextColumn::make('label')->label('Beschriftung')->weight(FontWeight::Bold)
                    ->description(fn (MenuItem $record): ?string => $record->label_en ? 'EN: '.$record->label_en : null),
                TextColumn::make('type')->label('Ziel')
                    ->state(fn (MenuItem $record): string => match ($record->type) {
                        'route' => Catalog::MENU_ROUTES[$record->route] ?? (string) $record->route,
                        'page' => 'Seite: '.($record->page?->title ?? '–'),
                        default => (string) $record->url,
                    })
                    ->color('gray')
                    ->limit(50),
                IconColumn::make('highlight')->label('Hervorgehoben')
                    ->icon(fn (bool $state) => $state ? Heroicon::Star : null)
                    ->color('warning'),
                ToggleColumn::make('is_active')->label('Sichtbar'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMenuItems::route('/'),
            'create' => CreateMenuItem::route('/create'),
            'edit' => EditMenuItem::route('/{record}/edit'),
        ];
    }
}
