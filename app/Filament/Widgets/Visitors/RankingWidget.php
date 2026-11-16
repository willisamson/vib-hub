<?php

namespace App\Filament\Widgets\Visitors;

use App\Support\VisitorStats;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;

/** Rangliste mit Balken (Top-Seiten, Herkunft, Geräte …). */
abstract class RankingWidget extends Widget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;

    protected string $view = 'filament.widgets.ranking';

    abstract public function heading(): string;

    /**
     * @return array<int, array{title: string, rows: array<int, array{label: string, value: int, hint?: string}>, empty?: string}>
     */
    abstract public function groups(VisitorStats $stats): array;

    /** Optionaler Hinweis unter der Liste (HTML erlaubt, nur eigene Texte). */
    public function note(): ?string
    {
        return null;
    }

    protected function getViewData(): array
    {
        return [
            'heading' => $this->heading(),
            'groups' => $this->groups(VisitorStats::fromFilters($this->pageFilters)),
            'note' => $this->note(),
        ];
    }
}
