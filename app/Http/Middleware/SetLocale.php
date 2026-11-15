<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

/** Setzt die Sprache der Website: Deutsch (Standard) oder Englisch unter /en. */
class SetLocale
{
    public function handle(Request $request, Closure $next, string $locale = 'de'): Response
    {
        $locale = in_array($locale, ['de', 'en'], true) ? $locale : 'de';

        app()->setLocale($locale);
        Carbon::setLocale($locale);

        return $next($request);
    }
}
