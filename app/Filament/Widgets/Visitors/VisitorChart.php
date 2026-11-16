<?php

namespace App\Filament\Widgets\Visitors;

use App\Support\VisitorStats;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class VisitorChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static bool $isDiscovered = false;

    protected int|string|array $columnSpan = 'full';

    protected ?string $heading = 'Verlauf';

    protected ?string $maxHeight = '300px';

    protected ?string $pollingInterval = null;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $daily = VisitorStats::fromFilters($this->pageFilters)->daily();

        return [
            'labels' => $daily['labels'],
            'datasets' => [
                [
                    'label' => 'Besucher',
                    'data' => $daily['visitors'],
                    'borderColor' => '#0b7fa0',
                    'backgroundColor' => 'rgba(11, 127, 160, .12)',
                    'fill' => true,
                    'tension' => .3,
                    'pointRadius' => count($daily['labels']) > 60 ? 0 : 2,
                ],
                [
                    'label' => 'Seitenaufrufe',
                    'data' => $daily['views'],
                    'borderColor' => '#6d3fe0',
                    'backgroundColor' => 'transparent',
                    'borderDash' => [5, 4],
                    'tension' => .3,
                    'pointRadius' => 0,
                ],
            ],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => ['legend' => ['display' => true, 'position' => 'bottom']],
            'scales' => ['y' => ['beginAtZero' => true, 'ticks' => ['precision' => 0]]],
            'interaction' => ['intersect' => false, 'mode' => 'index'],
        ];
    }
}
