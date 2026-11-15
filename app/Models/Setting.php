<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Einfacher Schlüssel/Wert-Speicher für Website-Einstellungen.
 * Werte werden als JSON gespeichert (Text, Zahlen und Listen).
 */
class Setting extends Model
{
    public const CACHE_KEY = 'vibhub.settings';

    protected $fillable = ['key', 'value'];

    /**
     * @return array<string, mixed>
     */
    public static function allValues(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function (): array {
            return static::query()->pluck('value', 'key')
                ->map(fn ($v) => $v === null ? null : json_decode($v, true))
                ->all();
        });
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $value = static::allValues()[$key] ?? null;

        return ($value === null || $value === '') ? $default : $value;
    }

    /**
     * @param  array<string, mixed>  $values
     */
    public static function setMany(array $values): void
    {
        foreach ($values as $key => $value) {
            static::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value === null ? null : json_encode($value, JSON_UNESCAPED_UNICODE)],
            );
        }

        Cache::forget(self::CACHE_KEY);
    }
}
