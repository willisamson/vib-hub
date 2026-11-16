<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Support\Catalog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Norm extends Model
{
    use HasTranslations;

    protected $fillable = [
        'code', 'slug', 'title', 'category', 'excerpt', 'body', 'parameters',
        'requirements', 'is_published', 'sort', 'en',
    ];

    protected function casts(): array
    {
        return [
            'parameters' => 'array',
            'requirements' => 'array',
            'is_published' => 'boolean',
            'sort' => 'integer',
            'en' => 'array',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderBy('code');
    }

    /**
     * Anforderungen in der aktuellen Sprache, nach Gruppen zusammengefasst.
     *
     * @return array<int, array{group: ?string, rows: array<int, array{label: string, value: string}>}>
     */
    public function requirementGroups(): array
    {
        $en = app()->getLocale() === 'en';
        $groups = [];
        $rows = array_values(array_filter((array) $this->requirements, 'is_array'));

        // Allgemeine Zeilen (ohne Gruppe) stehen oben, die Gruppen folgen in ihrer Reihenfolge.
        usort($rows, fn ($a, $b) => (int) filled($a['group'] ?? null) <=> (int) filled($b['group'] ?? null));

        foreach ($rows as $row) {
            if (blank($row['label'] ?? null) && blank($row['value'] ?? null)) {
                continue;
            }
            $group = $en ? (($row['group_en'] ?? null) ?: ($row['group'] ?? null)) : ($row['group'] ?? null);
            $label = $en ? (($row['label_en'] ?? null) ?: (string) ($row['label'] ?? '')) : (string) ($row['label'] ?? '');
            $value = $en
                ? (($row['value_en'] ?? null) ?: preg_replace('/(\d),(\d)/', '$1.$2', (string) ($row['value'] ?? '')))
                : (string) ($row['value'] ?? '');

            $last = array_key_last($groups);
            if ($last === null || $groups[$last]['group'] !== ($group ?: null)) {
                $groups[] = ['group' => $group ?: null, 'rows' => []];
                $last = array_key_last($groups);
            }
            // Zahl und Einheit nicht trennen (geschütztes Leerzeichen)
            $value = preg_replace('/(\d) (?=(?:m\/s²|mm\/s|g²\/Hz|grms|gn|g|Hz|kHz|ms|min|mm|inch|kg|kW|dB|h|s)\b|(?:m\/s²|g²\/Hz))/u', "$1\u{00A0}", $value);
            $groups[$last]['rows'][] = ['label' => $label, 'value' => $value];
        }

        return $groups;
    }

    public function categoryLabel(): string
    {
        return Catalog::label(Catalog::NORM_CATEGORIES, $this->category);
    }
}
