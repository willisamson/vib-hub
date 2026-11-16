<?php

namespace App\Support;

use App\Models\Norm;
use Illuminate\Support\Facades\Schema;

/**
 * Spielt die Normen-Bibliothek (database/data/norm-library.php) ein:
 * legt fehlende Normen an und ergänzt Anforderungen und Eckdaten.
 * Im Admin bereits gepflegte Inhalte bleiben erhalten, außer bei $force.
 */
class NormLibrary
{
    public static function data(): array
    {
        return require database_path('data/norm-library.php');
    }

    /**
     * @return array{created: int, updated: int}
     */
    public static function sync(bool $force = false): array
    {
        if (! Schema::hasTable('norms') || ! Schema::hasColumn('norms', 'requirements')) {
            return ['created' => 0, 'updated' => 0];
        }

        $data = static::data();
        $labels = $data['labels'] ?? [];
        $created = $updated = 0;

        foreach ($data['norms'] as $slug => $entry) {
            $norm = Norm::query()->where('slug', $slug)->first();

            if (! $norm) {
                if (empty($entry['new'])) {
                    continue; // Norm wurde im Admin gelöscht – nicht wieder anlegen.
                }
                $new = $entry['new'];
                $norm = new Norm([
                    'slug' => $slug,
                    'code' => $new['code'],
                    'title' => $new['title'],
                    'category' => $new['category'],
                    'excerpt' => $new['excerpt'] ?? null,
                    'sort' => $new['sort'] ?? 999,
                    'is_published' => true,
                    'en' => array_filter($new['en'] ?? []),
                ]);
                $created++;
            }

            $changed = ! $norm->exists;
            $en = (array) ($norm->en ?? []);

            if (! empty($entry['params']) && ($force || empty($norm->parameters))) {
                $norm->parameters = array_map(fn ($p) => ['label' => $p[0], 'value' => $p[1]], $entry['params']);
                $en['parameters'] = array_map(fn ($p) => ['label' => $p[2] ?? $p[0], 'value' => $p[3] ?? $p[1]], $entry['params']);
                $changed = true;
            }

            if (! empty($entry['req']) && ($force || empty($norm->requirements))) {
                $norm->requirements = array_map(fn ($r) => static::row($r, $labels), $entry['req']);
                $changed = true;
            }

            if ($changed) {
                $norm->en = $en ?: null;
                $wasNew = ! $norm->exists;
                $norm->save();
                $wasNew || $updated++;
            }
        }

        return ['created' => $created, 'updated' => $updated];
    }

    protected static function row(array $r, array $labels): array
    {
        [$group, $label, $value] = $r;

        return [
            'group' => $group ?: null,
            'label' => $label,
            'value' => $value,
            'group_en' => $group ? ($labels[$group] ?? null) : null,
            'label_en' => $labels[$label] ?? null,
            'value_en' => $r[3] ?? null,
        ];
    }
}
