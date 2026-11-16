<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Länderdaten für die Besucherstatistik: täglich prüfen, höchstens alle 30 Tage neu laden
Illuminate\Support\Facades\Schedule::command('vibhub:geoip --if-old')->dailyAt('04:10')->withoutOverlapping();
