<?php

namespace App\Filament\Widgets\Visitors;

use App\Support\Catalog;
use App\Support\VisitorStats;

class VisitorPages extends RankingWidget
{
    protected int|string|array $columnSpan = ['md' => 2, 'xl' => 1];

    public function heading(): string
    {
        return 'Meistbesuchte Seiten';
    }

    public function groups(VisitorStats $stats): array
    {
        $rows = $stats->query()
            ->selectRaw('path, max(route) as route, count(*) as value')
            ->groupBy('path')
            ->orderByDesc('value')
            ->limit(12)
            ->get()
            ->map(fn ($r) => [
                'label' => $r->path,
                'value' => (int) $r->value,
                'hint' => isset(Catalog::MENU_ROUTES[$r->route]) && $r->path !== '/' ? Catalog::MENU_ROUTES[$r->route] : ($r->path === '/' || $r->path === '/en' ? 'Startseite' : null),
            ])->all();

        return [['title' => 'Seite', 'rows' => $rows, 'empty' => 'Noch keine Aufrufe im Zeitraum.']];
    }
}
