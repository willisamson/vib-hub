<?php

namespace App\Filament\Widgets\Visitors;

use App\Support\VisitorStats;

class VisitorBrowsers extends RankingWidget
{
    public function heading(): string
    {
        return 'Browser & System';
    }

    public function groups(VisitorStats $stats): array
    {
        $rows = fn (string $column) => $stats->top($column, 8)->map(fn ($r) => ['label' => $r->label, 'value' => (int) $r->value])->all();

        return [
            ['title' => 'Browser', 'rows' => $rows('browser')],
            ['title' => 'Betriebssystem', 'rows' => $rows('os')],
        ];
    }
}
