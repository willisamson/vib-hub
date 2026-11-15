<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Resources\Pages\Pages\CreatePage;
use App\Filament\Resources\Pages\Pages\EditPage;
use App\Filament\Resources\Pages\Pages\ListPages;
use App\Models\Page;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
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
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = 'Website';

    protected static ?int $navigationSort = 10;

    protected static ?string $modelLabel = 'Seite';

    protected static ?string $pluralModelLabel = 'Eigene Seiten';

    protected static ?string $recordTitleAttribute = 'title';

    protected const TOOLBAR = [
        ['bold', 'italic', 'link'],
        ['h2', 'h3'],
        ['bulletList', 'orderedList', 'blockquote'],
        ['undo', 'redo'],
    ];

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Seite')
                ->description('Die Seite erscheint unter /seite/kuerzel. In ein Menü kommt sie über „Menüs“.')
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->label('Titel')
                        ->required()
                        ->maxLength(160)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Set $set, Get $get, ?string $state): void {
                            if (blank($get('slug'))) {
                                $set('slug', Str::slug((string) $state, '-', 'de'));
                            }
                        }),
                    TextInput::make('slug')->label('URL-Kürzel')->required()->alphaDash()->maxLength(120)->unique(ignoreRecord: true)
                        ->prefix('/seite/'),
                    Textarea::make('excerpt')->label('Einleitung')->rows(2)->columnSpanFull()
                        ->helperText('Erscheint unter der Überschrift und als Beschreibung für Suchmaschinen.'),
                    RichEditor::make('body')->label('Inhalt')->toolbarButtons(self::TOOLBAR)->columnSpanFull(),
                    Toggle::make('is_published')->label('Veröffentlicht')->default(true),
                ]),
            Section::make('English (/en)')
                ->description('Leere Felder zeigen auf der englischen Website den deutschen Text.')
                ->columnSpanFull()
                ->collapsible()
                ->schema([
                    TextInput::make('en.title')->label('Title'),
                    Textarea::make('en.excerpt')->label('Introduction')->rows(2),
                    RichEditor::make('en.body')->label('Content')->toolbarButtons(self::TOOLBAR),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('title')
            ->columns([
                TextColumn::make('title')->label('Titel')->searchable()->sortable()->weight(FontWeight::Bold),
                TextColumn::make('slug')->label('Adresse')->formatStateUsing(fn (string $state): string => '/seite/'.$state)->color('gray'),
                TextColumn::make('menu_items_count')->label('In Menüs')->counts('menuItems')->badge()->color('gray'),
                TextColumn::make('updated_at')->label('Geändert')->dateTime('d.m.Y H:i')->sortable(),
                ToggleColumn::make('is_published')->label('Online'),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('Ansehen')
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->url(fn (Page $record): string => route('page', $record))
                    ->openUrlInNewTab(),
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
            'index' => ListPages::route('/'),
            'create' => CreatePage::route('/create'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }
}
