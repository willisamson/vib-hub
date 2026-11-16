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
        'tagline' => 'Prüfstandsplanung, Beratung und Resonanzbeseitigung',
        'meta_description' => 'vib-hub – Shaker-Prüfstände resonanzfrei planen und aufbauen, Beratung zu Sinus-, Random- und Schockprüfungen nach DIN EN 60068, Resonanzbeseitigung, Berechnungen und Schulungen.',
        'email' => 'info@vib-hub.com',
        'notify_email' => null,
        'partner_name' => 'RMS Dynamic Test Systems',
        'partner_email' => null,
        'phone' => null,
        'address' => null,
        'linkedin' => null,
        'hero_kicker' => 'Beratung und Engineering für Schwingungstechnik',
        'hero_title' => 'Schwingungen verstehen. Resonanzen beherrschen.',
        'hero_text' => 'Wir planen Schwingprüfanlagen von Grund auf und bauen sie bei Ihnen resonanzfrei auf – von der Auslegung über Fundament und Vorrichtungen bis zur Abnahme. Dazu Beratung zu Sinus, Random und Schock, Messung, Berechnung und Resonanzbeseitigung.',
        'stats' => [
            ['value' => '1', 'suffix' => ' Ansprechpartner', 'label' => 'Von der Auslegung bis zur Abnahme'],
            ['value' => '3', 'suffix' => ' Achsen', 'label' => 'Aufbau für X, Y und Z mit Gleittisch'],
            ['value' => '40', 'suffix' => '+ Normen', 'label' => 'DIN, EN, ISO, IEC, MIL, VDI'],
            ['value' => '24', 'suffix' => ' h', 'label' => 'Rückmeldung auf Anfragen'],
        ],
        'footer_text' => 'Ingenieurdienstleistungen für Prüfstandsplanung, Prüfberatung, Resonanzanalyse und dynamische Auslegung.',
        'imprint' => null,
        'privacy' => null,
        'app_name' => 'PSD Profil Rechner',
        'app_url' => 'https://play.google.com/store/apps/details?id=de.example.psdprofilecalculator&hl=de',
        'app_text' => 'Kräfte und Hubwege für Schwingprüfungen direkt aus dem PSD-Profil berechnen – auf dem Smartphone, offline, ohne Werbung und ohne Datensammlung.',
        'app_image' => null,
        'logo' => null,
        'logo_dark' => null,
        'favicon' => null,
        'accounts_enabled' => true,
        'contact_requires_account' => false,
        'consent_version' => 1,
        'consent_title' => 'Cookies und Datenschutz',
        'consent_text' => 'Wir verwenden technisch notwendige Cookies, damit die Website und Ihr Kundenkonto funktionieren. Weitere Dienste wie Statistik oder externe Medien laden wir nur mit Ihrer Einwilligung. Sie können Ihre Auswahl jederzeit über „Cookie-Einstellungen“ im Seitenfuß ändern.',
        'consent_stats_name' => null,
        'stats_enabled' => true,
        'stats_require_consent' => false,
        'consent_stats_snippet' => null,
    ];

    /** Texte, die im Admin zusätzlich auf Englisch (Schlüssel + "_en") gepflegt werden können. */
    public const TRANSLATABLE = [
        'tagline', 'meta_description', 'hero_kicker', 'hero_title', 'hero_text',
        'footer_text', 'app_text', 'imprint', 'privacy', 'consent_title', 'consent_text',
    ];

    public const DEFAULTS_EN = [
        'tagline' => 'Test system planning, consulting and resonance elimination',
        'meta_description' => 'vib-hub – planning and setting up resonance-free shaker test systems, consulting on sine, random and shock testing to IEC 60068, resonance elimination, calculations and training.',
        'hero_kicker' => 'Consulting and engineering for vibration technology',
        'hero_title' => 'Understand vibration. Master resonance.',
        'hero_text' => 'We plan vibration test systems from the ground up and set them up resonance-free at your site – from sizing through foundation and fixtures to acceptance. Plus consulting on sine, random and shock, measurement, calculation and resonance elimination.',
        'footer_text' => 'Engineering services for test system planning, test consulting, resonance analysis and dynamic design.',
        'consent_title' => 'Cookies and privacy',
        'consent_text' => 'We use strictly necessary cookies so that the website and your customer account work. Further services such as statistics or external media are only loaded with your consent. You can change your choice at any time via “Cookie settings” in the footer.',
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

    public function accountsEnabled(): bool
    {
        return (bool) $this->get('accounts_enabled', true);
    }

    public function contactRequiresAccount(): bool
    {
        return $this->accountsEnabled() && (bool) $this->get('contact_requires_account', false);
    }

    public function statsEnabled(): bool
    {
        return (bool) $this->get('stats_enabled', true);
    }

    public function statsRequireConsent(): bool
    {
        return $this->statsEnabled() && (bool) $this->get('stats_require_consent', false);
    }

    public function consentVersion(): int
    {
        return max(1, (int) $this->get('consent_version', 1));
    }

    /** Partner für Prüfanlagen (Weiterleitung von Anfragen mit Zustimmung). */
    public function partnerName(): string
    {
        return (string) ($this->get('partner_name') ?: 'RMS Dynamic Test Systems');
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
