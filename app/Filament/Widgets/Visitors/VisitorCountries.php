<?php

namespace App\Filament\Widgets\Visitors;

use App\Support\GeoIp;
use App\Support\VisitorStats;

class VisitorCountries extends RankingWidget
{
    public function heading(): string
    {
        return 'Länder';
    }

    public function groups(VisitorStats $stats): array
    {
        $empty = GeoIp::hasData()
            ? 'Keine Daten im Zeitraum.'
            : 'Die Länderdatenbank wird nachts automatisch geladen (oder sofort: php artisan vibhub:geoip).';

        return [[
            'title' => 'Herkunftsland',
            'rows' => $stats->top('country', 12)->map(fn ($r) => [
                'label' => trim(GeoIp::flag($r->label).' '.GeoIp::name($r->label)),
                'value' => (int) $r->value,
            ])->all(),
            'empty' => $empty,
        ]];
    }

    public function note(): ?string
    {
        return 'Land aus der IP-Adresse ermittelt, die IP selbst wird nicht gespeichert. '
            .'<a href="https://db-ip.com" target="_blank" rel="noopener" style="text-decoration:underline">IP Geolocation by DB-IP</a> (CC BY 4.0).';
    }
}
