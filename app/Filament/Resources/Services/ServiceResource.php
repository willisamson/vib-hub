<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\Pages\CreateService;
use App\Filament\Resources\Services\Pages\EditService;
use App\Filament\Resources\Services\Pages\ListServices;
use App\Models\Service;
use App\Support\Catalog;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static string|UnitEnum|null $navigationGroup = 'Inhalte';

    protected static ?int $navigationSort = 10;

    protected static ?string $modelLabel = 'Leistung';

    protected static ?string $pluralModelLabel = 'Leistungen';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Leistung')
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->label('Titel')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Set $set, Get $get, ?string $state): void {
                            if (blank($get('slug'))) {
                                $set('slug', Str::slug((string) $state, '-', 'de'));
                            }
                        }),
                    TextInput::make('slug')
                        ->label('URL-Kürzel')
                        ->helperText('Erscheint in der Adresse: vib-hub.com/leistungen/…')
                        ->required()
                        ->alphaDash()
                        ->unique(ignoreRecord: true),
                    Select::make('category')
                        ->label('Kategorie')
                        ->options(Catalog::SERVICE_CATEGORIES)
                        ->required()
                        ->default('pruefung'),
                    Select::make('icon')
                        ->label('Symbol')
                        ->options(Catalog::ICONS)
                        ->required()
                        ->default('sine'),
                    Textarea::make('excerpt')
                        ->label('Kurzbeschreibung')
                        ->helperText('Für Karten und Suchmaschinen (ca. 1–2 Sätze).')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
            Section::make('Inhalt')
                ->columnSpanFull()
                ->schema([
                    RichEditor::make('body')
                        ->label('Beschreibung')
                        ->columnSpanFull(),
                    Repeater::make('features')
                        ->label('Leistungsmerkmale')
                        ->simple(TextInput::make('feature')->required()->maxLength(160))
                        ->addActionLabel('Merkmal hinzufügen')
                        ->reorderable()
                        ->defaultItems(0)
                        ->columnSpanFull(),
                ]),
            Section::make('Darstellung')
                ->columnSpanFull()
                ->schema([
                    Grid::make(4)->schema([
                        Select::make('demo')
                            ->label('Interaktive Demo auf der Detailseite')
                            ->options(Catalog::DEMOS)
                            ->placeholder('Keine')
                            ->columnSpan(2),
                        TextInput::make('sort')
                            ->label('Reihenfolge')
                            ->numeric()
                            ->default(0),
                        Grid::make(1)->schema([
                            Toggle::make('is_published')->label('Veröffentlicht')->default(true),
                            Toggle::make('is_featured')->label('Auf Startseite hervorheben'),
                        ])->columnSpan(1),
                    ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->reorderable('sort')
            ->columns([
                TextColumn::make('title')->label('Titel')->searchable()->sortable()->weight(FontWeight::Bold)
                    ->description(fn (Service $record): ?string => Str::limit((string) $record->excerpt, 80)),
                TextColumn::make('category')->label('Kategorie')->badge()
                    ->formatStateUsing(fn (?string $state): string => Catalog::label(Catalog::SERVICE_CATEGORIES, $state)),
                IconColumn::make('is_featured')->label('Startseite')->boolean(),
                ToggleColumn::make('is_published')->label('Online'),
                TextColumn::make('updated_at')->label('Geändert')->since()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')->label('Kategorie')->options(Catalog::SERVICE_CATEGORIES),
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
            'index' => ListServices::route('/'),
            'create' => CreateService::route('/create'),
            'edit' => EditService::route('/{record}/edit'),
        ];
    }
}
