<?php

namespace App\Support;

/**
 * Zentrale Auswahllisten – werden im Admin (Select-Felder) und im Frontend genutzt.
 */
class Catalog
{
    public const SERVICE_CATEGORIES = [
        'pruefung' => 'Prüfung',
        'analyse' => 'Analyse & Messung',
        'engineering' => 'Berechnung & Engineering',
        'service' => 'Service & Beratung',
    ];

    public const ICONS = [
        'sine' => 'Sinus',
        'random' => 'Random / Rauschen',
        'shock' => 'Schock',
        'resonance' => 'Resonanz',
        'modal' => 'Modalanalyse',
        'fem' => 'FEM / Simulation',
        'sensor' => 'Sensor / Messung',
        'machine' => 'Maschine',
        'building' => 'Bauwerk',
        'fixture' => 'Vorrichtung',
        'academy' => 'Schulung',
        'shield' => 'Qualität / Norm',
    ];

    public const DEMOS = [
        'resonance' => 'Resonanz-Demo (Tilger, Versteifung, Dämpfung)',
        'isolation' => 'Schwingungsisolierung (Übertragungsfunktion)',
        'sine' => 'Oszilloskop: Sinus',
        'random' => 'Oszilloskop: Random',
        'shock' => 'Oszilloskop: Schock',
    ];

    public const TRAINING_LEVELS = [
        'einsteiger' => 'Einsteiger',
        'fortgeschritten' => 'Fortgeschritten',
        'experte' => 'Experte',
    ];

    public const TRAINING_FORMATS = [
        'praesenz' => 'Präsenz',
        'online' => 'Online live',
        'inhouse' => 'Inhouse',
        'hybrid' => 'Hybrid',
    ];

    public const NORM_CATEGORIES = [
        'sinus' => 'Sinus',
        'random' => 'Random',
        'schock' => 'Schock',
        'allgemein' => 'Allgemein & Befestigung',
        'fahrzeug' => 'Fahrzeug',
        'bahn' => 'Bahn',
        'luftfahrt' => 'Luftfahrt & Militär',
        'transport' => 'Verpackung & Transport',
        'maschinen' => 'Maschinen',
        'bauwerk' => 'Bauwerke',
        'mensch' => 'Mensch',
        'kalibrierung' => 'Kalibrierung',
    ];

    public const INQUIRY_TOPICS = [
        'pruefung' => 'Prüfung (Sinus / Random / Schock)',
        'resonanz' => 'Resonanzbeseitigung',
        'berechnung' => 'Berechnung / Simulation',
        'service' => 'Service & Messung vor Ort',
        'schulung' => 'Schulung',
        'sonstiges' => 'Sonstiges',
    ];

    public const INQUIRY_STATUSES = [
        'neu' => 'Neu',
        'in_bearbeitung' => 'In Bearbeitung',
        'angebot' => 'Angebot versendet',
        'erledigt' => 'Erledigt',
    ];

    public static function label(array $list, ?string $key): string
    {
        return $list[$key] ?? (string) $key;
    }
}
