<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use App\Support\Site;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Zählt Seitenaufrufe für die Besucherstatistik im Admin – ohne Cookies und ohne die
 * IP-Adresse zu speichern. Eindeutige Besucher werden über einen Hash aus IP, Browser
 * und Datum erkannt, der sich täglich ändert und nicht zurückgerechnet werden kann.
 * Die Zählung läuft erst, nachdem die Antwort an den Browser gegangen ist.
 */
class TrackPageView
{
    private const BOTS = '/bot|crawl|spider|slurp|archiver|facebookexternalhit|embedly|preview|monitor|uptime|curl|wget|python|java\/|go-http|okhttp|axios|node-fetch|headless|lighthouse|pingdom|scrapy|httpclient|semrush|ahrefs|mj12|dotbot|petalbot|bytespider|gptbot|claudebot|ccbot/i';

    private const SKIP = ['admin', 'admin/*', 'livewire/*', 'livewire-*', 'filament/*', 'up', 'sitemap.xml', 'build/*', 'storage/*', 'favicon.ico', 'robots.txt'];

    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        try {
            if (! $this->shouldCount($request, $response)) {
                return;
            }

            $ua = (string) $request->userAgent();
            $day = now()->toDateString();

            PageView::query()->create([
                'viewed_on' => $day,
                'path' => mb_substr('/'.ltrim($request->path(), '/'), 0, 255),
                'route' => preg_replace('/^en\./', '', (string) $request->route()?->getName()) ?: null,
                'locale' => $request->is('en', 'en/*') ? 'en' : 'de',
                'referrer' => $this->referrer($request),
                'device' => $this->device($ua),
                'visitor' => substr(hash_hmac('sha256', $request->ip().'|'.$ua.'|'.$day, (string) config('app.key')), 0, 16),
            ]);

            // Alte Einträge gelegentlich aufräumen (älter als 400 Tage)
            if (random_int(1, 200) === 1) {
                PageView::query()->where('viewed_on', '<', now()->subDays(400)->toDateString())->delete();
            }
        } catch (Throwable $e) {
            Log::debug('Besucherstatistik: '.$e->getMessage());
        }
    }

    protected function shouldCount(Request $request, Response $response): bool
    {
        if (! $request->isMethod('GET') || $response->getStatusCode() !== 200) {
            return false;
        }
        if ($request->ajax() || $request->expectsJson() || $request->header('X-Livewire') || $request->is(self::SKIP)) {
            return false;
        }
        if (! str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
            return false;
        }

        $ua = (string) $request->userAgent();
        if ($ua === '' || preg_match(self::BOTS, $ua)) {
            return false;
        }

        // "Do Not Track" bzw. "Global Privacy Control" respektieren
        if ($request->header('DNT') === '1' || $request->header('Sec-GPC') === '1') {
            return false;
        }

        $site = app(Site::class);
        if (! $site->statsEnabled()) {
            return false;
        }

        // Eigene Besuche (angemeldet im Admin) nicht mitzählen
        if (Auth::guard('web')->check()) {
            return false;
        }

        if ($site->statsRequireConsent()) {
            $consent = json_decode((string) $request->cookie('vibhub_consent'), true);

            return is_array($consent) && ! empty($consent['stats']);
        }

        return true;
    }

    protected function referrer(Request $request): ?string
    {
        $host = parse_url((string) $request->headers->get('referer'), PHP_URL_HOST);
        if (! $host) {
            return null;
        }
        $host = preg_replace('/^www\./', '', strtolower($host));

        return $host === preg_replace('/^www\./', '', strtolower($request->getHost())) ? null : mb_substr($host, 0, 120);
    }

    protected function device(string $ua): string
    {
        if (preg_match('/ipad|tablet|kindle|silk|playbook|(android(?!.*mobile))/i', $ua)) {
            return 'tablet';
        }

        return preg_match('/mobi|iphone|ipod|android|blackberry|opera mini|iemobile/i', $ua) ? 'mobile' : 'desktop';
    }
}
