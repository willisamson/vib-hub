<?php

namespace App\Support;

use App\Models\Service;
use App\Models\Setting;
use App\Models\Training;
use Illuminate\Support\Facades\Schema;

/**
 * Spielt inhaltliche Änderungen aus database/data/*.php in die Datenbank ein
 * (Einstellungen, Leistungen, Schulungen). Einstellungen werden nur ersetzt,
 * solange noch der alte Standardtext gespeichert ist.
 */
class ContentPatch
{
    public static function apply(string $name): void
    {
        $data = require database_path("data/{$name}.php");

        if (Schema::hasTable('settings')) {
            static::settings($data['settings'] ?? []);
        }
        if (Schema::hasTable('services')) {
            static::records(Service::class, $data['services'] ?? []);
        }
        if (Schema::hasTable('trainings')) {
            static::records(Training::class, $data['trainings'] ?? []);
        }
    }

    protected static function settings(array $settings): void
    {
        $stored = Setting::query()->pluck('value', 'key')->map(fn ($v) => $v === null ? null : json_decode($v, true));
        $changes = [];

        foreach ($settings as $key => [$old, $new]) {
            if (! $stored->has($key)) {
                continue; // nie gespeichert → der neue Standard aus Site.php gilt automatisch
            }
            $current = $stored[$key];
            if ($current === null || $current === '' || $current === [] || static::same($current, $old)) {
                $changes[$key] = $new;
            }
        }

        if ($changes) {
            Setting::setMany($changes);
        }
    }

    protected static function same(mixed $current, mixed $old): bool
    {
        if (is_array($current) && is_array($old)) {
            $strip = fn (array $rows) => array_map(fn ($r) => [
                'value' => (string) ($r['value'] ?? ''), 'suffix' => (string) ($r['suffix'] ?? ''), 'label' => (string) ($r['label'] ?? ''),
            ], array_values($rows));

            return $strip($current) === $strip($old);
        }

        return is_string($current) && trim($current) === trim((string) $old);
    }

    /**
     * @param  class-string<\Illuminate\Database\Eloquent\Model>  $model
     */
    protected static function records(string $model, array $records): void
    {
        foreach ($records as $slug => $fields) {
            $record = $model::query()->where('slug', $slug)->first();
            $create = $fields['create'] ?? null;
            unset($fields['create']);

            if (! $record) {
                if (! $create) {
                    continue; // im Admin gelöscht oder umbenannt
                }
                $record = new $model(['slug' => $slug, 'is_published' => true] + $create);
            }

            $en = $fields['en'] ?? [];
            unset($fields['en']);
            $record->fill($fields);
            $record->en = array_merge((array) ($record->en ?? []), $en);
            $record->save();
        }
    }
}
