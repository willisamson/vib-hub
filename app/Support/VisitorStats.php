<?php

namespace App\Support;

use App\Models\PageView;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/** Auswertungen der anonymen Besucherstatistik für den Admin. */
class VisitorStats
{
    public const PERIODS = [
        '7' => 'Letzte 7 Tage',
        '30' => 'Letzte 30 Tage',
        '90' => 'Letzte 90 Tage',
        '365' => 'Letzte 12 Monate',
    ];

    public CarbonImmutable $from;

    public CarbonImmutable $to;

    public function __construct(public int $days = 30)
    {
        $this->days = max(1, min(400, $days));
        $this->to = CarbonImmutable::today();
        $this->from = $this->to->subDays($this->days - 1);
    }

    public static function fromFilters(?array $filters): self
    {
        return new self((int) ($filters['period'] ?? 30) ?: 30);
    }

    public function previous(): self
    {
        $prev = new self($this->days);
        $prev->to = $this->from->subDay();
        $prev->from = $prev->to->subDays($this->days - 1);

        return $prev;
    }

    public function query(): Builder
    {
        return PageView::query()->whereBetween('viewed_on', [$this->from->toDateString(), $this->to->toDateString()]);
    }

    /** Besucher = je Tag eindeutige Besucher, über den Zeitraum summiert. */
    public function visitors(): int
    {
        return (int) DB::query()->fromSub(
            $this->query()->select('viewed_on', 'visitor')->distinct(),
            'v',
        )->count();
    }

    public function views(): int
    {
        return $this->query()->count();
    }

    /** @return array{labels: array<int, string>, visitors: array<int, int>, views: array<int, int>} */
    public function daily(): array
    {
        $rows = $this->query()
            ->selectRaw('viewed_on as day, count(*) as views, count(distinct visitor) as visitors')
            ->groupBy('viewed_on')
            ->get()
            ->keyBy(fn ($r) => substr((string) $r->day, 0, 10));

        $out = ['labels' => [], 'visitors' => [], 'views' => []];
        for ($d = $this->from; $d->lte($this->to); $d = $d->addDay()) {
            $key = $d->toDateString();
            $out['labels'][] = $this->days > 90 ? $d->format('d.m.y') : $d->format('d.m.');
            $out['visitors'][] = (int) ($rows[$key]->visitors ?? 0);
            $out['views'][] = (int) ($rows[$key]->views ?? 0);
        }

        return $out;
    }

    /** @return Collection<int, object{label: string, value: int}> */
    public function top(string $column, int $limit = 10): Collection
    {
        return $this->query()
            ->selectRaw("{$column} as label, count(*) as value")
            ->whereNotNull($column)
            ->groupBy($column)
            ->orderByDesc('value')
            ->limit($limit)
            ->get();
    }

    /** Anteil der Aufrufe ohne verweisende Website (direkt, Lesezeichen, App). */
    public function directViews(): int
    {
        return $this->query()->whereNull('referrer')->count();
    }
}
