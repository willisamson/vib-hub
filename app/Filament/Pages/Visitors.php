<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\Visitors\VisitorBrowsers;
use App\Filament\Widgets\Visitors\VisitorChart;
use App\Filament\Widgets\Visitors\VisitorCountries;
use App\Filament\Widgets\Visitors\VisitorDevices;
use App\Filament\Widgets\Visitors\VisitorOverview;
use App\Filament\Widgets\Visitors\VisitorPages;
use App\Filament\Widgets\Visitors\VisitorSources;
use App\Support\Site;
use App\Support\VisitorStats;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/** Besucherstatistik der Website (anonym, ohne Cookies). */
class Visitors extends Dashboard
{
    use HasFiltersForm;

    protected static string $routePath = 'besucher';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static string|UnitEnum|null $navigationGroup = 'Website';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Besucher';

    protected static ?string $title = 'Besucherstatistik';

    public function getSubheading(): ?string
    {
        $site = app(Site::class);
        if (! $site->statsEnabled()) {
            return 'Die Zählung ist ausgeschaltet (Website & Logo → Cookies).';
        }

        return 'Anonym und ohne Cookies gezählt. Besucher werden je Tag einmal gezählt, eigene Besuche im angemeldeten Zustand nicht.'
            .($site->statsRequireConsent() ? ' Gezählt werden nur Besucher mit Einwilligung in „Statistik“.' : '');
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('period')
                ->label('Zeitraum')
                ->options(VisitorStats::PERIODS)
                ->default('30')
                ->selectablePlaceholder(false)
                ->native(false),
        ]);
    }

    public function getWidgets(): array
    {
        return [
            VisitorOverview::class,
            VisitorChart::class,
            VisitorPages::class,
            VisitorSources::class,
            VisitorCountries::class,
            VisitorBrowsers::class,
            VisitorDevices::class,
        ];
    }

    public function getColumns(): int|array
    {
        return ['md' => 2, 'xl' => 3];
    }
}
