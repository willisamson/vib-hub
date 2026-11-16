<?php

namespace App\Console\Commands;

use App\Support\GeoIp;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Throwable;

class UpdateGeoIp extends Command
{
    protected $signature = 'vibhub:geoip
        {--file= : Vorhandene CSV-Datei (auch .csv.gz) einlesen statt herunterladen}
        {--if-old : Nur aktualisieren, wenn die Daten fehlen oder älter als 30 Tage sind}';

    protected $description = 'Länderdatenbank für die Besucherstatistik laden (DB-IP Lite, CC BY 4.0)';

    public function handle(): int
    {
        $stamp = storage_path('app/geoip-updated.txt');

        if ($this->option('if-old') && GeoIp::hasData() && File::exists($stamp) && File::lastModified($stamp) > now()->subDays(30)->getTimestamp()) {
            $this->line('Länderdaten sind aktuell.');

            return self::SUCCESS;
        }

        $file = $this->option('file');
        $tmp = null;

        if (! $file) {
            $tmp = storage_path('app/geoip-download.csv.gz');
            foreach ([now(), now()->subMonthNoOverflow()] as $month) {
                $url = sprintf(GeoIp::SOURCE, $month->format('Y-m'));
                $this->line("Lade {$url} …");
                try {
                    $response = Http::timeout(180)->withOptions(['sink' => $tmp])->get($url);
                    if ($response->successful() && filesize($tmp) > 100000) {
                        $file = $tmp;
                        break;
                    }
                } catch (Throwable $e) {
                    $this->warn($e->getMessage());
                }
            }
            if (! $file) {
                $this->error('Download fehlgeschlagen. Alternativ die Datei von https://db-ip.com/db/download/ip-to-country-lite laden und mit --file= einlesen.');
                @unlink($tmp);

                return self::FAILURE;
            }
        }

        $this->line('Lese Daten ein …');
        $count = GeoIp::import($file);
        if ($tmp) {
            @unlink($tmp);
        }
        File::put($stamp, now()->toIso8601String());

        $this->info("Fertig: {$count} Adressbereiche geladen.");

        return self::SUCCESS;
    }
}
