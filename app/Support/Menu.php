<?php

namespace App\Support;

use App\Models\MenuItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Throwable;

/**
 * Menüs der Website aus der Datenbank. Fehlt die Tabelle (Migration noch nicht
 * ausgeführt), werden die eingebauten Standardeinträge verwendet.
 */
class Menu
{
    /** @var array<string, Collection<int, MenuItem>> */
    protected array $cache = [];

    protected const FALLBACK = [
        'header' => ['services.index' => 'Leistungen', 'calculators' => 'Berechnungen', 'planner' => 'Auslegung', 'simulator' => 'Simulator', 'app' => 'App', 'norms.index' => 'Normen', 'trainings.index' => 'Schulungen'],
        'footer' => ['services.index' => 'Alle Leistungen', 'calculators' => 'Berechnungen', 'simulator' => 'Regler-Simulator', 'planner' => 'Shaker-Auslegung', 'norms.index' => 'Normen', 'trainings.index' => 'Schulungen'],
        'legal' => ['imprint' => 'Impressum', 'privacy' => 'Datenschutz'],
    ];

    /**
     * Aktive, erreichbare Einträge eines Bereichs.
     *
     * @return Collection<int, MenuItem>
     */
    public function items(string $location): Collection
    {
        return $this->cache[$location] ??= $this->load($location)
            ->filter(fn (MenuItem $item) => $item->href() !== null)
            ->values();
    }

    /** @return Collection<int, MenuItem> */
    protected function load(string $location): Collection
    {
        try {
            if (Schema::hasTable('menu_items')) {
                return MenuItem::query()->with('page')->active()->ordered()->where('location', $location)->get();
            }
        } catch (Throwable) {
            // Datenbank nicht erreichbar → Standardmenü
        }

        return collect(self::FALLBACK[$location] ?? [])->map(fn (string $label, string $route) => new MenuItem([
            'location' => $location, 'label' => $label, 'type' => 'route', 'route' => $route,
            'highlight' => $route === 'app' && $location === 'header',
        ]))->values();
    }
}
