<?php

namespace App\Filament\Widgets\Visitors;

use App\Support\VisitorStats;

class VisitorSources extends RankingWidget
{
    public function heading(): string
    {
        return 'Herkunft';
    }

    public function groups(VisitorStats $stats): array
    {
        $rows = $stats->top('referrer', 9)->map(fn ($r) => ['label' => $r->label, 'value' => (int) $r->value])->all();
        $direct = $stats->directViews();
        if ($direct > 0) {
            $rows[] = ['label' => 'Direkt / Lesezeichen', 'value' => $direct, 'hint' => 'ohne verweisende Website'];
            usort($rows, fn ($a, $b) => $b['value'] <=> $a['value']);
        }

        return [['title' => 'Verweisende Website', 'rows' => $rows, 'empty' => 'Noch keine Aufrufe im Zeitraum.']];
    }
}
