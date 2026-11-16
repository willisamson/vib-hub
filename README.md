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
| Regler-Simulator (Sinus/Random/Schock/Sinus auf Rauschen) | `resources/js/modules/sim.js`, Seite `/simulator` |
| Englische Website (`/en/...`) | Texte: `lang/en.json` · Inhalte: Admin, Abschnitt „English (/en)“ · Startseite: Admin → Website & Logo → English |
| Menüs (Kopf- und Fußzeile) | Admin → **Website → Menüs** (`app/Models/MenuItem.php`, `app/Support/Menu.php`) |
| Eigene Seiten (`/seite/...`) | Admin → **Website → Eigene Seiten** (`app/Models/Page.php`, View `pages/page`) |
| Kundenkonto (`/konto`) | `app/Http/Controllers/Account/`, Views `resources/views/account/`, Guard `customer` in `config/auth.php` · Admin → **Kunden → Kunden** · ein/aus: Website & Logo → Kundenkonto |
| Cookie-Hinweis | `resources/views/partials/consent.blade.php`, `resources/js/modules/consent.js` · Texte/Statistik-Code: Website & Logo → Cookies |
| Besucherstatistik | Admin → **Website → Besucher** (`app/Filament/Pages/Visitors.php`, Zählung: `app/Http/Middleware/TrackPageView.php`) · ein/aus: Website & Logo → Cookies |
| Admin-Anmeldung | `app/Filament/Pages/Auth/Login.php`, Stil: `resources/views/filament/auth/login-brand.blade.php` (Logo aus Website & Logo) |
| Zwei-Faktor-Anmeldung | Pflicht für alle Admins (Authenticator-App + Wiederherstellungscodes), verwalten unter Profil (oben rechts) · Notfall: `php artisan vibhub:2fa-reset admin@…` |
| Shaker-Auslegung | `/shaker-auslegung` (`resources/js/modules/planner.js`) · Modelle: Admin → **Inhalte → Shaker-Modelle** · Weiterleitung an Partner: Website & Logo → Kontakt |
| Datensicherung (Server) | `server/backup-vibhub.sh` – einmal `bash server/backup-vibhub.sh install`, dann täglich 3:17 Uhr nach `/var/backups/vibhub` (14 Tage + 8 Wochen) |
| Normen-Anforderungen | Admin → **Inhalte → Normen** → Abschnitt „Anforderungen“ · Bibliothek: `database/data/norm-library.php`, einspielen mit `php artisan vibhub:normen` (`--force` überschreibt Admin-Änderungen) |
| Einblenden beim Scrollen | `resources/js/modules/reveal.js` (respektiert „Bewegung reduzieren“) |

### Hinweise

- **Kunden** liegen in einer eigenen Tabelle `customers` und haben keinen Zugang zum Admin.
- **Passwort vergessen** verschickt eine E-Mail – dafür muss in der `.env` ein Mailserver eingetragen sein (`MAIL_MAILER=smtp`, `MAIL_HOST`, …). Ohne Mailserver landet die Mail nur im Log.
- **Cookie-Version** (Website & Logo → Cookies) erhöhen, wenn ein neuer Dienst dazukommt – dann werden alle Besucher erneut gefragt.
- **Besucherstatistik** zählt ohne Cookies und ohne IP-Adressen (täglich wechselnder Hash), ignoriert Bots, „Do Not Track“ und angemeldete Admins. Einträge älter als 400 Tage werden automatisch gelöscht.
- Eingebettete Videos/Karten (iframes) in Seiten und Leistungen laden erst nach Zustimmung zu „Externe Medien“.

## Rechner (vibmath.js)

Geprüft gegen Referenzwerte: NAVMAT P-9492 → 6,06 Grms, NASA GEVS → 14,1 Grms,
Halbsinus 50 g / 11 ms → Δv = 3,43 m/s, Übergangsfrequenz 0,75 mm / 10 g → 57,6 Hz,
Den-Hartog-Tilger, Einmassenschwinger. Schwingstärke-Zonen: Orientierung nach ISO 10816-1 Anhang B.
