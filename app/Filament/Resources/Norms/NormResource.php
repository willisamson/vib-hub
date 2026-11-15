<?php

namespace App\Filament\Resources\Norms;

use App\Filament\Resources\Norms\Pages\CreateNorm;
use App\Filament\Resources\Norms\Pages\EditNorm;
use App\Filament\Resources\Norms\Pages\ListNorms;
use App\Models\Norm;
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
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class NormResource extends Resource
{
    protected static ?string $model = Norm::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static string|UnitEnum|null $navigationGroup = 'Inhalte';

    protected static ?int $navigationSort = 30;

    protected static ?string $modelLabel = 'Norm';

    protected static ?string $pluralModelLabel = 'Normen';

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Norm')
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    TextInput::make('code')
                        ->label('Bezeichnung')
                        ->placeholder('z. B. DIN EN 60068-2-6')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Set $set, Get $get, ?string $state): void {
                            if (blank($get('slug'))) {
                                $set('slug', Str::slug((string) $state, '-', 'de'));
                            }
                        }),
                    TextInput::make('slug')->label('URL-Kürzel')->required()->alphaDash()->unique(ignoreRecord: true),
                    TextInput::make('title')->label('Titel')->required()->maxLength(255)->columnSpanFull(),
                    Select::make('category')->label('Kategorie')->options(Catalog::NORM_CATEGORIES)->required()->default('allgemein'),
                    TextInput::make('sort')->label('Reihenfolge')->numeric()->default(0),
                    Textarea::make('excerpt')->label('Kurzbeschreibung')->rows(3)->columnSpanFull(),
                ]),
            Section::make('Details')
                ->columnSpanFull()
                ->schema([
                    Repeater::make('parameters')
                        ->label('Typische Prüfparameter')
                        ->schema([
                            TextInput::make('label')->label('Merkmal')->required(),
                            TextInput::make('value')->label('Wert')->required(),
                        ])
                        ->columns(2)
                        ->addActionLabel('Parameter hinzufügen')
                        ->defaultItems(0),
                    RichEditor::make('body')->label('Erläuterung'),
                    Toggle::make('is_published')->label('Veröffentlicht')->default(true),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->reorderable('sort')
            ->columns([
                TextColumn::make('code')->label('Norm')->searchable()->sortable()->weight(FontWeight::Bold),
                TextColumn::make('title')->label('Titel')->searchable()->limit(70)->wrap(),
                TextColumn::make('category')->label('Kategorie')->badge()
                    ->formatStateUsing(fn (?string $state): string => Catalog::label(Catalog::NORM_CATEGORIES, $state)),
                ToggleColumn::make('is_published')->label('Online'),
            ])
            ->filters([
                SelectFilter::make('category')->label('Kategorie')->options(Catalog::NORM_CATEGORIES),
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
            'index' => ListNorms::route('/'),
            'create' => CreateNorm::route('/create'),
            'edit' => EditNorm::route('/{record}/edit'),
        ];
    }
}
