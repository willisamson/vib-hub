<?php

namespace App\Http\Middleware;

use App\Support\Site;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Baustellen-Modus (Admin → Website & Logo → Baustelle): Besucher sehen nur die Baustellenseite.
 * Angemeldete Admins, der Admin-Bereich, Impressum und Datenschutz bleiben erreichbar.
 */
class ConstructionMode
{
    /** Routen, die auch während der Baustelle öffentlich bleiben (Impressumspflicht). */
    protected const OPEN_ROUTES = ['imprint', 'privacy', 'en.imprint', 'en.privacy'];

    public function handle(Request $request, Closure $next): Response
    {
        $site = app(Site::class);

        if (! $site->constructionMode()
            || $request->is('admin', 'admin/*', 'livewire*', 'filament/*', 'up')
            || $request->routeIs(...self::OPEN_ROUTES)
            || auth('web')->check()) {
            return $next($request);
        }

        if ($request->is('en', 'en/*')) {
            app()->setLocale('en');
        }

        return response()
            ->view('construction', ['site' => $site], 503)
            ->header('Retry-After', '86400')
            ->header('X-Robots-Tag', 'noindex');
    }
}
