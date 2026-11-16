<?php

namespace App\Filament\Widgets\Visitors;

use App\Models\PageView;
use App\Support\VisitorStats;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VisitorOverview extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $now = VisitorStats::fromFilters($this->pageFilters);
        $prev = $now->previous();
        $daily = $now->daily();

        $visitors = $now->visitors();
        $views = $now->views();
        $prevVisitors = $prev->visitors();
        $today = PageView::query()->whereDate('viewed_on', today())->distinct()->count('visitor');

        $trend = function (int $current, int $before): array {
            if ($before === 0) {
                return [$current > 0 ? 'neu im Zeitraum' : 'noch keine Daten', null, 'gray'];
            }
            $pct = ($current - $before) / $before * 100;

            return [
                ($pct >= 0 ? '+' : '').number_format($pct, 0, ',', '.').' % zum Vorzeitraum',
                $pct >= 0 ? Heroicon::ArrowTrendingUp : Heroicon::ArrowTrendingDown,
                $pct >= 0 ? 'success' : 'danger',
            ];
        };
        [$vText, $vIcon, $vColor] = $trend($visitors, $prevVisitors);
        [$pText, $pIcon, $pColor] = $trend($views, $prev->views());

        return [
            Stat::make('Besucher', number_format($visitors, 0, ',', '.'))
                ->description($vText)->descriptionIcon($vIcon)->color($vColor)
                ->chart(array_slice($daily['visitors'], -30)),
            Stat::make('Seitenaufrufe', number_format($views, 0, ',', '.'))
                ->description($pText)->descriptionIcon($pIcon)->color($pColor)
                ->chart(array_slice($daily['views'], -30)),
            Stat::make('Seiten je Besuch', $visitors ? number_format($views / $visitors, 1, ',', '.') : '–')
                ->description('Durchschnitt im Zeitraum'),
            Stat::make('Besucher heute', number_format($today, 0, ',', '.'))
                ->description('seit 0 Uhr')
                ->descriptionIcon(Heroicon::OutlinedClock),
        ];
    }
}
