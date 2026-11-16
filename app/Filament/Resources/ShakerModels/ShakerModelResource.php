<?php

namespace App\Filament\Resources\ShakerModels;

use App\Filament\Resources\ShakerModels\Pages\CreateShakerModel;
use App\Filament\Resources\ShakerModels\Pages\EditShakerModel;
use App\Filament\Resources\ShakerModels\Pages\ListShakerModels;
use App\Models\ShakerModel;
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
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

class ShakerModelResource extends Resource
{
    protected static ?string $model = ShakerModel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCpuChip;

    protected static string|UnitEnum|null $navigationGroup = 'Inhalte';

    protected static ?int $navigationSort = 40;

    protected static ?string $navigationLabel = 'Shaker-Modelle';

    protected static ?string $modelLabel = 'Shaker-Modell';

    protected static ?string $pluralModelLabel = 'Shaker-Modelle';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        $num = fn (string $name, string $label, string $suffix) => TextInput::make($name)->label($label)->numeric()->suffix($suffix);

        return $schema->components([
            Section::make('Modell')
                ->description('Grundlage für die Shaker-Auslegung auf der Website. Werte aus dem Herstellerdatenblatt übernehmen.')
                ->columnSpanFull()
                ->columns(3)
                ->schema([
                    TextInput::make('manufacturer')->label('Hersteller')->default('RMS')->required(),
                    TextInput::make('name')->label('Typ')->placeholder('z. B. SW8500 (45 kN)')->required(),
                    Select::make('cooling')->label('Kühlung')->options(['luft' => 'Luft', 'wasser' => 'Wasser']),
                    Toggle::make('is_active')->label('In der Auslegung verwenden')->default(true)->columnSpanFull(),
                ]),
            Section::make('Kräfte')
                ->columnSpanFull()
                ->columns(3)
                ->schema([
                    $num('sine_force_kn', 'Sinus (Spitze)', 'kN')->required(),
                    $num('random_force_kn', 'Random (effektiv)', 'kN')->required(),
                    $num('shock_force_kn', 'Schock Halbsinus (Spitze)', 'kN')->required(),
                ]),
            Section::make('Weg, Geschwindigkeit, Masse')
                ->columnSpanFull()
                ->columns(3)
                ->schema([
                    $num('displacement_mm', 'Weg Dauerbetrieb (Spitze-Spitze)', 'mm')->required(),
                    $num('shock_displacement_mm', 'Weg Schock (Spitze-Spitze)', 'mm'),
                    $num('velocity_ms', 'Geschwindigkeit Sinus (Spitze)', 'm/s')->required(),
                    $num('shock_velocity_ms', 'Geschwindigkeit Schock (Spitze)', 'm/s'),
                    $num('moving_mass_kg', 'Bewegte Masse (Anker)', 'kg')->required(),
                    $num('max_load_kg', 'Max. Last vertikal', 'kg'),
                    $num('f_min', 'Frequenz von', 'Hz')->integer()->default(5),
                    $num('f_max', 'Frequenz bis', 'Hz')->integer()->default(2500),
                    $num('table_mm', 'Ankertisch Ø', 'mm')->integer(),
                    $num('sort', 'Reihenfolge', '')->integer()->default(0),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sine_force_kn')
            ->columns([
                TextColumn::make('name')->label('Typ')->weight(FontWeight::Bold)->searchable()
                    ->description(fn (ShakerModel $record): string => $record->manufacturer),
                TextColumn::make('sine_force_kn')->label('Sinus')->suffix(' kN')->sortable(),
                TextColumn::make('random_force_kn')->label('Random')->suffix(' kN'),
                TextColumn::make('shock_force_kn')->label('Schock')->suffix(' kN'),
                TextColumn::make('displacement_mm')->label('Weg')->suffix(' mm'),
                TextColumn::make('moving_mass_kg')->label('Anker')->suffix(' kg'),
                ToggleColumn::make('is_active')->label('Aktiv'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListShakerModels::route('/'),
            'create' => CreateShakerModel::route('/create'),
            'edit' => EditShakerModel::route('/{record}/edit'),
        ];
    }
}
