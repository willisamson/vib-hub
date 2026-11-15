<?php

namespace App\Filament\Resources\Trainings;

use App\Filament\Resources\Trainings\Pages\CreateTraining;
use App\Filament\Resources\Trainings\Pages\EditTraining;
use App\Filament\Resources\Trainings\Pages\ListTrainings;
use App\Models\Training;
use App\Support\Catalog;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
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

class TrainingResource extends Resource
{
    protected static ?string $model = Training::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static string|UnitEnum|null $navigationGroup = 'Inhalte';

    protected static ?int $navigationSort = 20;

    protected static ?string $modelLabel = 'Schulung';

    protected static ?string $pluralModelLabel = 'Schulungen';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Schulung')
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
                        ->required()
                        ->alphaDash()
                        ->unique(ignoreRecord: true),
                    Select::make('level')->label('Niveau')->options(Catalog::TRAINING_LEVELS)->required()->default('einsteiger'),
                    Select::make('format')->label('Format')->options(Catalog::TRAINING_FORMATS)->required()->default('praesenz'),
                    TextInput::make('duration')->label('Dauer')->placeholder('z. B. 2 Tage'),
                    TextInput::make('price')->label('Preis (netto)')->numeric()->suffix('€')->helperText('Leer lassen = „auf Anfrage“'),
                    Textarea::make('excerpt')->label('Kurzbeschreibung')->rows(3)->columnSpanFull(),
                ]),
            Section::make('Termine')
                ->description('Vergangene Termine werden auf der Website automatisch ausgeblendet.')
                ->columnSpanFull()
                ->schema([
                    Repeater::make('dates')
                        ->hiddenLabel()
                        ->schema([
                            DatePicker::make('start')->label('Beginn')->required()->native(false)->displayFormat('d.m.Y'),
                            DatePicker::make('end')->label('Ende')->native(false)->displayFormat('d.m.Y')->afterOrEqual('start'),
                            TextInput::make('location')->label('Ort')->placeholder('z. B. Präsenz, Online live'),
                            TextInput::make('seats')->label('Plätze')->numeric()->minValue(0),
                        ])
                        ->columns(4)
                        ->addActionLabel('Termin hinzufügen')
                        ->defaultItems(0)
                        ->reorderable(false),
                ]),
            Section::make('Inhalt')
                ->columnSpanFull()
                ->schema([
                    Repeater::make('topics')
                        ->label('Themen / Agenda')
                        ->simple(TextInput::make('topic')->required()->maxLength(200))
                        ->addActionLabel('Thema hinzufügen')
                        ->defaultItems(0),
                    RichEditor::make('body')->label('Beschreibung'),
                ]),
            Section::make('Veröffentlichung')
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    Toggle::make('is_published')->label('Veröffentlicht')->default(true),
                    TextInput::make('sort')->label('Reihenfolge')->numeric()->default(0),
                ]),
            Section::make('English (/en)')
                ->description('Leere Felder zeigen auf der englischen Website den deutschen Text.')
                ->columnSpanFull()
                ->collapsible()
                ->schema([
                    TextInput::make('en.title')->label('Title'),
                    TextInput::make('en.duration')->label('Duration')->placeholder('e.g. 2 days'),
                    Textarea::make('en.excerpt')->label('Short description')->rows(3),
                    Repeater::make('en.topics')
                        ->label('Topics / agenda')
                        ->simple(TextInput::make('topic')->maxLength(200))
                        ->addActionLabel('Add topic')
                        ->defaultItems(0),
                    RichEditor::make('en.body')->label('Description'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort')
            ->reorderable('sort')
            ->columns([
                TextColumn::make('title')->label('Titel')->searchable()->sortable()->weight(FontWeight::Bold),
                TextColumn::make('level')->label('Niveau')->badge()
                    ->formatStateUsing(fn (?string $state): string => Catalog::label(Catalog::TRAINING_LEVELS, $state)),
                TextColumn::make('format')->label('Format')
                    ->formatStateUsing(fn (?string $state): string => Catalog::label(Catalog::TRAINING_FORMATS, $state)),
                TextColumn::make('next_date')->label('Nächster Termin')
                    ->state(function (Training $record): ?string {
                        $next = $record->upcomingDates()->first();

                        return $next ? $next['start']->format('d.m.Y') : null;
                    })
                    ->placeholder('—'),
                TextColumn::make('price')->label('Preis')->money('EUR', locale: 'de')->placeholder('auf Anfrage'),
                ToggleColumn::make('is_published')->label('Online'),
            ])
            ->filters([
                SelectFilter::make('format')->label('Format')->options(Catalog::TRAINING_FORMATS),
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
            'index' => ListTrainings::route('/'),
            'create' => CreateTraining::route('/create'),
            'edit' => EditTraining::route('/{record}/edit'),
        ];
    }
}
