<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Support\Catalog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class Training extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title', 'slug', 'level', 'format', 'duration', 'price', 'excerpt',
        'body', 'topics', 'dates', 'is_published', 'sort', 'en',
    ];

    protected function casts(): array
    {
        return [
            'topics' => 'array',
            'dates' => 'array',
            'price' => 'decimal:2',
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
        return $query->orderBy('sort')->orderBy('title');
    }

    public function levelLabel(): string
    {
        return Catalog::label(Catalog::TRAINING_LEVELS, $this->level);
    }

    public function formatLabel(): string
    {
        return Catalog::label(Catalog::TRAINING_FORMATS, $this->format);
    }

    /**
     * Kommende Termine, aufsteigend sortiert.
     *
     * @return Collection<int, array{start: Carbon, end: ?Carbon, location: ?string, seats: ?int}>
     */
    public function upcomingDates(): Collection
    {
        $today = Carbon::today();

        return collect($this->dates ?? [])
            ->filter(fn ($d) => ! empty($d['start']))
            ->map(fn ($d) => [
                'start' => Carbon::parse($d['start']),
                'end' => ! empty($d['end']) ? Carbon::parse($d['end']) : null,
                'location' => $d['location'] ?? null,
                'seats' => isset($d['seats']) && $d['seats'] !== '' ? (int) $d['seats'] : null,
            ])
            ->filter(fn ($d) => ($d['end'] ?? $d['start'])->gte($today))
            ->sortBy(fn ($d) => $d['start']->timestamp)
            ->values();
    }

    public function priceLabel(): ?string
    {
        if ($this->price === null) {
            return null;
        }

        return number_format((float) $this->price, 0, ',', '.').' € zzgl. MwSt.';
    }
}
