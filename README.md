# vib-hub.com

Website für Schwingungstechnik: Sinus-, Random- und Schockprüfung, Resonanzbeseitigung,
Berechnungen, Service, Normen und Schulungen – mit Admin-Bereich unter `/admin`.

**Technik:** Laravel 13 · Filament 5 (Admin) · Tailwind 4 / Vite · ohne zusätzliche JS-Bibliotheken.

## Lokal starten (Herd)

```bash
composer install
npm install
php artisan migrate
php artisan db:seed          # Startinhalte + lokaler Test-Admin
php artisan storage:link     # für hochgeladene Logos
npm run dev                  # oder einmalig: npm run build
```

Dann `http://vib-hub.test` öffnen, Admin unter `http://vib-hub.test/admin`
(lokal: `admin@vib-hub.test` / `password` – nur in der Umgebung `local` angelegt).

> Hinweis: Die `.env` nutzt MySQL (`DB_DATABASE=vibhub`). Ohne MySQL in Herd
> einfach `DB_CONNECTION=sqlite` setzen und die übrigen `DB_*`-Zeilen auskommentieren.

## Auf den Server

```bash
git add -A && git commit -m "vib-hub Website" && git push
# auf dem Server:
deploy-vibhub
php artisan db:seed --class=ContentSeeder --force   # einmalig: Startinhalte
```

## Aufbau

| Bereich | Wo |
|---|---|
| Seiten & Routen | `routes/web.php`, `app/Http/Controllers/` |
| Inhalte (Leistungen, Schulungen, Normen, Anfragen) | `app/Models/`, Admin: `app/Filament/Resources/` |
| Logo, Kontaktdaten, Startseitentexte, Impressum | Admin → **System → Website & Logo** (`app/Filament/Pages/Settings.php`) |
| Startinhalte | `database/seeders/ContentSeeder.php` |
| Design | `resources/css/app.css` (Farben/Schriften oben in `:root`) |
| Oszilloskop, Resonanz-Demo, Rechner | `resources/js/modules/` (`vibmath.js` = alle Formeln) |
| Logo-Dateien | `public/images/` |

## Rechner (vibmath.js)

Geprüft gegen Referenzwerte: NAVMAT P-9492 → 6,06 Grms, NASA GEVS → 14,1 Grms,
Halbsinus 50 g / 11 ms → Δv = 3,43 m/s, Übergangsfrequenz 0,75 mm / 10 g → 57,6 Hz,
Den-Hartog-Tilger, Einmassenschwinger. Schwingstärke-Zonen: Orientierung nach ISO 10816-1 Anhang B.
