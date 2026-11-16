<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Locale;
use RuntimeException;

/**
 * Land zu einer IP-Adresse nachschlagen – lokal aus der freien Datenbank „IP to Country Lite“
 * von DB-IP (https://db-ip.com, Lizenz CC BY 4.0). Es wird nichts an Dritte übertragen und
 * die IP-Adresse nicht gespeichert.
 */
class GeoIp
{
    public const SOURCE = 'https://download.db-ip.com/free/dbip-country-lite-%s.csv.gz';

    /** Fester 32-stelliger Hex-Schlüssel; IPv4 als IPv4-mapped IPv6, damit alle Adressen vergleichbar sind. */
    public static function key(string $ip): ?string
    {
        $bin = @inet_pton(trim($ip));
        if ($bin === false) {
            return null;
        }
        if (strlen($bin) === 4) {
            $bin = str_repeat("\0", 10)."\xff\xff".$bin;
        }

        return bin2hex($bin);
    }

    public static function country(?string $ip): ?string
    {
        if (! $ip || ! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return null;
        }
        $key = static::key($ip);
        if ($key === null) {
            return null;
        }

        $row = DB::table('geoip_ranges')->where('ip_start', '<=', $key)->orderByDesc('ip_start')->first(['ip_end', 'country']);

        return ($row && strcmp($row->ip_end, $key) >= 0) ? $row->country : null;
    }

    public static function hasData(): bool
    {
        return Schema::hasTable('geoip_ranges') && DB::table('geoip_ranges')->exists();
    }

    /** CSV (auch .gz) im Format „start,ende,land“ einlesen und die Tabelle ersetzen. */
    public static function import(string $file): int
    {
        $handle = str_ends_with($file, '.gz') ? gzopen($file, 'rb') : fopen($file, 'rb');
        if (! $handle) {
            throw new RuntimeException("Datei nicht lesbar: {$file}");
        }

        $count = 0;
        $batch = [];
        DB::table('geoip_ranges')->truncate();

        $flush = function () use (&$batch, &$count): void {
            if ($batch) {
                DB::table('geoip_ranges')->insert($batch);
                $count += count($batch);
                $batch = [];
            }
        };

        while (($row = fgetcsv($handle, 200, ',', '"', '')) !== false) {
            if (count($row) < 3 || strlen($row[2]) !== 2 || $row[2] === 'ZZ') {
                continue;
            }
            $start = static::key($row[0]);
            $end = static::key($row[1]);
            if ($start === null || $end === null) {
                continue;
            }
            $batch[] = ['ip_start' => $start, 'ip_end' => $end, 'country' => strtoupper($row[2])];
            if (count($batch) >= 2000) {
                $flush();
            }
        }
        $flush();
        str_ends_with($file, '.gz') ? gzclose($handle) : fclose($handle);

        return $count;
    }

    public static function name(string $code, string $locale = 'de'): string
    {
        $name = class_exists(Locale::class) ? Locale::getDisplayRegion('-'.$code, $locale) : '';

        return $name && $name !== $code ? $name : $code;
    }

    public static function flag(string $code): string
    {
        if (! preg_match('/^[A-Z]{2}$/', $code)) {
            return '';
        }

        return mb_chr(0x1F1E6 + ord($code[0]) - 65).mb_chr(0x1F1E6 + ord($code[1]) - 65);
    }
}
