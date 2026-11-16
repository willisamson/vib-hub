<?php

namespace App\Filament\Widgets\Visitors;

use App\Support\VisitorStats;

class VisitorDevices extends RankingWidget
{
    public function heading(): string
    {
        return 'Geräte & Sprache';
    }

    public function groups(VisitorStats $stats): array
    {
        $devices = ['desktop' => 'Computer', 'mobile' => 'Smartphone', 'tablet' => 'Tablet'];
        $locales = ['de' => 'Deutsch (/)', 'en' => 'Englisch (/en)'];

        return [
            ['title' => 'Gerät', 'rows' => $stats->top('device')->map(fn ($r) => ['label' => $devices[$r->label] ?? $r->label, 'value' => (int) $r->value])->all()],
            ['title' => 'Sprache der Website', 'rows' => $stats->top('locale')->map(fn ($r) => ['label' => $locales[$r->label] ?? $r->label, 'value' => (int) $r->value])->all()],
        ];
    }
}
