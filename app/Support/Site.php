<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * Zugriff auf die Website-Einstellungen mit sinnvollen Standardwerten.
 * In allen Frontend-Views als $site verfügbar.
 */
class Site
{
    /** @var array<string, mixed>|null */
    protected ?array $values = null;

    public const DEFAULTS = [
        'company_name' => 'vib-hub',
        'tagline' => 'Schwingungsprüfung, Analyse und Resonanzbeseitigung',
        'meta_description' => 'vib-hub – Schwingungsprüfung (Sinus, Random, Schock) nach DIN EN 60068, Resonanzbeseitigung, Berechnungen, Service und Schulungen rund um die Schwingungstechnik.',
        'email' => 'info@vib-hub.com',
        'notify_email' => null,
        'phone' => null,
        'address' => null,
        'linkedin' => null,
        'hero_kicker' => 'Labor und Engineering für Schwingungstechnik',
        'hero_title' => 'Schwingungen verstehen. Resonanzen beherrschen.',
        'hero_text' => 'Sinus, Random und Schock nach DIN EN 60068 – dazu Messung, Berechnung und Resonanzbeseitigung aus einer Hand. Wir machen sichtbar, was Ihr Produkt zum Schwingen bringt, und sorgen dafür, dass es hält.',
        'stats' => [
            ['value' => '20', 'suffix' => '–2000 Hz', 'label' => 'Typischer Prüffrequenzbereich'],
            ['value' => '3', 'suffix' => ' Achsen', 'label' => 'Prüfung in X, Y und Z'],
            ['value' => '30', 'suffix' => '+ Normen', 'label' => 'DIN, EN, ISO, IEC, MIL, VDI'],
            ['value' => '24', 'suffix' => ' h', 'label' => 'Rückmeldung auf Anfragen'],
        ],
        'footer_text' => 'Ingenieurdienstleistungen für Schwingungsprüfung, Resonanzanalyse und dynamische Auslegung.',
        'imprint' => null,
        'privacy' => null,
        'app_name' => 'PSD Profil Rechner',
        'app_url' => 'https://play.google.com/store/apps/details?id=de.example.psdprofilecalculator&hl=de',
        'app_text' => 'Kräfte und Hubwege für Schwingprüfungen direkt aus dem PSD-Profil berechnen – auf dem Smartphone, offline, ohne Werbung und ohne Datensammlung.',
        'app_image' => null,
        'logo' => null,
        'logo_dark' => null,
        'favicon' => null,
    ];

    /** Texte, die im Admin zusätzlich auf Englisch (Schlüssel + "_en") gepflegt werden können. */
    public const TRANSLATABLE = [
        'tagline', 'meta_description', 'hero_kicker', 'hero_title', 'hero_text',
        'footer_text', 'app_text', 'imprint', 'privacy',
    ];

    public const DEFAULTS_EN = [
        'tagline' => 'Vibration testing, analysis and resonance elimination',
        'meta_description' => 'vib-hub – vibration testing (sine, random, shock) to IEC 60068, resonance elimination, calculations, field service and training in vibration engineering.',
        'hero_kicker' => 'Laboratory and engineering for vibration technology',
        'hero_title' => 'Understand vibration. Master resonance.',
        'hero_text' => 'Sine, random and shock to IEC 60068 – plus measurement, calculation and resonance elimination from a single source. We reveal what makes your product vibrate and make sure it holds.',
        'footer_text' => 'Engineering services for vibration testing, resonance analysis and dynamic design.',
        'app_text' => 'Calculate forces and strokes for vibration tests directly from the PSD profile – on your smartphone, offline, without ads and without data collection.',
    ];

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        if ($this->values !== null) {
            return $this->values;
        }

        $stored = [];

        try {
            if (Schema::hasTable('settings')) {
                $stored = Setting::allValues();
            }
        } catch (Throwable) {
            $stored = [];
        }

        $merged = self::DEFAULTS;
        foreach ($stored as $key => $value) {
            if ($value !== null && $value !== '' && $value !== []) {
                $merged[$key] = $value;
            }
        }

        return $this->values = $merged;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $all = $this->all();
        $value = $all[$key] ?? $default;

        if (app()->getLocale() === 'en' && in_array($key, self::TRANSLATABLE, true)) {
            $en = $all[$key.'_en'] ?? null;
            if (is_string($en) && trim($en) !== '') {
                return $en;
            }
            // Unveränderter deutscher Standardtext → englischer Standardtext
            if (isset(self::DEFAULTS_EN[$key]) && $value === (self::DEFAULTS[$key] ?? null)) {
                return self::DEFAULTS_EN[$key];
            }
        }

        return $value;
    }

    public function name(): string
    {
        return (string) $this->get('company_name', 'vib-hub');
    }

    /** Hauptlogo für helle Hintergründe (Website, Admin hell). */
    public function logoUrl(): string
    {
        return $this->fileUrl('logo') ?? asset('images/vib-hub-logo-dark.svg');
    }

    /** Logo-Variante für dunkle Hintergründe (Admin im Dunkelmodus). */
    public function logoOnDarkUrl(): string
    {
        return $this->fileUrl('logo_dark') ?? $this->fileUrl('logo') ?? asset('images/vib-hub-logo.svg');
    }

    public function appUrl(): ?string
    {
        $url = $this->get('app_url');

        return is_string($url) && $url !== '' ? $url : null;
    }

    public function appImageUrl(): ?string
    {
        return $this->fileUrl('app_image');
    }

    public function hasCustomLogo(): bool
    {
        return $this->fileUrl('logo') !== null;
    }

    public function faviconUrl(): string
    {
        return $this->fileUrl('favicon') ?? asset('images/favicon.svg');
    }

    protected function fileUrl(string $key): ?string
    {
        $path = $this->get($key);

        if (! is_string($path) || $path === '') {
            return null;
        }

        return Storage::disk('public')->url($path);
    }

    /**
     * @return array<int, array{value: string, suffix: string, label: string}>
     */
    public function stats(): array
    {
        $stats = $this->get('stats');
        $stats = is_array($stats) && count($stats) ? array_values($stats) : self::DEFAULTS['stats'];

        if (app()->getLocale() !== 'en') {
            return $stats;
        }

        return array_map(fn (array $s): array => [
            'value' => $s['value'] ?? '',
            'suffix' => filled($s['suffix_en'] ?? null) ? $s['suffix_en'] : __($s['suffix'] ?? ''),
            'label' => filled($s['label_en'] ?? null) ? $s['label_en'] : __($s['label'] ?? ''),
        ], $stats);
    }
}
