<?php

namespace Database\Seeders;

use App\Models\Norm;
use App\Models\Service;
use App\Models\Training;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Support\NormLibrary;
use Illuminate\Support\Str;

/**
 * Startinhalte für vib-hub.com. Kann gefahrlos mehrfach laufen
 * (Einträge werden über den Slug aktualisiert statt doppelt angelegt).
 * Alles lässt sich danach im Admin unter /admin bearbeiten.
 */
class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->services();
        $this->trainings();
        $this->norms();
    }

    protected function services(): void
    {
        $services = [
            [
                'title' => 'Sinusschwingprüfung',
                'category' => 'pruefung',
                'icon' => 'sine',
                'demo' => 'sine',
                'is_featured' => true,
                'excerpt' => 'Gleitsinus und Resonanzverweilprüfung nach DIN EN 60068-2-6 – mit Resonanzsuche vor und nach der Belastung.',
                'features' => ['Resonanzsuche (Sweep) vor und nach der Prüfung', 'Gleitsinus, Festfrequenz und Resonanzverweilen', 'Weg-/Beschleunigungs-Übergang nach Spezifikation', 'Prüfung in drei Achsen mit Prüfbericht'],
                'body' => '<p>Die Sinusschwingprüfung ist der Klassiker, um Resonanzen zu finden und die Dauerfestigkeit eines Produkts gegenüber periodischen Schwingungen nachzuweisen. Wir fahren Frequenzbereich, Amplitude und Sweep-Rate exakt nach Ihrer Spezifikation – typischerweise mit konstantem Weg im unteren und konstanter Beschleunigung im oberen Frequenzbereich.</p><h3>Ablauf</h3><ol><li>Abstimmung der Prüfspezifikation und Befestigung nach DIN EN 60068-2-47</li><li>Resonanzsuche mit niedriger Amplitude</li><li>Belastungsprüfung (Gleitsinus oder Verweilen auf Resonanzen)</li><li>Abschließende Resonanzsuche – Frequenzverschiebungen zeigen Schädigungen</li></ol><p>Sie erhalten einen nachvollziehbaren Prüfbericht mit Regelprofilen, Übertragungsfunktionen und Fotodokumentation.</p>',
            ],
            [
                'title' => 'Random-Prüfung (Breitbandrauschen)',
                'category' => 'pruefung',
                'icon' => 'random',
                'demo' => 'random',
                'is_featured' => true,
                'excerpt' => 'Breitbandrauschen nach DIN EN 60068-2-64 – realitätsnahe Belastung mit definiertem Leistungsdichtespektrum (PSD).',
                'features' => ['PSD-Profile nach Norm oder Messdaten', 'Grms-, Weg- und Kraftbedarf vorab berechnet', 'Mehrpunkt-Regelung (Mittelwert/Extremwert)', 'Ableitung von Profilen aus Feldmessungen'],
                'body' => '<p>Reale Umgebungen – Fahrzeug, Bahn, Flugzeug, Transport – erzeugen keine sauberen Sinusschwingungen, sondern ein breitbandiges, zufälliges Signal. Bei der Random-Prüfung regeln wir den Schwingerreger auf ein vorgegebenes Leistungsdichtespektrum (PSD, in g²/Hz) und belasten alle Resonanzen Ihres Produkts gleichzeitig.</p><h3>Was wir vorab für Sie klären</h3><ul><li>Effektivwert (Grms) und Crest-Faktor des Profils</li><li>Erforderlicher Hub (Spitze-Spitze-Weg) und Kraftbedarf des Shakers</li><li>Prüfdauer je Achse und Zeitraffung aus Felddaten</li></ul>',
            ],
            [
                'title' => 'Schockprüfung',
                'category' => 'pruefung',
                'icon' => 'shock',
                'demo' => 'shock',
                'is_featured' => true,
                'excerpt' => 'Halbsinus-, Sägezahn- und Trapezschocks nach DIN EN 60068-2-27, Dauerschocken und Schock-Antwortspektren (SRS).',
                'features' => ['Halbsinus, Sägezahn, Trapez', 'Einzelschock und Dauerschocken (Bump)', 'Schock-Antwortspektrum (SRS)', 'Toleranzbänder nach Norm dokumentiert'],
                'body' => '<p>Stöße beim Transport, bei Fehlbedienung oder im Betrieb sind oft kurz, aber heftig. Mit der Schockprüfung weisen wir nach, dass Ihr Produkt definierte Stoßbelastungen ohne Funktionsausfall übersteht – zum Beispiel 30 g / 18 ms oder 50 g / 11 ms Halbsinus, je drei Schocks in jeder Richtung aller drei Achsen.</p><p>Bei komplexen Einsatzbedingungen arbeiten wir mit Schock-Antwortspektren (SRS) und synthetisieren daraus geeignete Prüfsignale.</p>',
            ],
            [
                'title' => 'Resonanzbeseitigung',
                'category' => 'engineering',
                'icon' => 'resonance',
                'demo' => 'resonance',
                'is_featured' => true,
                'excerpt' => 'Wir finden die Ursache störender Schwingungen und beseitigen sie – mit Versteifung, Dämpfung, Isolierung oder Schwingungstilger.',
                'features' => ['Ursachenanalyse mit FFT, Ordnungsanalyse und ODS', 'Maßnahmen per Simulation abgesichert', 'Schwingungstilger auslegen und abstimmen', 'Wirksamkeitsnachweis durch Messung'],
                'body' => '<p>Eine Resonanz liegt vor, wenn eine Erregerfrequenz – etwa Drehzahl, Zahneingriff oder Pumpenpuls – auf eine Eigenfrequenz der Struktur trifft. Die Folge: laute Geräusche, Risse, Lagerschäden oder Messfehler. Wir gehen systematisch vor:</p><ol><li><strong>Messen</strong> – Betriebsschwingungen, Hochlauf/Auslauf, Übertragungsfunktionen</li><li><strong>Analysieren</strong> – FFT, Campbell-Diagramm, Betriebsschwingformen (ODS), Modalanalyse</li><li><strong>Simulieren</strong> – FE-Modell abgleichen und Varianten bewerten</li><li><strong>Umsetzen</strong> – Versteifen, Masse verändern, Dämpfung erhöhen, isolieren oder tilgen</li><li><strong>Nachweisen</strong> – Kontrollmessung mit Vorher/Nachher-Vergleich</li></ol><p>Probieren Sie unten aus, wie sich die einzelnen Maßnahmen auf die Resonanzüberhöhung auswirken.</p>',
            ],
            [
                'title' => 'Modalanalyse & Betriebsschwingformen',
                'category' => 'analyse',
                'icon' => 'modal',
                'demo' => null,
                'is_featured' => false,
                'excerpt' => 'Experimentelle Modalanalyse mit Impulshammer oder Shaker und ODS-Analyse direkt im Betrieb.',
                'features' => ['Eigenfrequenzen, Dämpfungen, Eigenformen', 'Impulshammer und Shaker-Anregung', 'Animierte Schwingformen', 'Daten für den FE-Modellabgleich'],
                'body' => '<p>Mit der experimentellen Modalanalyse ermitteln wir die dynamischen Eigenschaften Ihrer Struktur: Eigenfrequenzen, modale Dämpfungen und Eigenformen. Die Betriebsschwingformanalyse (ODS) zeigt zusätzlich, wie sich die Struktur unter realer Erregung tatsächlich bewegt.</p>',
            ],
            [
                'title' => 'FEM-Berechnung & Simulation',
                'category' => 'engineering',
                'icon' => 'fem',
                'demo' => null,
                'is_featured' => false,
                'excerpt' => 'Modal-, Frequenzgang-, Random- und Schockanalysen im FE-Modell – bevor der erste Prototyp gebaut wird.',
                'features' => ['Modalanalyse und harmonische Analyse', 'PSD-/Random-Response und Lebensdauer', 'Schock- und SRS-Analyse', 'Auslegung von Prüfvorrichtungen'],
                'body' => '<p>Simulation spart Prototypen und Prüfschleifen. Wir berechnen Eigenfrequenzen, Spannungen unter Sinus-, Random- und Schockbelastung und bewerten die Lebensdauer. Messdaten aus Prüfung und Modalanalyse fließen in den Modellabgleich ein – so werden die Ergebnisse belastbar.</p>',
            ],
            [
                'title' => 'Schwingungsmessung & Maschinendiagnose',
                'category' => 'service',
                'icon' => 'machine',
                'demo' => null,
                'is_featured' => false,
                'excerpt' => 'Messungen vor Ort an Maschinen und Anlagen: Bewertung nach ISO 20816, Unwucht, Ausrichtung, Lagerschäden.',
                'features' => ['Bewertung nach ISO 20816 / ISO 10816', 'Hüllkurvenanalyse für Wälzlager', 'Auswuchten vor Ort', 'Trendmessungen und Zustandsüberwachung'],
                'body' => '<p>Wir kommen mit mobiler Messtechnik zu Ihnen und bewerten den Schwingungszustand Ihrer Maschinen. Auffälligkeiten wie Unwucht, Fehlausrichtung, Lockerungen oder beginnende Lagerschäden werden früh erkannt – bevor es zum Stillstand kommt.</p>',
            ],
            [
                'title' => 'Erschütterungsmessung an Bauwerken',
                'category' => 'service',
                'icon' => 'building',
                'demo' => 'isolation',
                'is_featured' => false,
                'excerpt' => 'Messung und Bewertung nach DIN 4150-2 und -3 – für Baustellen, Anlagen, Verkehr und schwingungsempfindliche Geräte.',
                'features' => ['Einwirkung auf Menschen (DIN 4150-2)', 'Einwirkung auf Gebäude (DIN 4150-3)', 'Dauerüberwachung mit Alarmierung', 'Isolierung von Maschinenfundamenten'],
                'body' => '<p>Erschütterungen durch Baumaßnahmen, Verkehr oder Maschinen können Anwohner stören und Gebäude beschädigen. Wir messen, bewerten nach DIN 4150 und planen Maßnahmen – von der elastischen Lagerung bis zur Fundamentisolierung.</p>',
            ],
            [
                'title' => 'Prüfvorrichtungen & Adapter',
                'category' => 'engineering',
                'icon' => 'fixture',
                'demo' => null,
                'is_featured' => false,
                'excerpt' => 'Steife, resonanzarme Prüfvorrichtungen – berechnet, gefertigt und messtechnisch abgenommen.',
                'features' => ['Erste Eigenfrequenz oberhalb des Prüfbereichs', 'Befestigung nach DIN EN 60068-2-47', 'Leichtbau für hohe Beschleunigungen', 'Abnahme per Übertragungsfunktion'],
                'body' => '<p>Eine Prüfung ist nur so gut wie ihre Vorrichtung. Liegt eine Vorrichtungsresonanz im Prüfbereich, wird der Prüfling falsch belastet. Wir legen Vorrichtungen per FEM aus und weisen ihre Eignung mit einer Übertragungsmessung nach.</p>',
            ],
            [
                'title' => 'Schulungen & Workshops',
                'category' => 'service',
                'icon' => 'academy',
                'demo' => null,
                'is_featured' => false,
                'excerpt' => 'Praxisnahe Seminare zu Schwingungsgrundlagen, Prüfnormen, Signalanalyse und Resonanzbeseitigung – offen oder inhouse.',
                'features' => ['Offene Termine und Inhouse-Schulungen', 'Live-Demos am Schwingerreger', 'Teilnahmebescheinigung', 'Unterlagen zum Nachschlagen'],
                'body' => '<p>Wir geben unser Wissen weiter – verständlich, praxisnah und mit vielen Live-Demonstrationen. Alle Termine finden Sie unter <a href="/schulungen">Schulungen</a>.</p>',
            ],
        ];

        $en = EnglishContent::services();

        foreach ($services as $i => $data) {
            $slug = Str::slug($data['title'], '-', 'de');
            Service::query()->updateOrCreate(
                ['slug' => $slug],
                $data + ['sort' => ($i + 1) * 10, 'is_published' => true, 'en' => $en[$slug] ?? null],
            );
        }
    }

    protected function trainings(): void
    {
        $d = fn (int $weeks, int $days = 1) => [
            'start' => Carbon::today()->addWeeks($weeks)->startOfWeek()->addDay()->toDateString(),
            'end' => Carbon::today()->addWeeks($weeks)->startOfWeek()->addDays($days)->toDateString(),
        ];

        $trainings = [
            [
                'title' => 'Grundlagen der Schwingungstechnik',
                'level' => 'einsteiger',
                'format' => 'praesenz',
                'duration' => '2 Tage',
                'price' => 1290,
                'excerpt' => 'Von der Feder-Masse-Schwingung bis zur Resonanz: das Fundament für alle, die mit Schwingungen zu tun haben.',
                'topics' => ['Einmassenschwinger, Eigenfrequenz und Dämpfung', 'Weg, Geschwindigkeit, Beschleunigung – Umrechnung', 'Resonanz und Übertragungsfunktion', 'Messkette: Sensor, Verstärker, Datenerfassung', 'Live-Demo am Schwingerreger'],
                'dates' => [$d(3, 2) + ['location' => 'Präsenz', 'seats' => 12], $d(12, 2) + ['location' => 'Präsenz', 'seats' => 12]],
                'body' => '<p>Das Seminar vermittelt die physikalischen Grundlagen anschaulich und ohne unnötige Formelflut. Jede Einheit wird mit Experimenten am Shaker begleitet.</p>',
            ],
            [
                'title' => 'Schwingprüfung nach DIN EN 60068: Sinus, Random, Schock',
                'level' => 'fortgeschritten',
                'format' => 'praesenz',
                'duration' => '2 Tage',
                'price' => 1490,
                'excerpt' => 'Prüfnormen richtig lesen, Spezifikationen ableiten und Prüfungen sicher durchführen.',
                'topics' => ['DIN EN 60068-2-6, -2-27, -2-64 im Detail', 'Befestigung nach DIN EN 60068-2-47', 'PSD, Grms, Crest-Faktor und Shaker-Auslegung', 'Halbsinus, Sägezahn, Trapez – Toleranzen', 'Prüfberichte und typische Fehler'],
                'dates' => [$d(5, 2) + ['location' => 'Präsenz', 'seats' => 10], $d(16, 2) + ['location' => 'Online live', 'seats' => 16]],
                'body' => '<p>Für Prüfingenieure, Entwickler und Qualitätsverantwortliche, die Schwingprüfungen spezifizieren, beauftragen oder selbst durchführen.</p>',
            ],
            [
                'title' => 'Signalanalyse & FFT in der Praxis',
                'level' => 'fortgeschritten',
                'format' => 'online',
                'duration' => '1 Tag',
                'price' => 790,
                'excerpt' => 'FFT, Fensterung, Mittelung, Ordnungsanalyse – Messdaten sicher interpretieren.',
                'topics' => ['Abtastung, Aliasing und Auflösung', 'Fensterfunktionen und Leckeffekt', 'Leistungsdichtespektrum (PSD)', 'Ordnungsanalyse und Campbell-Diagramm', 'Übertragungsfunktion und Kohärenz'],
                'dates' => [$d(7, 1) + ['location' => 'Online live', 'seats' => 20]],
                'body' => '<p>Mit vielen Beispielen aus realen Messungen – Sie arbeiten mit echten Datensätzen.</p>',
            ],
            [
                'title' => 'Resonanzen erkennen und beseitigen',
                'level' => 'experte',
                'format' => 'praesenz',
                'duration' => '2 Tage',
                'price' => 1590,
                'excerpt' => 'Von der Ursachenanalyse zur wirksamen Maßnahme: Versteifung, Dämpfung, Isolierung und Tilger.',
                'topics' => ['Modalanalyse und Betriebsschwingformen', 'Maßnahmen bewerten mit einfachen Modellen', 'Schwingungstilger auslegen (Den Hartog)', 'Elastische Lagerung und Isolierung', 'Fallbeispiele aus Maschinenbau und Elektronik'],
                'dates' => [$d(9, 2) + ['location' => 'Präsenz', 'seats' => 10]],
                'body' => '<p>Das Expertenseminar für alle, die Schwingungsprobleme nachhaltig lösen wollen – mit vielen Fallbeispielen und Rechenübungen.</p>',
            ],
            [
                'title' => 'Inhouse-Schulung nach Maß',
                'level' => 'einsteiger',
                'format' => 'inhouse',
                'duration' => 'nach Absprache',
                'price' => null,
                'excerpt' => 'Wir stellen die Inhalte auf Ihre Produkte, Normen und Prüfeinrichtungen ab – bei Ihnen vor Ort.',
                'topics' => ['Inhalte individuell kombinierbar', 'Arbeit an Ihren eigenen Prüflingen und Daten', 'Für Teams von 4 bis 16 Personen'],
                'dates' => [],
                'body' => '<p>Sprechen Sie uns an – wir erstellen ein passendes Programm.</p>',
            ],
        ];

        $en = EnglishContent::trainings();

        foreach ($trainings as $i => $data) {
            $slug = Str::slug($data['title'], '-', 'de');
            Training::query()->updateOrCreate(
                ['slug' => $slug],
                $data + ['sort' => ($i + 1) * 10, 'is_published' => true, 'en' => $en[$slug] ?? null],
            );
        }
    }

    protected function norms(): void
    {
        $norms = [
            ['DIN EN 60068-2-6', 'Umgebungseinflüsse – Prüfung Fc: Schwingen (sinusförmig)', 'sinus', 'Grundnorm für Sinusschwingprüfungen: Gleitsinus, Resonanzsuche und Verweilprüfungen.', [['label' => 'Signal', 'value' => 'Sinus, Gleitsinus'], ['label' => 'Typischer Bereich', 'value' => '10–500 Hz oder 10–2000 Hz'], ['label' => 'Sweep-Rate', 'value' => '1 Oktave/min'], ['label' => 'Achsen', 'value' => '3 (X, Y, Z)']]],
            ['DIN EN 60068-2-64', 'Umgebungseinflüsse – Prüfung Fh: Schwingen, Breitbandrauschen (digital geregelt)', 'random', 'Grundnorm für Random-Prüfungen mit definiertem Leistungsdichtespektrum.', [['label' => 'Signal', 'value' => 'Breitbandrauschen (PSD)'], ['label' => 'Kenngröße', 'value' => 'Grms, g²/Hz'], ['label' => 'Achsen', 'value' => '3 (X, Y, Z)']]],
            ['DIN EN 60068-2-27', 'Umgebungseinflüsse – Prüfung Ea: Schocken', 'schock', 'Grundnorm für Einzelschocks: Halbsinus, Sägezahn und Trapez mit Toleranzbändern.', [['label' => 'Pulsformen', 'value' => 'Halbsinus, Sägezahn, Trapez'], ['label' => 'Beispiele', 'value' => '30 g / 18 ms, 50 g / 11 ms'], ['label' => 'Anzahl', 'value' => 'typ. 3 je Richtung, 18 gesamt']]],
            ['DIN EN 60068-2-47', 'Umgebungseinflüsse – Befestigung von Prüflingen für Schwing-, Stoß- und ähnliche dynamische Prüfungen', 'allgemein', 'Regeln für die Befestigung von Prüflingen und die Lage der Regel- und Messpunkte.', []],
            ['DIN EN 60068-2-57', 'Umgebungseinflüsse – Prüfung Ff: Schwingen – Zeitlaufverfahren und Sinusschwebungen', 'sinus', 'Zeitverlaufsprüfungen, z. B. zur Nachbildung von Erdbebenbelastungen.', []],
            ['DIN EN 60068-2-80', 'Umgebungseinflüsse – Prüfung Fi: Schwingen – Mixed Mode', 'random', 'Überlagerte Signale: Sinus auf Random (SoR) und Random auf Random (RoR).', []],
            ['DIN EN 60068-2-31', 'Umgebungseinflüsse – Prüfung Ec: Schocks durch raue Handhabung, vorwiegend für Geräte', 'schock', 'Fall- und Kippprüfungen für Geräte, die bei Handhabung Stößen ausgesetzt sind.', []],
            ['DIN EN 61373', 'Bahnanwendungen – Betriebsmittel von Bahnfahrzeugen – Prüfungen für Schwingen und Schocken', 'bahn', 'Random-Funktionsprüfung, Langzeit-Lebensdauerprüfung und Schocks für Bahnkomponenten nach Einbaukategorie.', [['label' => 'Kategorien', 'value' => '1A/1B (Wagenkasten), 2 (Drehgestell), 3 (Achse)']]],
            ['ISO 16750-3', 'Straßenfahrzeuge – Umgebungsbedingungen und Prüfungen für elektrische und elektronische Ausrüstungen – Teil 3: Mechanische Belastungen', 'fahrzeug', 'Schwing- und Schockprüfungen für E/E-Komponenten nach Einbauort im Fahrzeug.', []],
            ['MIL-STD-810 Method 514', 'Environmental Engineering Considerations and Laboratory Tests – Vibration', 'luftfahrt', 'Militärische Prüfnorm mit Tailoring-Ansatz: Prüfprofile werden aus dem Lebenszyklus abgeleitet.', []],
            ['MIL-STD-810 Method 516', 'Environmental Engineering Considerations and Laboratory Tests – Shock', 'luftfahrt', 'Schockprüfungen inkl. Schock-Antwortspektren (SRS), Crash-Hazard und Transportstöße.', []],
            ['RTCA DO-160', 'Environmental Conditions and Test Procedures for Airborne Equipment (Abschnitt 7 und 8)', 'luftfahrt', 'Luftfahrt-Umweltprüfungen: Abschnitt 7 Betriebsschocks und Crash-Sicherheit, Abschnitt 8 Schwingungen.', []],
            ['DIN EN ISO 13355', 'Verpackung – Versandfertige Packstücke – Vertikale Schwingprüfung mit Zufallsanregung', 'transport', 'Random-Prüfung für Packstücke zur Nachbildung des Straßen- und Bahntransports.', []],
            ['ASTM D4169', 'Standard Practice for Performance Testing of Shipping Containers and Systems', 'transport', 'Prüfsequenzen für Versandeinheiten: Handhabung, Stapeln, Transportschwingungen.', []],
            ['ISTA 3A', 'Packaged-Products for Parcel Delivery System Shipment', 'transport', 'Simulationsprüfung für Pakete im Paketdienst: Fall, Schwingung, Druck.', []],
            ['ISO 20816-1', 'Mechanische Schwingungen – Messung und Bewertung der Schwingungen von Maschinen – Teil 1: Allgemeine Anleitungen', 'maschinen', 'Nachfolger der ISO 10816-1: Bewertung über Schwingstärke und Zonen A bis D.', [['label' => 'Messgröße', 'value' => 'Schwinggeschwindigkeit v_rms in mm/s'], ['label' => 'Bewertung', 'value' => 'Zonen A / B / C / D']]],
            ['ISO 20816-3', 'Mechanische Schwingungen – Messung und Bewertung der Schwingungen von Maschinen – Teil 3: Industriemaschinen mit Leistungen über 15 kW', 'maschinen', 'Bewertung industrieller Maschinen mit Nenndrehzahlen zwischen 120 und 30 000 min⁻¹.', []],
            ['DIN 4150-2', 'Erschütterungen im Bauwesen – Teil 2: Einwirkungen auf Menschen in Gebäuden', 'bauwerk', 'Anhaltswerte für die Beurteilung von Erschütterungen in Wohnungen und vergleichbar genutzten Räumen.', []],
            ['DIN 4150-3', 'Erschütterungen im Bauwesen – Teil 3: Einwirkungen auf bauliche Anlagen', 'bauwerk', 'Anhaltswerte für die Schwinggeschwindigkeit, bei deren Einhaltung keine Schäden zu erwarten sind.', []],
            ['ISO 2631-1', 'Mechanische Schwingungen und Stöße – Bewertung der Einwirkung von Ganzkörper-Schwingungen auf den Menschen – Teil 1: Allgemeine Anforderungen', 'mensch', 'Frequenzbewertete Beschleunigung zur Beurteilung von Komfort und Gesundheit.', []],
            ['DIN EN ISO 5349-1', 'Mechanische Schwingungen – Messung und Bewertung der Einwirkung von Schwingungen auf das Hand-Arm-System des Menschen – Teil 1: Allgemeine Anforderungen', 'mensch', 'Grundlage für die Beurteilung von Hand-Arm-Vibrationen an Werkzeugen und Maschinen.', []],
            ['VDI 2057', 'Einwirkung mechanischer Schwingungen auf den Menschen', 'mensch', 'Richtlinienreihe für Ganzkörper- und Hand-Arm-Schwingungen.', []],
            ['VDI 2062', 'Schwingungsisolierung', 'allgemein', 'Begriffe, Methoden und Elemente der Schwingungsisolierung.', []],
            ['VDI 3830', 'Werkstoff- und Bauteildämpfung', 'allgemein', 'Kenngrößen und Messverfahren für die Dämpfung von Werkstoffen und Bauteilen.', []],
            ['ISO 16063', 'Verfahren zur Kalibrierung von Schwingungs- und Stoßaufnehmern', 'kalibrierung', 'Primär- und Vergleichskalibrierung von Beschleunigungsaufnehmern.', []],
            ['DIN EN 60721-3', 'Klassifizierung von Umweltbedingungen – Teil 3: Klassen von Umwelteinflussgrößen und deren Grenzwerte', 'allgemein', 'Klassen für Lagerung, Transport und ortsfesten Einsatz – Basis für die Ableitung von Prüfschärfen.', []],
        ];

        $en = EnglishContent::norms();

        foreach ($norms as $i => [$code, $title, $category, $excerpt, $params]) {
            Norm::query()->updateOrCreate(
                ['slug' => Str::slug($code, '-', 'de')],
                [
                    'code' => $code,
                    'title' => $title,
                    'category' => $category,
                    'excerpt' => $excerpt,
                    'parameters' => $params,
                    'body' => null,
                    'sort' => ($i + 1) * 10,
                    'is_published' => true,
                    'en' => $en[$code] ?? null,
                ],
            );
        }
        // Anforderungen und weitere Normen aus der Normen-Bibliothek
        NormLibrary::sync(true);
    }
}
