<?php

use Illuminate\Support\Facades\Route;

if (! function_exists('lroute')) {
    /** URL zu einer Route in der aktuellen Sprache (Englisch: /en/...). */
    function lroute(string $name, mixed $parameters = [], ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return route($locale === 'en' ? 'en.'.$name : $name, $parameters);
    }
}

if (! function_exists('switch_locale_url')) {
    /** Dieselbe Seite in der anderen Sprache. */
    function switch_locale_url(string $locale): string
    {
        $route = Route::current();
        $name = $route?->getName();

        if (! $name) {
            return $locale === 'en' ? url('/en') : url('/');
        }

        $base = str_starts_with($name, 'en.') ? substr($name, 3) : $name;
        $target = $locale === 'en' ? 'en.'.$base : $base;

        if (! Route::has($target)) {
            return $locale === 'en' ? url('/en') : url('/');
        }

        $url = route($target, $route->parameters());
        $query = request()->getQueryString();

        return $query ? $url.'?'.$query : $url;
    }
}

if (! function_exists('dec')) {
    /** Dezimalzahl als Text in der Schreibweise der aktuellen Sprache ("0,75" / "0.75"). */
    function dec(string|int|float $value): string
    {
        $value = str_replace(',', '.', (string) $value);

        return app()->getLocale() === 'en' ? $value : str_replace('.', ',', $value);
    }
}
