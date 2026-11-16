<?php

/*
 * Normen-Bibliothek: wesentliche Anforderungen je Norm, in eigenen Worten zusammengefasst.
 * Wird von App\Support\NormLibrary eingespielt (Migration bzw. "php artisan vibhub:normen").
 *
 * Aufbau je Norm (Schlüssel = URL-Kürzel):
 *   new     – nur für neu anzulegende Normen: code, title, category, sort, excerpt, en[title, excerpt]
 *   params  – Eckdaten [Merkmal, Wert, Merkmal EN, Wert EN] (nur wenn noch keine vorhanden)
 *   req     – Anforderungen [Gruppe, Anforderung, Festlegung, Festlegung EN (optional)]
 *             Gruppen- und Anforderungsnamen werden über "labels" übersetzt.
 *             Ohne EN-Wert wird der deutsche Wert mit Dezimalpunkt angezeigt.
 */

return [
    'labels' => [
        // Gruppen
        'Schärfe' => 'Severity',
        'Toleranzen' => 'Tolerances',
        'Ablauf' => 'Procedure',
        'Signal' => 'Signal',
        'Regelung' => 'Control',
        'Praxis' => 'In practice',
        'Messsystem' => 'Measuring system',
        'Einbaukategorien' => 'Mounting categories',
        'Funktionsprüfung (Random, Effektivwert)' => 'Functional test (random, rms)',
        'Simulierte Lebensdauerprüfung (Random, Effektivwert)' => 'Simulated long-life test (random, rms)',
        'Schock (Halbsinus)' => 'Shock (half-sine)',
        'Schwingen' => 'Vibration',
        'Mechanischer Schock' => 'Mechanical shock',
        'Freier Fall' => 'Free fall',
        'Abschnitt 7 – Schock und Crash' => 'Section 7 – Shock and crash safety',
        'Abschnitt 8 – Schwingen' => 'Section 8 – Vibration',
        'Kurzzeitige Erschütterungen' => 'Short-term vibration',
        'Dauererschütterungen' => 'Long-term vibration',
        'T3 Schwingung' => 'T3 vibration',
        'T4 Schock' => 'T4 shock',
        'Komponenten-Random' => 'Component random vibration',
        'Typ I – Umgebungsschwingung' => 'Type I – environmental vibration',
        'Typ II – innere Anregung' => 'Type II – internally excited',
        // Anforderungen
        'Hinweis' => 'Note',
        'Zweck' => 'Purpose',
        'Ziel' => 'Objective',
        'Inhalt' => 'Content',
        'Ausgabe' => 'Edition',
        'Grundsatz' => 'Principle',
        'Grundprinzip' => 'Principle',
        'Prinzip' => 'Principle',
        'Anwendung' => 'Application',
        'Abgrenzung' => 'Scope limits',
        'Frequenzbereich' => 'Frequency range',
        'Amplitude' => 'Amplitude',
        'Übergangsfrequenz' => 'Crossover frequency',
        'Sweep-Rate' => 'Sweep rate',
        'Dauerprüfung' => 'Endurance test',
        'Amplitude am Bezugspunkt' => 'Amplitude at reference point',
        'Amplitude an Kontrollpunkten' => 'Amplitude at check points',
        'Querbewegung' => 'Cross-axis motion',
        'Resonanzsuche' => 'Resonance search',
        'Befestigung' => 'Mounting',
        'Prüfprofil' => 'Test profile',
        'Prüfdauer' => 'Test duration',
        'Verteilung' => 'Distribution',
        'Statistische Genauigkeit' => 'Statistical accuracy',
        'ASD' => 'ASD',
        'Effektivwert' => 'RMS value',
        'Ein- und Mehrpunktregelung' => 'Single and multipoint control',
        'Pegelstufen' => 'Level steps',
        'Pulsformen' => 'Pulse shapes',
        'Beispiele aus der Vorzugsreihe' => 'Examples of preferred severities',
        'Anzahl' => 'Number',
        'Dauerschocken' => 'Repetitive shock (bump)',
        'Pulsverlauf' => 'Pulse shape',
        'Geschwindigkeitsänderung' => 'Velocity change',
        'Querbeschleunigung' => 'Cross-axis acceleration',
        'Frequenzgang' => 'Frequency response',
        'Vorrichtung' => 'Fixture',
        'Messpunkte' => 'Measuring points',
        'Verpackte Prüflinge' => 'Packaged specimens',
        'Kabel und Leitungen' => 'Cables and lines',
        'Dokumentation' => 'Documentation',
        'Verfahren' => 'Methods',
        'Bewertung' => 'Assessment',
        'Sinusschwebung' => 'Sine beat',
        'Dämpfung' => 'Damping',
        'Achsen' => 'Axes',
        'Sinusanteile' => 'Sine components',
        'Random-Anteil' => 'Random component',
        'Ausprobieren' => 'Try it',
        'Fallen und Kippen' => 'Drop and topple',
        'Freier Fall (Verfahren 1)' => 'Free fall (procedure 1)',
        'Wiederholter freier Fall (Verfahren 2)' => 'Repeated free fall (procedure 2)',
        'Aufprallfläche' => 'Impact surface',
        'Prüfling' => 'Specimen',
        'Kategorie 1 Klasse A' => 'Category 1 class A',
        'Kategorie 1 Klasse B' => 'Category 1 class B',
        'Kategorie 1 A' => 'Category 1 A',
        'Kategorie 1 B' => 'Category 1 B',
        'Kategorie 1 A/B' => 'Category 1 A/B',
        'Kategorie 2' => 'Category 2',
        'Kategorie 3' => 'Category 3',
        'Dauer' => 'Duration',
        'Abnahme' => 'Acceptance',
        'Signalart' => 'Signal type',
        'Beispiel Karosserie (Pkw, Test IV)' => 'Example vehicle body (passenger car, test IV)',
        'Karosserie und Rahmen' => 'Vehicle body and frame',
        'Türen und Klappen' => 'Doors and flaps',
        'Weitere Prüfungen' => 'Further tests',
        'Funktionszustand' => 'Functional status',
        'Mindest-Integritätsprüfung' => 'Minimum integrity test',
        'Beispielkategorien' => 'Example categories',
        'Funktionsschock (I)' => 'Functional shock (I)',
        'Crash Hazard (V)' => 'Crash hazard (V)',
        'Transportfall (IV)' => 'Transit drop (IV)',
        'Bench Handling (VI)' => 'Bench handling (VI)',
        'Alternative' => 'Alternative',
        'Betriebsschock' => 'Operational shock',
        'Crash Safety' => 'Crash safety',
        'Kategorien' => 'Categories',
        'Prüfkurven' => 'Test curves',
        'Anregung' => 'Excitation',
        'Allgemeines Profil' => 'Generic profile',
        'Aufbau' => 'Structure',
        'Prüfungen (Schedules)' => 'Tests (schedules)',
        'Lkw-Random' => 'Truck random',
        'Schwingung' => 'Vibration',
        'Fall' => 'Drop',
        'Pakettypen' => 'Package types',
        'Messgröße' => 'Measured quantity',
        'Zonen' => 'Zones',
        'Alarm und Abschaltung' => 'Alarm and trip',
        'Änderungen' => 'Changes',
        'Gruppe 1' => 'Group 1',
        'Gruppe 2' => 'Group 2',
        'Zonengrenzen Gruppe 1' => 'Zone boundaries group 1',
        'Zonengrenzen Gruppe 2' => 'Zone boundaries group 2',
        'Starr oder nachgiebig' => 'Rigid or flexible',
        'Alarm' => 'Alarm',
        'Kenngrößen' => 'Parameters',
        'Anhaltswerte tags (Au / Ao / Ar)' => 'Guideline values by day (Au / Ao / Ar)',
        'Anhaltswerte nachts (Au / Ao / Ar)' => 'Guideline values at night (Au / Ao / Ar)',
        'Beurteilungszeiten' => 'Assessment periods',
        'Baumaßnahmen' => 'Construction work',
        'Fundament' => 'Foundation',
        'Oberste Deckenebene, horizontal' => 'Top floor, horizontal',
        'Decken, vertikal' => 'Floors, vertical',
        'Bedeutung' => 'Meaning',
        'Frequenzbewertung' => 'Frequency weighting',
        'Gesundheit' => 'Health',
        'Komfort (Richtwerte für aw)' => 'Comfort (guide values for aw)',
        'Stöße' => 'Shocks',
        'Tagesexposition' => 'Daily exposure',
        'Grenzwerte in Deutschland' => 'Limit values in Germany',
        'Teil 2' => 'Part 2',
        'Blätter' => 'Parts',
        'Ganzkörper' => 'Whole-body',
        'Hand-Arm' => 'Hand-arm',
        'Messgeräte' => 'Instruments',
        'Auslegung' => 'Design',
        'Arten' => 'Types',
        'Rechner' => 'Calculator',
        'Umrechnung' => 'Conversion',
        'Messverfahren' => 'Measuring methods',
        'Teile' => 'Parts',
        'Kalibriergrößen' => 'Calibrated quantities',
        'Referenzbedingungen' => 'Reference conditions',
        'Rückführung' => 'Traceability',
        'Messunsicherheit' => 'Measurement uncertainty',
        'Mechanische Klassen' => 'Mechanical classes',
        'Prüfschärfen' => 'Test severities',
        'Neue Ausgaben' => 'New editions',
        'Vorgabe' => 'Requirement',
        'Toleranz' => 'Tolerance',
        'Zeitraffung' => 'Time compression',
        'Prüfzweck' => 'Test purpose',
        'Grundlage' => 'Basis',
        'Vorgaben' => 'Inputs',
        'Verwandte Normen' => 'Related standards',
        'Pegel' => 'Level',
        'Puls' => 'Pulse',
        'Reihenfolge' => 'Sequence',
        'Kriterien' => 'Criteria',
        'Prüfarten' => 'Test types',
        'Schärfeklassen' => 'Severity classes',
        'Schwingen (Teil 1), Klasse 2' => 'Vibration (part 1), class 2',
        'Schocken (Teil 2), Klasse 1' => 'Shock (part 2), class 1',
        'Qualifikation' => 'Qualification',
        'Abnahme (Flughardware)' => 'Acceptance (flight hardware)',
        'Protoflight' => 'Protoflight',
        'Mindest-Workmanship' => 'Minimum workmanship',
        'Zuschlag' => 'Margin',
        'Simulator' => 'Simulator',
        'Erkundungsprüfung' => 'Exploratory test',
        'Variable Frequenz' => 'Variable frequency test',
        'Wuchtgütestufe G' => 'Balance quality grade G',
        'Beispiel' => 'Example',
        'Typische Stufen' => 'Typical grades',
        'Aufteilung' => 'Allocation',
        'Ersetzt' => 'Supersedes',
        'Zusammenhang' => 'Context',
        'Angaben' => 'Specification items',
        'Schwingen und Schocken' => 'Vibration and shock',
        'Weitere Umweltprüfungen' => 'Further environmental tests',
        'Befestigung (von steif nach weich)' => 'Mounting (from stiff to soft)',
        'Einfluss' => 'Effect',
        'Masse' => 'Mass',
        'Kabel' => 'Cable',
        'Frequenzbewertungen' => 'Frequency weightings',
        'Prüfungen' => 'Tests',
        'Überwachung' => 'Monitoring',
        'Weitere Teile' => 'Further parts',
    ],

    'norms' => [

        /* ---------------------------------------------------------------- Sinus */

        'din-en-60068-2-6' => [
            'req' => [
                ['', 'Zweck', 'Resonanzsuche (Schwingungsantwort-Untersuchung) und Dauerprüfung mit Gleitsinus oder bei festen Frequenzen', 'Vibration response investigation (resonance search) and endurance testing with swept sine or at fixed frequencies'],
                ['Schärfe', 'Frequenzbereich', 'Auswahl aus Vorzugsbereichen, z. B. 10–55 Hz, 10–150 Hz, 10–500 Hz, 10–2000 Hz oder 55–2000 Hz', 'Selected from preferred ranges, e.g. 10–55 Hz, 10–150 Hz, 10–500 Hz, 10–2000 Hz or 55–2000 Hz'],
                ['Schärfe', 'Amplitude', 'Unterhalb der Übergangsfrequenz konstanter Weg, darüber konstante Beschleunigung – z. B. 0,35 mm / 50 m/s², 0,75 mm / 100 m/s² oder 1,5 mm / 200 m/s²', 'Constant displacement below the crossover frequency, constant acceleration above – e.g. 0.35 mm / 50 m/s², 0.75 mm / 100 m/s² or 1.5 mm / 200 m/s²'],
                ['Schärfe', 'Übergangsfrequenz', '8–10 Hz oder 58–62 Hz – dort passen Weg- und Beschleunigungsamplitude zusammen', '8–10 Hz or 58–62 Hz – where displacement and acceleration amplitude match'],
                ['Schärfe', 'Sweep-Rate', '1 Oktave/min (±10 %), logarithmisch', '1 octave/min (±10 %), logarithmic'],
                ['Schärfe', 'Dauerprüfung', 'Gleitsinus: Anzahl der Sweep-Zyklen je Achse nach Spezifikation; feste Frequenzen: Verweildauer z. B. 10 min, 30 min, 90 min oder 10 h', 'Swept sine: number of sweep cycles per axis as specified; fixed frequencies: dwell time e.g. 10 min, 30 min, 90 min or 10 h'],
                ['Toleranzen', 'Amplitude am Bezugspunkt', '±15 %'],
                ['Toleranzen', 'Amplitude an Kontrollpunkten', '±25 % bis 500 Hz, ±50 % darüber', '±25 % up to 500 Hz, ±50 % above'],
                ['Toleranzen', 'Querbewegung', 'Bis 500 Hz höchstens 50 % der Sollamplitude, darüber bis 100 %', 'Up to 500 Hz at most 50 % of the specified amplitude, above up to 100 %'],
                ['Ablauf', 'Resonanzsuche', 'Vor und nach der Dauerprüfung mit niedrigem Pegel; verschobene Resonanzen deuten auf Schäden hin', 'Before and after the endurance test at low level; shifted resonances indicate damage'],
                ['Ablauf', 'Befestigung', 'Nach DIN EN 60068-2-47, möglichst wie im späteren Einsatz', 'To IEC 60068-2-47, as close as possible to the service mounting'],
            ],
        ],

        'din-en-60068-2-57' => [
            'params' => [['Verfahren', 'Zeitverlauf, Sinusschwebung', 'Methods', 'Time history, sine beat'], ['Bewertung', 'Antwortspektrum (RRS/TRS)', 'Assessment', 'Response spectrum (RRS/TRS)']],
            'req' => [
                ['', 'Verfahren', 'Zeitverlaufsprüfung mit vorgegebenem Beschleunigungsverlauf oder Sinusschwebungen (Sinus mit ansteigender und abklingender Hüllkurve)', 'Time-history test with a specified acceleration history or sine beats (sine with rising and decaying envelope)'],
                ['', 'Bewertung', 'Über Antwortspektren: das Prüf-Antwortspektrum (TRS) muss das geforderte Antwortspektrum (RRS) einhüllen', 'Via response spectra: the test response spectrum (TRS) has to envelope the required response spectrum (RRS)'],
                ['', 'Dämpfung', 'Antwortspektren werden für eine festgelegte Dämpfung angegeben, häufig 5 %', 'Response spectra are given for a specified damping, often 5 %'],
                ['', 'Sinusschwebung', 'Anzahl der Schwingungen je Schwebung, Anzahl der Schwebungen und Pausen nach Spezifikation; bevorzugt bei bekannten Eigenfrequenzen', 'Number of cycles per beat, number of beats and pauses as specified; preferred when natural frequencies are known'],
                ['', 'Achsen', 'Ein-, zwei- oder dreiachsige Anregung nach Spezifikation', 'Single-, bi- or tri-axial excitation as specified'],
                ['', 'Anwendung', 'Erdbeben und vergleichbare kurze, starke Belastungen', 'Earthquakes and similar short, severe loads'],
            ],
        ],

        /* ---------------------------------------------------------------- Random */

        'din-en-60068-2-64' => [
            'req' => [
                ['', 'Hinweis', 'IEC 60068-2-64 Ausgabe 3.0 ist im Oktober 2026 erschienen; die deutsche Fassung folgt. Die Werte unten entsprechen der Ausgabe 2008 mit Änderung 1 (2019).', 'IEC 60068-2-64 edition 3.0 was published in October 2026. The values below correspond to the 2008 edition with amendment 1 (2019).'],
                ['Schärfe', 'Prüfprofil', 'Beschleunigungsdichtespektrum (ASD/PSD in g²/Hz oder (m/s²)²/Hz) mit Frequenzbereich und Effektivwert', 'Acceleration spectral density (ASD/PSD in g²/Hz or (m/s²)²/Hz) with frequency range and rms value'],
                ['Schärfe', 'Prüfdauer', 'Je Achse nach Spezifikation, aus der Lebensdauer abgeleitet, ggf. mit Zeitraffung', 'Per axis as specified, derived from the service life, time-compressed where appropriate'],
                ['Signal', 'Verteilung', 'Gaußverteilt; der Crest-Faktor des Ansteuersignals darf nicht unter 2,5 begrenzt werden', 'Gaussian; the crest factor of the drive signal must not be limited below 2.5'],
                ['Signal', 'Statistische Genauigkeit', 'Mindestens 120 statistische Freiheitsgrade (DOF) für die Regelung', 'At least 120 statistical degrees of freedom (DOF) for control'],
                ['Toleranzen', 'ASD', '±3 dB im Prüffrequenzbereich', '±3 dB within the test frequency range'],
                ['Toleranzen', 'Effektivwert', '±10 % vom Sollwert', '±10 % of the specified value'],
                ['Regelung', 'Ein- und Mehrpunktregelung', 'Mehrere Kontrollpunkte mit Mittelwert- oder Extremwertregelung möglich', 'Several check points with averaging or extremal control are possible'],
                ['Praxis', 'Pegelstufen', 'Anfahren über reduzierte Pegel (z. B. −12, −9, −6, −3 dB) vor der Volllast', 'Ramp up via reduced levels (e.g. −12, −9, −6, −3 dB) before full level'],
            ],
        ],

        'din-en-60068-2-80' => [
            'params' => [['Verfahren', 'SoR, RoR, SRoR', 'Methods', 'SoR, RoR, SRoR'], ['Typisch für', 'Hubschrauber, Kettenfahrzeuge', 'Typical for', 'Helicopters, tracked vehicles']],
            'req' => [
                ['', 'Verfahren', 'Sinus auf Random (SoR), Random auf Random (RoR) sowie Sinus und Random auf Random', 'Sine-on-random (SoR), random-on-random (RoR) and sine-and-random-on-random'],
                ['', 'Anwendung', 'Hubschrauber (Rotor- und Blattfrequenzen), Propellerflugzeuge, Kettenfahrzeuge, rotierende Maschinen', 'Helicopters (rotor and blade frequencies), propeller aircraft, tracked vehicles, rotating machinery'],
                ['', 'Sinusanteile', 'Feste oder gewobbelte Töne mit eigener Amplitude, eigenem Frequenzband und eigener Wobbelrate', 'Fixed or swept tones with their own amplitude, frequency band and sweep rate'],
                ['', 'Random-Anteil', 'Breitbandiges ASD wie bei DIN EN 60068-2-64', 'Broadband ASD as in IEC 60068-2-64'],
                ['', 'Regelung', 'Rauschen und Töne werden getrennt geregelt und mit eigenen Toleranzen überwacht', 'Random and tones are controlled separately and monitored with their own tolerances'],
                ['', 'Ausprobieren', 'Im vib-hub Regler-Simulator, Betriebsart „Sinus auf Rauschen“', 'In the vib-hub controller simulator, mode “Sine on random”'],
            ],
        ],

        /* ---------------------------------------------------------------- Schock */

        'din-en-60068-2-27' => [
            'req' => [
                ['', 'Pulsformen', 'Halbsinus (bevorzugt), Sägezahn mit Endspitze, Trapez', 'Half-sine (preferred), final-peak sawtooth, trapezoid'],
                ['Schärfe', 'Beispiele aus der Vorzugsreihe', '150 m/s² / 11 ms, 300 m/s² / 18 ms, 500 m/s² / 11 ms, 1000 m/s² / 6 ms (rund 15 g / 11 ms bis 100 g / 6 ms)', '150 m/s² / 11 ms, 300 m/s² / 18 ms, 500 m/s² / 11 ms, 1000 m/s² / 6 ms (roughly 15 g / 11 ms to 100 g / 6 ms)'],
                ['Schärfe', 'Anzahl', 'Sofern nicht anders festgelegt 3 Schocks je Richtung, bei drei Achsen also 18', 'Unless specified otherwise 3 shocks per direction, i.e. 18 for three axes'],
                ['Schärfe', 'Dauerschocken', 'Wiederholte Schocks (früher DIN EN 60068-2-29, seit 2008 Teil von 2-27) mit großer Anzahl je Richtung', 'Repetitive shocks (formerly IEC 60068-2-29, part of 2-27 since 2008) with a large number per direction'],
                ['Toleranzen', 'Pulsverlauf', 'Innerhalb des Toleranzbandes der gewählten Pulsform', 'Within the tolerance band of the selected pulse shape'],
                ['Toleranzen', 'Geschwindigkeitsänderung', '±15 % vom Nennwert', '±15 % of the nominal value'],
                ['Toleranzen', 'Querbeschleunigung', 'Höchstens 30 % der Nenn-Spitzenbeschleunigung', 'At most 30 % of the nominal peak acceleration'],
                ['Messsystem', 'Frequenzgang', 'Ausreichend breitbandig für die Pulsdauer – je kürzer der Puls, desto höher die obere Grenzfrequenz', 'Wide enough for the pulse duration – the shorter the pulse, the higher the upper cut-off frequency'],
            ],
        ],

        'din-en-60068-2-31' => [
            'params' => [['Prüfungen', 'Fallen, Kippen, freier Fall', 'Tests', 'Drop, topple, free fall'], ['Fallhöhen', '25 mm bis 1000 mm', 'Drop heights', '25 mm to 1000 mm']],
            'req' => [
                ['', 'Fallen und Kippen', 'Fallen auf eine Fläche, auf eine Kante oder Ecke und Umkippen; Höhen und Winkel nach Spezifikation, Toleranz ±10 %', 'Dropping onto a face, onto an edge or corner and toppling; heights and angles as specified, tolerance ±10 %'],
                ['', 'Freier Fall (Verfahren 1)', 'Fallhöhen aus der Reihe 25, 50, 100, 250, 500 und 1000 mm, abhängig von Masse und Handhabung', 'Drop heights from the series 25, 50, 100, 250, 500 and 1000 mm, depending on mass and handling'],
                ['', 'Wiederholter freier Fall (Verfahren 2)', 'Taumeltrommel für kleine Bauteile und Steckverbinder; Anzahl der Fälle nach Spezifikation', 'Tumbling barrel for small components and connectors; number of drops as specified'],
                ['', 'Aufprallfläche', 'Glatt, hart und starr, z. B. Beton oder Stahl', 'Smooth, hard and rigid, e.g. concrete or steel'],
                ['', 'Prüfling', 'Frei, ohne Befestigung, im Betriebs- oder Transportzustand', 'Free, without fixing, in operating or transport condition'],
                ['', 'Abgrenzung', 'Für genau definierte Schockpulse gilt DIN EN 60068-2-27', 'For precisely defined shock pulses use IEC 60068-2-27'],
            ],
        ],

        'din-en-60068-2-81' => [
            'new' => [
                'code' => 'DIN EN 60068-2-81', 'category' => 'schock', 'sort' => 35,
                'title' => 'Umgebungseinflüsse – Prüfung Ei: Schocken – Synthese eines Schockantwortspektrums',
                'excerpt' => 'Schockprüfung über ein gefordertes Schockantwortspektrum (SRS) statt über eine feste Pulsform.',
                'en' => ['title' => 'Environmental testing – Test Ei: Shock – Shock response spectrum synthesis', 'excerpt' => 'Shock testing via a required shock response spectrum (SRS) instead of a fixed pulse shape.'],
            ],
            'params' => [['Vorgabe', 'Schockantwortspektrum (SRS)', 'Requirement', 'Shock response spectrum (SRS)'], ['Signal', 'synthetisiert', 'Signal', 'synthesised']],
            'req' => [
                ['', 'Vorgabe', 'Gefordertes Schockantwortspektrum über der Frequenz, meist für 5 % Dämpfung (Q = 10)', 'Required shock response spectrum over frequency, mostly for 5 % damping (Q = 10)'],
                ['', 'Signal', 'Synthetisierte Zeitsignale, z. B. aus abklingenden Sinusschwingungen (Wavelets)', 'Synthesised time signals, e.g. from decaying sinusoids (wavelets)'],
                ['', 'Toleranz', 'Das erzeugte Spektrum muss innerhalb der festgelegten Grenzen um das geforderte liegen', 'The achieved spectrum has to lie within the specified limits around the required one'],
                ['', 'Anzahl', 'Anzahl der Schocks und Richtungen nach Spezifikation', 'Number of shocks and directions as specified'],
                ['', 'Anwendung', 'Komplexe Stoßbelastungen, Pyroschock-Ersatz, Nachbildung gemessener Ereignisse', 'Complex shock loads, pyroshock substitute, replicating measured events'],
            ],
        ],

        /* ---------------------------------------------------------------- Allgemein */

        'din-en-60068-2-47' => [
            'params' => [['Inhalt', 'Befestigung und Messpunkte', 'Content', 'Mounting and measuring points'], ['Gilt für', 'Schwingen, Schock, Fallen', 'Applies to', 'Vibration, shock, drop']],
            'req' => [
                ['', 'Grundsatz', 'Befestigung wie im späteren Einsatz, mit denselben Befestigungspunkten und -mitteln', 'Mounting as in service, using the same fixing points and means'],
                ['', 'Vorrichtung', 'Möglichst steif und leicht; Eigenfrequenzen oberhalb des Prüffrequenzbereichs oder bekannt und berücksichtigt', 'As stiff and light as possible; natural frequencies above the test range or known and taken into account'],
                ['', 'Messpunkte', 'Begriffe Befestigungspunkt, Kontrollpunkt und Bezugspunkt; geregelt wird an der Schnittstelle zum Prüfling', 'Terms fixing point, check point and reference point; control is at the interface to the specimen'],
                ['', 'Verpackte Prüflinge', 'Prüfung im Transportbehälter möglich; Befestigung mit Gurten oder Rahmen beschreiben', 'Testing in the transport container is possible; describe fixing by straps or frames'],
                ['', 'Kabel und Leitungen', 'So führen und befestigen, dass sie die Bewegung nicht verfälschen', 'Route and fix so that they do not alter the motion'],
                ['', 'Dokumentation', 'Befestigung, Anzugsmomente und Lage der Aufnehmer im Prüfbericht festhalten', 'Record mounting, tightening torques and transducer positions in the test report'],
            ],
        ],

        'din-en-60068-3-8' => [
            'new' => [
                'code' => 'DIN EN 60068-3-8', 'category' => 'allgemein', 'sort' => 45,
                'title' => 'Umgebungseinflüsse – Unterstützende Dokumentation und Leitfaden – Auswahl von Schwingprüfverfahren',
                'excerpt' => 'Leitfaden für die Wahl zwischen Sinus, Random, Mixed Mode und Zeitverlauf.',
                'en' => ['title' => 'Environmental testing – Supporting documentation and guidance – Selecting amongst vibration tests', 'excerpt' => 'Guidance on choosing between sine, random, mixed mode and time history.'],
            ],
            'req' => [
                ['', 'Inhalt', 'Entscheidungshilfe: Sinus (2-6), Random (2-64), Mixed Mode (2-80) oder Zeitverlauf (2-57)', 'Decision aid: sine (2-6), random (2-64), mixed mode (2-80) or time history (2-57)'],
                ['', 'Grundlage', 'Umgebung aus Messdaten oder aus Klassen nach DIN EN 60721-3 beschreiben', 'Describe the environment from measured data or from classes to IEC 60721-3'],
                ['', 'Prüfzweck', 'Funktionsprüfung, Lebensdauernachweis, Qualifikation oder Screening unterscheiden', 'Distinguish functional test, durability proof, qualification or screening'],
                ['', 'Zeitraffung', 'Hinweise zum Verkürzen der Prüfdauer bei erhöhtem Pegel (ermüdungsäquivalent)', 'Guidance on shortening test time at increased level (fatigue-equivalent)'],
            ],
        ],

        'din-en-60721-3' => [
            'params' => [['Klassen', 'Lagerung, Transport, Einsatz', 'Classes', 'Storage, transport, use'], ['Mechanik', 'Klassen „M“', 'Mechanical', '“M” classes']],
            'req' => [
                ['', 'Teile', '3-1 Lagerung, 3-2 Transport, 3-3 ortsfest wettergeschützt, 3-4 ortsfest nicht wettergeschützt, 3-5 Landfahrzeuge, 3-6 Schiffe, 3-7 ortsveränderlicher Einsatz', '3-1 storage, 3-2 transportation, 3-3 stationary weatherprotected, 3-4 stationary non-weatherprotected, 3-5 ground vehicles, 3-6 ships, 3-7 portable use'],
                ['', 'Mechanische Klassen', 'Kennbuchstabe M mit Teilnummer, z. B. 2M für Transport oder 3M für ortsfesten Einsatz; höhere Ziffer bedeutet rauere Bedingungen', 'Letter M with part number, e.g. 2M for transportation or 3M for stationary use; a higher digit means harsher conditions'],
                ['', 'Prüfschärfen', 'Aus den Klassen werden Prüfschärfen für Sinus, Random und Schock nach DIN EN 60068 abgeleitet (Reihe IEC 60721-4)', 'Test severities for sine, random and shock to IEC 60068 are derived from the classes (series IEC 60721-4)'],
                ['', 'Neue Ausgaben', 'Die Teile wurden ab 2019 überarbeitet, Klassenbezeichnungen haben sich teils geändert – Ausgabe in der Spezifikation beachten', 'The parts have been revised since 2019 and some class designations changed – check the edition in the specification'],
            ],
        ],

        'din-en-60068-3-3' => [
            'new' => [
                'code' => 'DIN EN 60068-3-3', 'category' => 'bauwerk', 'sort' => 195,
                'title' => 'Umgebungseinflüsse – Leitfaden – Seismische Prüfverfahren für Geräte',
                'excerpt' => 'Erdbebenqualifikation von Geräten und Anlagenteilen mit Zeitverläufen, Sinusschwebungen und Antwortspektren.',
                'en' => ['title' => 'Environmental testing – Guidance – Seismic test methods for equipment', 'excerpt' => 'Seismic qualification of equipment with time histories, sine beats and response spectra.'],
            ],
            'req' => [
                ['', 'Ziel', 'Nachweis, dass Geräte (z. B. Schaltanlagen, Steuerungen) ein Erdbeben ohne Funktionsverlust überstehen', 'Proof that equipment (e.g. switchgear, control systems) withstands an earthquake without loss of function'],
                ['', 'Verfahren', 'Zeitverlauf, Sinusschwebungen oder Sinus-Verweilprüfungen; Bewertung über Antwortspektren', 'Time history, sine beats or sine dwells; assessment via response spectra'],
                ['', 'Vorgaben', 'Gefordertes Antwortspektrum, Dämpfung, Dauer der Starkbebenphase, ein- oder mehrachsige Anregung', 'Required response spectrum, damping, duration of the strong-motion part, single- or multi-axis excitation'],
                ['', 'Ablauf', 'Resonanzsuche, dann die festgelegten Bebenbelastungen; Funktion während und nach der Prüfung überwachen', 'Resonance search, then the specified seismic loads; monitor function during and after the test'],
                ['', 'Verwandte Normen', 'DIN EN 60068-2-57, IEC 60255-21-3 (Relais), IEEE 344 (kerntechnische Anlagen)', 'IEC 60068-2-57, IEC 60255-21-3 (relays), IEEE 344 (nuclear facilities)'],
            ],
        ],

        'din-en-60255-21' => [
            'new' => [
                'code' => 'DIN EN 60255-21-1/-2/-3', 'category' => 'allgemein', 'sort' => 265,
                'title' => 'Elektrische Relais – Schwing-, Schock-, Dauerschock- und Erdbebenprüfungen an Messrelais und Schutzeinrichtungen',
                'excerpt' => 'Typprüfungen für Schutzrelais und Schutzgeräte in Schärfeklassen 0 bis 2.',
                'en' => ['title' => 'Electrical relays – Vibration, shock, bump and seismic tests on measuring relays and protection equipment', 'excerpt' => 'Type tests for protection relays and equipment in severity classes 0 to 2.'],
            ],
            'params' => [['Teile', 'Schwingen, Schock, Erdbeben', 'Parts', 'Vibration, shock, seismic'], ['Klassen', '0, 1, 2', 'Classes', '0, 1, 2']],
            'req' => [
                ['', 'Teile', '-1 Schwingen (Sinus), -2 Schocken und Dauerschocken, -3 Erdbebenprüfungen', '-1 vibration (sine), -2 shock and bump, -3 seismic tests'],
                ['', 'Prüfarten', 'Funktionsprüfung (Gerät in Betrieb) und Festigkeitsprüfung (Gerät ohne Betrieb)', 'Response test (equipment operating) and endurance/withstand test (not operating)'],
                ['', 'Schärfeklassen', 'Klasse 0 ohne Anforderung, Klasse 1 für übliche Einsatzbedingungen, Klasse 2 für erhöhte Anforderungen (z. B. starke Erschütterungen, Schiffe)', 'Class 0 no requirement, class 1 for normal service conditions, class 2 for increased requirements (e.g. severe vibration, ships)'],
                ['', 'Schwingen (Teil 1), Klasse 2', 'Funktion: 10–150 Hz, 0,075 mm unterhalb und 1 g oberhalb der Übergangsfrequenz; Festigkeit: 2 g, 20 Sweep-Zyklen je Achse', 'Response: 10–150 Hz, 0.075 mm below and 1 g above the crossover frequency; endurance: 2 g, 20 sweep cycles per axis'],
                ['', 'Schocken (Teil 2), Klasse 1', 'Funktion 5 g / 11 ms, Festigkeit 15 g / 11 ms, Dauerschocken 10 g / 16 ms mit 1000 Schocks je Richtung', 'Response 5 g / 11 ms, withstand 15 g / 11 ms, bump 10 g / 16 ms with 1000 bumps per direction'],
                ['', 'Hinweis', 'Eine zusammengefasste Neuausgabe IEC 60255-21 ist in Arbeit', 'A consolidated new edition IEC 60255-21 is in preparation'],
            ],
        ],

        'vdi-2062' => [
            'params' => [['Isolierung ab', 'f/f0 > √2', 'Isolation above', 'f/f0 > √2'], ['Blätter', '1 und 2', 'Parts', '1 and 2']],
            'req' => [
                ['', 'Grundprinzip', 'Isolierwirkung erst oberhalb des Abstimmverhältnisses f/f0 = √2; je größer das Verhältnis, desto besser die Isolierung', 'Isolation only above the frequency ratio f/f0 = √2; the larger the ratio, the better the isolation'],
                ['', 'Auslegung', 'Abstimmverhältnis in der Praxis meist 2,5 bis 4; Eigenfrequenz der Lagerung entsprechend tief legen', 'Frequency ratio in practice mostly 2.5 to 4; set the natural frequency of the mounting low accordingly'],
                ['', 'Dämpfung', 'Mehr Dämpfung senkt die Überhöhung in der Resonanz, verschlechtert aber die Isolierung oberhalb von √2', 'More damping reduces the amplification at resonance but degrades isolation above √2'],
                ['', 'Arten', 'Aktive Isolierung (an der Quelle) und passive Isolierung (am Empfänger); Federn aus Stahl, Elastomer oder Luft', 'Active isolation (at the source) and passive isolation (at the receiver); steel, elastomer or air springs'],
                ['', 'Blätter', 'Blatt 1: Begriffe und Methoden; Blatt 2: Isolierelemente', 'Part 1: terms and methods; part 2: isolation elements'],
                ['', 'Rechner', 'Isolierwirkung im vib-hub Rechner „Schwingungsisolierung“ abschätzen', 'Estimate the isolation with the vib-hub “Vibration isolation” calculator'],
            ],
        ],

        'vdi-3830' => [
            'params' => [['Kenngrößen', 'η, D, Λ, Q', 'Parameters', 'η, D, Λ, Q']],
            'req' => [
                ['', 'Inhalt', 'Kenngrößen der Dämpfung (Verlustfaktor, Lehrsches Dämpfungsmaß, logarithmisches Dekrement, Güte) und deren Messung', 'Damping parameters (loss factor, damping ratio, logarithmic decrement, quality factor) and their measurement'],
                ['', 'Umrechnung', 'Bei kleiner Dämpfung gilt η ≈ 2D ≈ 1/Q und Λ ≈ 2πD', 'For small damping η ≈ 2D ≈ 1/Q and Λ ≈ 2πD'],
                ['', 'Messverfahren', 'Ausschwingversuch, Halbwertsbreite der Resonanz, Hysterese aus Kraft und Weg', 'Decay test, half-power bandwidth of the resonance, hysteresis from force and displacement'],
                ['', 'Blätter', 'Mehrere Blätter von den Grundlagen über Werkstoff- und Bauteildämpfung bis zu den Messverfahren', 'Several parts from fundamentals through material and component damping to measuring methods'],
            ],
        ],

        /* ---------------------------------------------------------------- Bahn */

        'din-en-61373' => [
            'req' => [
                ['', 'Hinweis', 'IEC 61373 Ausgabe 3.0 ist im Juli 2026 erschienen. Die Werte unten stammen aus EN 61373:2010.', 'IEC 61373 edition 3.0 was published in July 2026. The values below are from EN 61373:2010.'],
                ['Einbaukategorien', 'Kategorie 1 Klasse A', 'Wagenkasten: Geräte direkt am oder unter dem Wagenkasten', 'Car body: equipment mounted directly on or under the car body'],
                ['Einbaukategorien', 'Kategorie 1 Klasse B', 'Wagenkasten: Geräte in einem Gehäuse, das am Wagenkasten befestigt ist', 'Car body: equipment inside a case that is mounted on the car body'],
                ['Einbaukategorien', 'Kategorie 2', 'Drehgestell', 'Bogie'],
                ['Einbaukategorien', 'Kategorie 3', 'Radsatz bzw. Achse', 'Wheelset or axle'],
                ['Funktionsprüfung (Random, Effektivwert)', 'Kategorie 1 A', 'vertikal 0,750 / quer 0,370 / längs 0,500 m/s², bis 150 Hz', 'vertical 0.750 / transverse 0.370 / longitudinal 0.500 m/s², up to 150 Hz'],
                ['Funktionsprüfung (Random, Effektivwert)', 'Kategorie 1 B', 'vertikal 1,01 / quer 0,450 / längs 0,700 m/s², bis 150 Hz', 'vertical 1.01 / transverse 0.450 / longitudinal 0.700 m/s², up to 150 Hz'],
                ['Funktionsprüfung (Random, Effektivwert)', 'Kategorie 2', 'vertikal 5,40 / quer 4,70 / längs 2,50 m/s², bis 250 Hz', 'vertical 5.40 / transverse 4.70 / longitudinal 2.50 m/s², up to 250 Hz'],
                ['Funktionsprüfung (Random, Effektivwert)', 'Kategorie 3', 'vertikal 38,0 / quer 34,0 / längs 17,0 m/s², 10–500 Hz', 'vertical 38.0 / transverse 34.0 / longitudinal 17.0 m/s², 10–500 Hz'],
                ['Simulierte Lebensdauerprüfung (Random, Effektivwert)', 'Dauer', '5 h je Achse, 15 h gesamt', '5 h per axis, 15 h in total'],
                ['Simulierte Lebensdauerprüfung (Random, Effektivwert)', 'Kategorie 1 A', 'vertikal 4,25 / quer 2,09 / längs 2,83 m/s²', 'vertical 4.25 / transverse 2.09 / longitudinal 2.83 m/s²'],
                ['Simulierte Lebensdauerprüfung (Random, Effektivwert)', 'Kategorie 1 B', 'vertikal 5,72 / quer 2,55 / längs 3,96 m/s²', 'vertical 5.72 / transverse 2.55 / longitudinal 3.96 m/s²'],
                ['Simulierte Lebensdauerprüfung (Random, Effektivwert)', 'Kategorie 2', 'vertikal 30,6 / quer 26,6 / längs 14,2 m/s²', 'vertical 30.6 / transverse 26.6 / longitudinal 14.2 m/s²'],
                ['Simulierte Lebensdauerprüfung (Random, Effektivwert)', 'Kategorie 3', 'vertikal 144 / quer 129 / längs 64,3 m/s²', 'vertical 144 / transverse 129 / longitudinal 64.3 m/s²'],
                ['Schock (Halbsinus)', 'Kategorie 1 A/B', 'vertikal und quer 30 m/s², längs 50 m/s², jeweils 30 ms', 'vertical and transverse 30 m/s², longitudinal 50 m/s², each 30 ms'],
                ['Schock (Halbsinus)', 'Kategorie 2', '300 m/s², 18 ms'],
                ['Schock (Halbsinus)', 'Kategorie 3', '1000 m/s², 6 ms'],
                ['Schock (Halbsinus)', 'Anzahl', '18 Schocks: je 3 in positiver und negativer Richtung jeder Achse', '18 shocks: 3 each in positive and negative direction of every axis'],
            ],
        ],

        'din-en-50155' => [
            'new' => [
                'code' => 'DIN EN 50155', 'category' => 'bahn', 'sort' => 85,
                'title' => 'Bahnanwendungen – Fahrzeuge – Elektronische Einrichtungen',
                'excerpt' => 'Produktnorm für Elektronik auf Schienenfahrzeugen; Schwingen und Schocken nach DIN EN 61373.',
                'en' => ['title' => 'Railway applications – Rolling stock – Electronic equipment', 'excerpt' => 'Product standard for electronics on rolling stock; vibration and shock to EN 61373.'],
            ],
            'req' => [
                ['', 'Schwingen und Schocken', 'Prüfung nach DIN EN 61373, Kategorie nach Einbauort im Fahrzeug', 'Testing to EN 61373, category according to the mounting location in the vehicle'],
                ['', 'Weitere Umweltprüfungen', 'Typprüfungen u. a. zu Temperatur, Feuchte, Isolation und EMV', 'Type tests including temperature, humidity, insulation and EMC'],
                ['', 'Grundsatz', 'Auslegung auf die Nutzungsdauer des Fahrzeugs mit nachgewiesener Zuverlässigkeit', 'Design for the service life of the vehicle with proven reliability'],
            ],
        ],

        /* ---------------------------------------------------------------- Fahrzeug */

        'iso-16750-3' => [
            'params' => [['Ausgabe', 'ISO 16750-3:2023', 'Edition', 'ISO 16750-3:2023'], ['Prüfungen', 'Schwingen, Schock, Fall', 'Tests', 'Vibration, shock, drop']],
            'req' => [
                ['', 'Ausgabe', '4. Ausgabe 2023 mit Prüfprofilen nach Einbauort, auch für Hybrid- und Elektrofahrzeuge', '4th edition 2023 with test profiles by mounting location, including hybrid and electric vehicles'],
                ['Schwingen', 'Prüfprofil', 'Nach Einbauort: Verbrennungsmotor, Getriebe, elektrische Antriebsmaschine, Karosserie (gefederte Masse), Rad und Radaufhängung (ungefederte Masse), Abgasanlage u. a.', 'By mounting location: combustion engine, gearbox, electric drive motor, vehicle body (sprung mass), wheel and suspension (unsprung mass), exhaust and others'],
                ['Schwingen', 'Signalart', 'Random, Sinus oder Sinus auf Random – oft kombiniert mit Temperaturzyklen', 'Random, sine or sine-on-random – often combined with temperature cycling'],
                ['Schwingen', 'Beispiel Karosserie (Pkw, Test IV)', 'Random 10–1000 Hz, 8 h je Achse', 'Random 10–1000 Hz, 8 h per axis'],
                ['Mechanischer Schock', 'Karosserie und Rahmen', 'Halbsinus 500 m/s², 6 ms, 10 Schocks je Richtung', 'Half-sine 500 m/s², 6 ms, 10 shocks per direction'],
                ['Mechanischer Schock', 'Türen und Klappen', 'Viele Schocks zur Nachbildung des Zuschlagens, Anzahl nach Einbauort', 'Many shocks simulating slamming, number according to mounting location'],
                ['Freier Fall', 'Prüfling', 'Fall auf harten Untergrund, Höhe nach Handhabung; verdeckte Schäden sind nicht zulässig', 'Drop onto a hard surface, height according to handling; hidden damage is not permitted'],
                ['', 'Weitere Prüfungen', 'Oberflächen- und Kratzfestigkeit, Steinschlag', 'Surface strength and scratch resistance, gravel bombardment'],
                ['', 'Funktionszustand', 'Betriebszustand während der Prüfung nach ISO 16750-1', 'Operating mode during the test according to ISO 16750-1'],
            ],
        ],

        /* ---------------------------------------------------------------- Luftfahrt, Raumfahrt & Militär */

        'mil-std-810-method-514' => [
            'params' => [['Ausgabe', 'MIL-STD-810H, Methode 514.8', 'Edition', 'MIL-STD-810H, method 514.8'], ['Ansatz', 'Tailoring', 'Approach', 'Tailoring']],
            'req' => [
                ['', 'Grundsatz', 'Tailoring: Prüfprofile aus dem Lebenszyklus (Transport, Betrieb, Lagerung) ableiten statt Pauschalwerte zu übernehmen', 'Tailoring: derive test profiles from the life cycle (transport, operation, storage) rather than using generic values'],
                ['', 'Verfahren', 'I allgemeine Schwingung, II lose Ladung, III Transport großer Baugruppen, IV Außenlasten an Luftfahrzeugen', 'I general vibration, II loose cargo, III large assembly transport, IV assembled aircraft stores'],
                ['', 'Mindest-Integritätsprüfung', 'Allgemeines Profil bis 2000 Hz, 7,7 grms, 1 h je Achse – wenn die Umgebung nicht bekannt ist', 'General profile up to 2000 Hz, 7.7 grms, 1 h per axis – if the environment is unknown'],
                ['', 'Beispielkategorien', 'Kategorie 4 Lkw/Anhänger mit gesicherter Ladung, Kategorie 14 Hubschrauber, Kategorie 24 Mindest-Integrität', 'Category 4 truck/trailer secured cargo, category 14 rotary wing aircraft, category 24 minimum integrity'],
                ['Toleranzen', 'ASD', '±3 dB bei mindestens 120 Freiheitsgraden', '±3 dB with at least 120 degrees of freedom'],
                ['', 'Achsen', 'Drei Achsen; Reihenfolge und Dauer nach Tailoring', 'Three axes; sequence and duration according to tailoring'],
            ],
        ],

        'mil-std-810-method-516' => [
            'params' => [['Ausgabe', 'MIL-STD-810H, Methode 516.8', 'Edition', 'MIL-STD-810H, method 516.8'], ['Verfahren', 'I bis VIII', 'Procedures', 'I to VIII']],
            'req' => [
                ['', 'Verfahren', 'I Funktionsschock, II Transportschock, III Fragilität, IV Transportfall, V Crash Hazard, VI Bench Handling, VII Pendelschlag, VIII Katapultstart und Fanglandung', 'I functional shock, II transportation shock, III fragility, IV transit drop, V crash hazard, VI bench handling, VII pendulum impact, VIII catapult launch and arrested landing'],
                ['', 'Funktionsschock (I)', 'Sägezahn mit Endspitze, 20 g / 11 ms für Fluggerät, 40 g / 11 ms für Bodengerät', 'Terminal-peak sawtooth, 20 g / 11 ms for flight equipment, 40 g / 11 ms for ground equipment'],
                ['', 'Crash Hazard (V)', 'Sägezahn mit Endspitze, 40 g (Fluggerät) bzw. 75 g (Bodengerät), 11 ms', 'Terminal-peak sawtooth, 40 g (flight) or 75 g (ground), 11 ms'],
                ['', 'Anzahl', 'Funktionsschock üblicherweise 3 Schocks je Richtung, 18 gesamt', 'Functional shock usually 3 shocks per direction, 18 in total'],
                ['', 'Transportfall (IV)', 'Fallhöhe und Anzahl nach Masse und Transportart, z. B. 26 Fälle für von Hand getragene Geräte', 'Drop height and number according to mass and transport mode, e.g. 26 drops for manually handled items'],
                ['', 'Bench Handling (VI)', 'Kippen über die Kanten aus 100 mm Höhe bzw. 45° auf eine Werkbank', 'Tipping over the edges from 100 mm height or 45° onto a bench'],
                ['', 'Alternative', 'Schockantwortspektrum (SRS) aus Messdaten statt klassischer Pulsform', 'Shock response spectrum (SRS) from measured data instead of a classical pulse'],
            ],
        ],

        'rtca-do-160' => [
            'params' => [['Abschnitte', '7 Schock, 8 Schwingen', 'Sections', '7 shock, 8 vibration'], ['Kategorien Abschnitt 8', 'S, R, U, U2, H, Z', 'Section 8 categories', 'S, R, U, U2, H, Z']],
            'req' => [
                ['Abschnitt 7 – Schock und Crash', 'Betriebsschock', '6 g, Sägezahn mit Endspitze, 11 ms (Standard) bzw. 20 ms (niederfrequent)', '6 g, terminal-peak sawtooth, 11 ms (standard) or 20 ms (low frequency)'],
                ['Abschnitt 7 – Schock und Crash', 'Crash Safety', 'Impuls 20 g (11 ms bzw. 20 ms); Dauerbeschleunigung je nach Richtung und Luftfahrzeug, z. B. 9 g über 3 s', 'Impulse 20 g (11 ms or 20 ms); sustained acceleration depending on direction and aircraft, e.g. 9 g for 3 s'],
                ['Abschnitt 8 – Schwingen', 'Kategorien', 'S Standard, R/U/U2 robust, H/Z hoher Pegel mit kurzer Dauer', 'S standard, R/U/U2 robust, H/Z high level with short duration'],
                ['Abschnitt 8 – Schwingen', 'Prüfkurven', 'Nach Luftfahrzeugtyp (Strahl-, Propellerflugzeug, Hubschrauber) und Einbauzone', 'According to aircraft type (jet, propeller, helicopter) and installation zone'],
                ['Abschnitt 8 – Schwingen', 'Verfahren', 'Sinus (Resonanzsuche und Verweilen) oder Random; Hubschrauber mit Sinus auf Random', 'Sine (resonance search and dwell) or random; helicopters with sine-on-random'],
                ['Abschnitt 8 – Schwingen', 'Dauer', 'Kategorie R Flächenflugzeug, Sinus: 3 h je Achse zuzüglich Verweilen', 'Category R fixed wing, sine: 3 h per axis plus dwells'],
                ['Toleranzen', 'ASD', '+3/−1,5 dB unter 500 Hz, ±3 dB darüber; mindestens 100 Freiheitsgrade', '+3/−1.5 dB below 500 Hz, ±3 dB above; at least 100 degrees of freedom'],
            ],
        ],

        'gsfc-std-7000-gevs' => [
            'new' => [
                'code' => 'GSFC-STD-7000 (GEVS)', 'category' => 'luftfahrt', 'sort' => 125,
                'title' => 'General Environmental Verification Standard (GEVS) für Raumfahrtprojekte der NASA GSFC',
                'excerpt' => 'Umweltnachweis für Raumfahrt-Hardware: Random-Qualifikation, Abnahme, Workmanship, Sinus, Akustik und Schock.',
                'en' => ['title' => 'General Environmental Verification Standard (GEVS) for NASA GSFC flight programs and projects', 'excerpt' => 'Environmental verification of spaceflight hardware: random qualification, acceptance, workmanship, sine, acoustics and shock.'],
            ],
            'params' => [['Qualifikation', '14,1 grms, 2 min je Achse', 'Qualification', '14.1 grms, 2 min per axis'], ['Workmanship', '6,8 grms', 'Workmanship', '6.8 grms']],
            'req' => [
                ['Komponenten-Random', 'Qualifikation', '14,1 grms, 20–2000 Hz, 2 min je Achse', '14.1 grms, 20–2000 Hz, 2 min per axis'],
                ['Komponenten-Random', 'Abnahme (Flughardware)', 'Qualifikationspegel −3 dB (rund 10 grms), 1 min je Achse', 'Qualification level −3 dB (about 10 grms), 1 min per axis'],
                ['Komponenten-Random', 'Protoflight', 'Qualifikationspegel mit Abnahmedauer', 'Qualification level with acceptance duration'],
                ['Komponenten-Random', 'Mindest-Workmanship', '6,8 grms: 20 Hz 0,01 g²/Hz, +3 dB/Okt bis 80 Hz, 0,04 g²/Hz bis 500 Hz, −3 dB/Okt bis 2000 Hz (wie NASA-STD-7001B)', '6.8 grms: 20 Hz 0.01 g²/Hz, +3 dB/oct to 80 Hz, 0.04 g²/Hz to 500 Hz, −3 dB/oct to 2000 Hz (as NASA-STD-7001B)'],
                ['', 'Zuschlag', 'Qualifikation = höchste erwartete Flugumgebung + 3 dB', 'Qualification = maximum expected flight environment + 3 dB'],
                ['', 'Weitere Prüfungen', 'Niederfrequenter Sinus, Akustik, Schock (z. B. Pyro), Modalprüfung', 'Low-frequency sine, acoustics, shock (e.g. pyro), modal survey'],
                ['', 'Simulator', 'Profil „NASA GEVS“ im vib-hub Regler-Simulator auswählbar', 'Profile “NASA GEVS” can be selected in the vib-hub controller simulator'],
            ],
        ],

        'mil-std-167-1a' => [
            'new' => [
                'code' => 'MIL-STD-167-1A', 'category' => 'luftfahrt', 'sort' => 115,
                'title' => 'Mechanical Vibrations of Shipboard Equipment (Type I – Environmental, Type II – Internally Excited)',
                'excerpt' => 'Schwingprüfung für Geräte an Bord von Marineschiffen: 4–33 Hz Sinus mit Erkundung, variabler Frequenz und Dauerprüfung.',
                'en' => ['excerpt' => 'Vibration testing of equipment on board naval ships: 4–33 Hz sine with exploratory, variable frequency and endurance tests.'],
            ],
            'params' => [['Frequenzbereich', '4–33 Hz', 'Frequency range', '4–33 Hz'], ['Dauerprüfung', '2 h je Achse', 'Endurance', '2 h per axis']],
            'req' => [
                ['Typ I – Umgebungsschwingung', 'Frequenzbereich', '4–33 Hz, Sinus, jede Achse getrennt', '4–33 Hz, sine, each axis separately'],
                ['Typ I – Umgebungsschwingung', 'Erkundungsprüfung', '4–33 Hz in 1-Hz-Schritten mit je etwa 15 s bei 0,010 ± 0,002 inch (rund 0,25 mm) Einzelamplitude', '4–33 Hz in 1 Hz steps of about 15 s each at 0.010 ± 0.002 inch (about 0.25 mm) single amplitude'],
                ['Typ I – Umgebungsschwingung', 'Variable Frequenz', '4–33 Hz in 1-Hz-Schritten je 5 min; Amplitude 4–15 Hz 0,030 inch (0,76 mm), 16–25 Hz 0,020 inch (0,51 mm), 26–33 Hz 0,010 inch (0,25 mm)', '4–33 Hz in 1 Hz steps of 5 min each; amplitude 4–15 Hz 0.030 inch (0.76 mm), 16–25 Hz 0.020 inch (0.51 mm), 26–33 Hz 0.010 inch (0.25 mm)'],
                ['Typ I – Umgebungsschwingung', 'Dauerprüfung', '2 h je Achse bei der Resonanz (ohne Resonanz bei 33 Hz); bei mehreren Frequenzen aufgeteilt, je mindestens 40 min', '2 h per axis at the resonance (at 33 Hz if none); split over several frequencies with at least 40 min each'],
                ['Typ II – innere Anregung', 'Inhalt', 'Auswuchten und zulässige Schwingungen rotierender Maschinen an Bord', 'Balancing and permissible vibration of rotating machinery on board'],
                ['', 'Abgrenzung', 'Schockfestigkeit wird separat nach MIL-DTL-901E nachgewiesen', 'Shock resistance is proven separately to MIL-DTL-901E'],
            ],
        ],

        /* ---------------------------------------------------------------- Verpackung & Transport */

        'un-38-3' => [
            'new' => [
                'code' => 'UN 38.3', 'category' => 'transport', 'sort' => 128,
                'title' => 'UN-Handbuch Prüfungen und Kriterien, Teil III, Abschnitt 38.3 – Lithium-Zellen und -Batterien',
                'excerpt' => 'Pflichtprüfungen für den Transport von Lithiumbatterien, darunter T3 Schwingung und T4 Schock.',
                'en' => ['title' => 'UN Manual of Tests and Criteria, Part III, Section 38.3 – Lithium cells and batteries', 'excerpt' => 'Mandatory tests for transporting lithium batteries, including T3 vibration and T4 shock.'],
            ],
            'params' => [['T3', 'Sinus 7–200 Hz, 3 h je Achse', 'T3', 'Sine 7–200 Hz, 3 h per axis'], ['T4', 'Halbsinus 150 g / 6 ms bzw. 50 g / 11 ms', 'T4', 'Half-sine 150 g / 6 ms or 50 g / 11 ms']],
            'req' => [
                ['T3 Schwingung', 'Signal', 'Sinus, logarithmischer Sweep 7 → 200 → 7 Hz in 15 min, 12 Zyklen = 3 h je Achse, drei Achsen', 'Sine, logarithmic sweep 7 → 200 → 7 Hz in 15 min, 12 cycles = 3 h per axis, three axes'],
                ['T3 Schwingung', 'Pegel', '7–18 Hz 1 gn; dann 1,6 mm Doppelamplitude bis etwa 50 Hz; 50–200 Hz 8 gn (große Batterien über 12 kg: höchstens 2 gn)', '7–18 Hz 1 gn; then 1.6 mm peak-to-peak up to about 50 Hz; 50–200 Hz 8 gn (large batteries above 12 kg: max. 2 gn)'],
                ['T4 Schock', 'Puls', 'Halbsinus 150 gn / 6 ms für kleine Zellen und Batterien, 50 gn / 11 ms für große', 'Half-sine 150 gn / 6 ms for small cells and batteries, 50 gn / 11 ms for large ones'],
                ['T4 Schock', 'Anzahl', '3 Schocks je Richtung, 18 gesamt', '3 shocks per direction, 18 in total'],
                ['', 'Reihenfolge', 'T1 bis T5 nacheinander an denselben Prüflingen', 'T1 to T5 in sequence on the same samples'],
                ['', 'Kriterien', 'Kein Leck, kein Abblasen, keine Zerlegung, kein Bersten, kein Brand; Leerlaufspannung mindestens 90 % des Ausgangswerts', 'No leakage, venting, disassembly, rupture or fire; open-circuit voltage at least 90 % of the initial value'],
            ],
        ],

        'din-en-iso-13355' => [
            'params' => [['Anregung', 'vertikal, Random', 'Excitation', 'vertical, random'], ['Mindestdauer', '30 min je Lage', 'Minimum duration', '30 min per attitude']],
            'req' => [
                ['', 'Ausgabe', 'ISO 13355:2016'],
                ['', 'Anregung', 'Vertikales Random auf versandfertige Packstücke und Ladeeinheiten', 'Vertical random vibration of complete, filled transport packages and unit loads'],
                ['', 'Allgemeines Profil', 'Generisches ASD der Norm mit rund 0,6 g Effektivwert; Profile aus eigenen Messdaten sind zulässig', 'Generic ASD of the standard with about 0.6 g rms; profiles from own measured data are permitted'],
                ['', 'Prüfdauer', 'Mindestens 30 min je Prüflage empfohlen', 'At least 30 min per test attitude recommended'],
                ['Toleranzen', 'Effektivwert', '±15 %'],
                ['Toleranzen', 'ASD', '±3 dB im gesamten Frequenzband', '±3 dB across the frequency band'],
                ['Toleranzen', 'Querbewegung', 'Horizontale Anteile höchstens 20 % der vertikalen', 'Horizontal components at most 20 % of the vertical'],
            ],
        ],

        'din-en-60068-2-55' => [
            'new' => [
                'code' => 'DIN EN 60068-2-55', 'category' => 'transport', 'sort' => 132,
                'title' => 'Umgebungseinflüsse – Prüfung Ee und Leitfaden: Prüfung loser Ladung einschließlich Bounce',
                'excerpt' => 'Ungesicherte Ladung: der Prüfling liegt lose auf dem Prüftisch und springt.',
                'en' => ['title' => 'Environmental testing – Test Ee and guidance: Loose cargo testing including bounce', 'excerpt' => 'Unsecured cargo: the specimen lies loose on the test table and bounces.'],
            ],
            'req' => [
                ['', 'Prinzip', 'Prüfling liegt ungesichert in einer Einfassung auf der Prüffläche und hebt bei der Bewegung ab', 'The specimen lies unsecured inside a fence on the test surface and lifts off during the motion'],
                ['', 'Anregung', 'Kreisförmige (rotierende) oder vertikal sinusförmige Bewegung mit Weg und Frequenz, bei denen der Prüfling springt', 'Circular (rotary) or vertical sinusoidal motion with a displacement and frequency at which the specimen bounces'],
                ['', 'Dauer', 'Nach Spezifikation, aus Transportweg und Fahrzeug abgeleitet', 'As specified, derived from transport route and vehicle'],
                ['', 'Anwendung', 'Lose Ladung auf Lkw-Ladeflächen, unbefestigte Geräte in Fahrzeugen', 'Loose cargo on truck beds, unfixed equipment in vehicles'],
            ],
        ],

        'astm-d4169' => [
            'params' => [['Distributionszyklen', 'DC 1 bis DC 18', 'Distribution cycles', 'DC 1 to DC 18'], ['Sicherheitsstufen', 'I, II, III', 'Assurance levels', 'I, II, III']],
            'req' => [
                ['', 'Aufbau', 'Der Distributionszyklus (DC) legt die Folge der Prüfungen fest, die Sicherheitsstufe (Assurance Level I–III) deren Intensität', 'The distribution cycle (DC) defines the sequence of tests, the assurance level (I–III) their intensity'],
                ['', 'Prüfungen (Schedules)', 'A Handhabung, B Lagerstapelung, C Stapelung im Fahrzeug, D gestapelte Schwingung, E Fahrzeugschwingung, F lose Ladung, G Rangierstoß, H Klima, I Unterdruck, J konzentrierter Stoß', 'A handling, B warehouse stacking, C vehicle stacking, D stacked vibration, E vehicle vibration, F loose-load vibration, G rail switching, H environmental hazard, I low pressure, J concentrated impact'],
                ['', 'Lkw-Random', 'Seit 2016 Schleife aus drei Stufen je Stunde: 0,40 grms 40 min, 0,54 grms 15 min, 0,70 grms 5 min', 'Since 2016 a loop of three levels per hour: 0.40 grms 40 min, 0.54 grms 15 min, 0.70 grms 5 min'],
                ['', 'Bewertung', 'Abnahmekriterien für Produkt- und Verpackungsschäden legt der Anwender vorab fest', 'Acceptance criteria for product and package damage are defined by the user beforehand'],
            ],
        ],

        'astm-d4728' => [
            'new' => [
                'code' => 'ASTM D4728', 'category' => 'transport', 'sort' => 145,
                'title' => 'Standard Test Method for Random Vibration Testing of Shipping Containers',
                'excerpt' => 'Prüfmethode für Random-Schwingungen an Versandeinheiten – Grundlage für ASTM D4169 und viele ISTA-Verfahren.',
                'en' => ['excerpt' => 'Test method for random vibration of shipping units – basis for ASTM D4169 and many ISTA procedures.'],
            ],
            'req' => [
                ['', 'Verfahren', 'Vertikales Random mit vorgegebenem ASD; Profile aus Messdaten oder aus Normen wie ASTM D4169 und ISTA', 'Vertical random with a specified ASD; profiles from measured data or from standards such as ASTM D4169 and ISTA'],
                ['', 'Signal', 'Gaußverteiltes Rauschen; Begrenzung des Ansteuersignals nur nach Absprache', 'Gaussian random; limiting the drive signal only by agreement'],
                ['', 'Angaben', 'ASD, Frequenzbereich, Gesamt-grms, Dauer, Auflast und Prüflage festlegen', 'Specify ASD, frequency range, overall grms, duration, top load and test orientation'],
                ['', 'Ziel', 'Schwachstellen von Verpackung und Produkt sowie Resonanzen finden', 'Find weaknesses of package and product as well as resonances'],
            ],
        ],

        'ista-3a' => [
            'params' => [['Anwendung', 'Paketdienst, bis 70 kg', 'Application', 'Parcel delivery, up to 70 kg'], ['Random', '0,53 / 0,46 grms', 'Random', '0.53 / 0.46 grms']],
            'req' => [
                ['', 'Ausgabe', 'ISTA 3A (2018)'],
                ['', 'Anwendung', 'Einzelpakete im Paketdienst bis 70 kg (150 lb)', 'Individual packages in parcel delivery up to 70 kg (150 lb)'],
                ['', 'Ablauf', 'Klimatisierung, Fallprüfungen, Random-Schwingung, optional Unterdruck, abschließende Fallprüfungen', 'Conditioning, drops, random vibration, optional low pressure, final drops'],
                ['', 'Schwingung', 'Random mit und ohne Auflast, Gesamtpegel 0,53 und 0,46 grms', 'Random with and without top load, overall levels 0.53 and 0.46 grms'],
                ['', 'Fall', 'Mehrere Fallserien, z. B. 9 Fälle am Anfang und 8 am Ende einschließlich Fall auf ein Hindernis; Höhe abhängig vom Gewicht', 'Several drop sequences, e.g. 9 drops at the start and 8 at the end including a drop onto a hazard; height depends on weight'],
                ['', 'Pakettypen', 'Standard, klein, flach und länglich – jeweils mit eigener Prüffolge', 'Standard, small, flat and elongated – each with its own test sequence'],
            ],
        ],

        /* ---------------------------------------------------------------- Maschinen */

        'iso-20816-1' => [
            'req' => [
                ['', 'Messgröße', 'Breitbandiger Effektivwert der Schwinggeschwindigkeit in mm/s an nicht rotierenden Teilen (Lagergehäuse); ergänzend Wellenschwingungen', 'Broadband rms vibration velocity in mm/s on non-rotating parts (bearing housings); additionally shaft vibration'],
                ['', 'Frequenzbereich', 'Typisch 10–1000 Hz, bei langsam laufenden Maschinen ab 2 Hz', 'Typically 10–1000 Hz, from 2 Hz for slow-running machines'],
                ['', 'Zonen', 'A: neu in Betrieb genommen; B: langfristig zulässig; C: nur begrenzt zulässig; D: Schäden möglich', 'A: newly commissioned; B: acceptable long-term; C: acceptable for a limited time; D: damage possible'],
                ['', 'Alarm und Abschaltung', 'Alarm meist an der Grenze B/C ausgerichtet, Abschaltung an C/D – maschinenspezifisch festlegen', 'Alarm usually aligned with the B/C boundary, trip with C/D – set specifically for the machine'],
                ['', 'Änderungen', 'Deutliche Änderungen des Schwingungsniveaus auch innerhalb einer Zone bewerten', 'Assess significant changes of the vibration level even within a zone'],
                ['', 'Messpunkte', 'Radial in zwei Richtungen an jedem Lager, axial an Axiallagern', 'Radially in two directions at each bearing, axially at thrust bearings'],
            ],
        ],

        'iso-20816-3' => [
            'params' => [['Leistung', 'über 15 kW', 'Power', 'above 15 kW'], ['Drehzahl', '120–30000 min⁻¹', 'Speed', '120–30000 r/min']],
            'req' => [
                ['', 'Anwendung', 'Industriemaschinen über 15 kW mit 120–30000 min⁻¹; ersetzt ISO 10816-3 und ISO 7919-3', 'Industrial machines above 15 kW at 120–30000 r/min; supersedes ISO 10816-3 and ISO 7919-3'],
                ['', 'Gruppe 1', 'Über 300 kW bzw. elektrische Maschinen mit Achshöhe ab 315 mm', 'Above 300 kW or electrical machines with shaft height from 315 mm'],
                ['', 'Gruppe 2', '15–300 kW bzw. Achshöhe 160 bis unter 315 mm', '15–300 kW or shaft height 160 to below 315 mm'],
                ['', 'Zonengrenzen Gruppe 1', 'Starr: A/B 2,3 – B/C 4,5 – C/D 7,1 mm/s; nachgiebig: 3,5 – 7,1 – 11 mm/s', 'Rigid: A/B 2.3 – B/C 4.5 – C/D 7.1 mm/s; flexible: 3.5 – 7.1 – 11 mm/s'],
                ['', 'Zonengrenzen Gruppe 2', 'Starr: A/B 1,4 – B/C 2,8 – C/D 4,5 mm/s; nachgiebig: 2,3 – 4,5 – 7,1 mm/s', 'Rigid: A/B 1.4 – B/C 2.8 – C/D 4.5 mm/s; flexible: 2.3 – 4.5 – 7.1 mm/s'],
                ['', 'Starr oder nachgiebig', 'Starr, wenn die tiefste Eigenfrequenz von Maschine und Aufstellung mindestens 25 % über der Hauptanregung (meist Drehfrequenz) liegt', 'Rigid if the lowest natural frequency of machine and support is at least 25 % above the main excitation (usually running speed)'],
                ['', 'Alarm', 'Grundwert + 25 % der Grenze B/C, in der Regel nicht über dem 1,25-Fachen dieser Grenze', 'Baseline + 25 % of the B/C boundary, normally not above 1.25 times this boundary'],
            ],
        ],

        'iso-13373-1' => [
            'new' => [
                'code' => 'ISO 13373-1', 'category' => 'maschinen', 'sort' => 185,
                'title' => 'Zustandsüberwachung und -diagnostik von Maschinen – Schwingungs-Zustandsüberwachung – Teil 1: Allgemeine Verfahren',
                'excerpt' => 'Vorgehen bei der schwingungsbasierten Zustandsüberwachung von Maschinen.',
                'en' => ['title' => 'Condition monitoring and diagnostics of machines – Vibration condition monitoring – Part 1: General procedures', 'excerpt' => 'Procedure for vibration-based condition monitoring of machines.'],
            ],
            'req' => [
                ['', 'Inhalt', 'Messgrößen, Messorte, Messintervalle und Trendbeobachtung für die Zustandsüberwachung', 'Measured quantities, locations, intervals and trending for condition monitoring'],
                ['', 'Messgröße', 'Weg, Geschwindigkeit oder Beschleunigung je nach Frequenzbereich und Fehlerart; Wellenschwingungen bei Gleitlagern', 'Displacement, velocity or acceleration depending on frequency range and fault type; shaft vibration for fluid-film bearings'],
                ['', 'Überwachung', 'Dauerhaft (online) oder periodisch (offline); Grundwerte im Gutzustand erfassen', 'Permanent (online) or periodic (offline); record baseline values in good condition'],
                ['', 'Weitere Teile', 'Teil 2: Signalverarbeitung und -darstellung; Teil 3: Leitfaden zur Diagnose', 'Part 2: processing and presentation of data; part 3: guidelines for diagnostics'],
            ],
        ],

        'iso-21940-11' => [
            'new' => [
                'code' => 'ISO 21940-11', 'category' => 'maschinen', 'sort' => 178,
                'title' => 'Mechanische Schwingungen – Auswuchten von Rotoren – Teil 11: Verfahren und Toleranzen für Rotoren mit starrem Verhalten',
                'excerpt' => 'Wuchtgütestufen G und zulässige Restunwucht starrer Rotoren (Nachfolger der ISO 1940-1).',
                'en' => ['title' => 'Mechanical vibration – Rotor balancing – Part 11: Procedures and tolerances for rotors with rigid behaviour', 'excerpt' => 'Balance quality grades G and permissible residual unbalance of rigid rotors (successor of ISO 1940-1).'],
            ],
            'params' => [['Kenngröße', 'Wuchtgütestufe G in mm/s', 'Parameter', 'Balance quality grade G in mm/s'], ['Ersetzt', 'ISO 1940-1', 'Supersedes', 'ISO 1940-1']],
            'req' => [
                ['', 'Wuchtgütestufe G', 'G = e_per · Ω in mm/s; zulässige spezifische Restunwucht e_per = G · 1000 / Ω in g·mm/kg', 'G = e_per · Ω in mm/s; permissible specific residual unbalance e_per = G · 1000 / Ω in g·mm/kg'],
                ['', 'Beispiel', 'G 2,5 bei 3000 min⁻¹ (Ω ≈ 314 s⁻¹): e_per ≈ 8 g·mm/kg', 'G 2.5 at 3000 r/min (Ω ≈ 314 s⁻¹): e_per ≈ 8 g·mm/kg'],
                ['', 'Typische Stufen', 'G 0,4–G 1 Präzisionsspindeln und Kreisel; G 2,5 Elektromotoren, Werkzeugmaschinenspindeln, Pumpen, Turbinen; G 6,3 allgemeiner Maschinenbau, Ventilatoren, Schwungräder; G 16 Gelenkwellen; G 40 Fahrzeugräder', 'G 0.4–G 1 precision spindles and gyroscopes; G 2.5 electric motors, machine-tool spindles, pumps, turbines; G 6.3 general machinery, fans, flywheels; G 16 drive shafts; G 40 vehicle wheels'],
                ['', 'Aufteilung', 'Zulässige Restunwucht auf die Ausgleichsebenen aufteilen', 'Allocate the permissible residual unbalance to the correction planes'],
                ['', 'Zusammenhang', 'Unwucht erzeugt Schwingungen mit Drehfrequenz (1×); die Maschine wird dann nach ISO 20816 bewertet', 'Unbalance causes vibration at running speed (1×); the machine is then assessed to ISO 20816'],
            ],
        ],

        /* ---------------------------------------------------------------- Bauwerke */

        'din-4150-2' => [
            'params' => [['Frequenzbereich', '1–80 Hz', 'Frequency range', '1–80 Hz'], ['Kenngröße', 'KB-Wert', 'Parameter', 'KB value']],
            'req' => [
                ['', 'Kenngrößen', 'KB_Fmax (maximale bewertete Schwingstärke) und KB_FTr (Beurteilungs-Schwingstärke), verglichen mit den Anhaltswerten Au, Ao und Ar', 'KB_Fmax (maximum weighted vibration level) and KB_FTr (rating level), compared with the guideline values Au, Ao and Ar'],
                ['', 'Anhaltswerte tags (Au / Ao / Ar)', 'Industriegebiet 0,4 / 6 / 0,2; Gewerbegebiet 0,3 / 6 / 0,15; Misch-, Dorf-, Kerngebiet 0,2 / 5 / 0,1; Wohngebiet 0,15 / 3 / 0,07; Krankenhaus, Kurgebiet 0,1 / 3 / 0,05', 'Industrial area 0.4 / 6 / 0.2; commercial area 0.3 / 6 / 0.15; mixed, village, core area 0.2 / 5 / 0.1; residential area 0.15 / 3 / 0.07; hospital, spa area 0.1 / 3 / 0.05'],
                ['', 'Anhaltswerte nachts (Au / Ao / Ar)', 'Industriegebiet 0,3 / 0,6 / 0,15; Gewerbegebiet 0,2 / 0,4 / 0,1; Misch-, Dorf-, Kerngebiet 0,15 / 0,3 / 0,07; Wohngebiet 0,1 / 0,2 / 0,05', 'Industrial area 0.3 / 0.6 / 0.15; commercial area 0.2 / 0.4 / 0.1; mixed, village, core area 0.15 / 0.3 / 0.07; residential area 0.1 / 0.2 / 0.05'],
                ['', 'Bewertung', 'KB_Fmax ≤ Au: eingehalten; KB_Fmax > Ao: nicht eingehalten; dazwischen muss KB_FTr ≤ Ar sein', 'KB_Fmax ≤ Au: met; KB_Fmax > Ao: not met; in between KB_FTr ≤ Ar is required'],
                ['', 'Beurteilungszeiten', 'Tags 6–22 Uhr, nachts 22–6 Uhr', 'Day 6 am – 10 pm, night 10 pm – 6 am'],
                ['', 'Baumaßnahmen', 'Für zeitlich begrenzte Baustellen gelten eigene, höhere Anhaltswerte', 'Separate, higher guideline values apply to temporary construction work'],
            ],
        ],

        'din-4150-3' => [
            'params' => [['Messgröße', 'Schwinggeschwindigkeit in mm/s', 'Measured quantity', 'Vibration velocity in mm/s'], ['Ausgabe', 'DIN 4150-3:2016-12', 'Edition', 'DIN 4150-3:2016-12']],
            'req' => [
                ['Kurzzeitige Erschütterungen', 'Fundament', 'Gewerbe- und Industriebauten: 20 mm/s (1–10 Hz), 20–40 mm/s (10–50 Hz), 40–50 mm/s (50–100 Hz); Wohngebäude: 5 / 5–15 / 15–20 mm/s; besonders empfindliche oder denkmalgeschützte Bauten: 3 / 3–8 / 8–10 mm/s', 'Commercial and industrial buildings: 20 mm/s (1–10 Hz), 20–40 mm/s (10–50 Hz), 40–50 mm/s (50–100 Hz); residential buildings: 5 / 5–15 / 15–20 mm/s; particularly sensitive or listed buildings: 3 / 3–8 / 8–10 mm/s'],
                ['Kurzzeitige Erschütterungen', 'Oberste Deckenebene, horizontal', 'Gewerbe 40 mm/s, Wohnen 15 mm/s, empfindlich 8 mm/s', 'Commercial 40 mm/s, residential 15 mm/s, sensitive 8 mm/s'],
                ['Kurzzeitige Erschütterungen', 'Decken, vertikal', '20 mm/s'],
                ['Dauererschütterungen', 'Oberste Deckenebene, horizontal', 'Gewerbe 10 mm/s, Wohnen 5 mm/s, empfindlich 2,5 mm/s', 'Commercial 10 mm/s, residential 5 mm/s, sensitive 2.5 mm/s'],
                ['Dauererschütterungen', 'Decken, vertikal', '10 mm/s'],
                ['', 'Messgröße', 'Größter Maximalwert der Schwinggeschwindigkeit der drei Komponenten; oberhalb 100 Hz mindestens die Werte für 100 Hz', 'Highest peak value of the vibration velocity of the three components; above 100 Hz at least the values for 100 Hz'],
                ['', 'Bedeutung', 'Werden die Anhaltswerte eingehalten, sind Schäden im Sinne einer Minderung des Gebrauchswerts nicht zu erwarten', 'If the guideline values are met, damage reducing the serviceability is not to be expected'],
            ],
        ],

        /* ---------------------------------------------------------------- Mensch */

        'iso-2631-1' => [
            'params' => [['Frequenzbereich', '0,5–80 Hz', 'Frequency range', '0.5–80 Hz'], ['Bewertung', 'Wk, Wd, Wf', 'Weighting', 'Wk, Wd, Wf']],
            'req' => [
                ['', 'Frequenzbereich', '0,5–80 Hz für Gesundheit, Komfort und Wahrnehmung; 0,1–0,5 Hz für Bewegungskrankheit', '0.5–80 Hz for health, comfort and perception; 0.1–0.5 Hz for motion sickness'],
                ['', 'Frequenzbewertung', 'Wk vertikal (z), Wd horizontal (x, y), Wf Bewegungskrankheit', 'Wk vertical (z), Wd horizontal (x, y), Wf motion sickness'],
                ['', 'Gesundheit', 'Faktoren 1,4 für x und y sowie 1,0 für z; Bewertung über die Tagesexposition', 'Factors 1.4 for x and y and 1.0 for z; assessment via daily exposure'],
                ['', 'Komfort (Richtwerte für aw)', 'unter 0,315 m/s² nicht unangenehm; 0,315–0,63 etwas unangenehm; 0,5–1,0 ziemlich unangenehm; 0,8–1,6 unangenehm; 1,25–2,5 sehr unangenehm; über 2 m/s² extrem unangenehm', 'below 0.315 m/s² not uncomfortable; 0.315–0.63 a little uncomfortable; 0.5–1.0 fairly uncomfortable; 0.8–1.6 uncomfortable; 1.25–2.5 very uncomfortable; above 2 m/s² extremely uncomfortable'],
                ['', 'Stöße', 'Bei Crest-Faktoren über 9 zusätzlich Schwingungsdosiswert (VDV) oder gleitender Effektivwert (MTVV)', 'For crest factors above 9 additionally the vibration dose value (VDV) or maximum transient vibration value (MTVV)'],
            ],
        ],

        'din-en-iso-5349-1' => [
            'params' => [['Bewertung', 'Wh', 'Weighting', 'Wh'], ['Kenngröße', 'A(8) in m/s²', 'Parameter', 'A(8) in m/s²']],
            'req' => [
                ['', 'Messgröße', 'Frequenzbewertete Beschleunigung in drei Achsen und daraus der Schwingungsgesamtwert ahv', 'Frequency-weighted acceleration in three axes and the resulting vibration total value ahv'],
                ['', 'Frequenzbewertung', 'Wh, Terzbänder 8–1000 Hz', 'Wh, one-third octave bands 8–1000 Hz'],
                ['', 'Tagesexposition', 'A(8) = ahv · √(T / 8 h)'],
                ['', 'Grenzwerte in Deutschland', 'LärmVibrationsArbSchV: Auslösewert A(8) = 2,5 m/s², Expositionsgrenzwert A(8) = 5 m/s²', 'German Noise and Vibration Ordinance: action value A(8) = 2.5 m/s², exposure limit A(8) = 5 m/s²'],
                ['', 'Teil 2', 'DIN EN ISO 5349-2: praktische Messung am Arbeitsplatz', 'ISO 5349-2: practical measurement at the workplace'],
            ],
        ],

        'vdi-2057' => [
            'params' => [['Blätter', 'Ganzkörper, Hand-Arm', 'Parts', 'Whole-body, hand-arm']],
            'req' => [
                ['', 'Blätter', 'Blatt 1: Ganzkörper-Schwingungen; Blatt 2: Hand-Arm-Schwingungen', 'Part 1: whole-body vibration; part 2: hand-arm vibration'],
                ['', 'Ganzkörper', 'Auslösewert A(8) = 0,5 m/s²; Expositionsgrenzwert 0,8 m/s² (z) bzw. 1,15 m/s² (x, y) nach LärmVibrationsArbSchV', 'Action value A(8) = 0.5 m/s²; exposure limit 0.8 m/s² (z) or 1.15 m/s² (x, y) according to the German Noise and Vibration Ordinance'],
                ['', 'Hand-Arm', 'Auslösewert A(8) = 2,5 m/s²; Expositionsgrenzwert 5 m/s²', 'Action value A(8) = 2.5 m/s²; exposure limit 5 m/s²'],
                ['', 'Bewertung', 'Frequenzbewertung und Tagesexposition wie in ISO 2631-1 und DIN EN ISO 5349-1', 'Frequency weighting and daily exposure as in ISO 2631-1 and ISO 5349-1'],
                ['', 'Messgeräte', 'Nach DIN EN ISO 8041-1', 'To ISO 8041-1'],
            ],
        ],

        'din-en-iso-8041-1' => [
            'new' => [
                'code' => 'DIN EN ISO 8041-1', 'category' => 'mensch', 'sort' => 225,
                'title' => 'Schwingungseinwirkung auf den Menschen – Messeinrichtung – Teil 1: Schwingungsmesser für allgemeine Anwendungen',
                'excerpt' => 'Anforderungen an Messgeräte für Ganzkörper-, Hand-Arm- und Gebäudeschwingungen.',
                'en' => ['title' => 'Human response to vibration – Measuring instrumentation – Part 1: General purpose vibration meters', 'excerpt' => 'Requirements for instruments measuring whole-body, hand-arm and building vibration.'],
            ],
            'req' => [
                ['', 'Inhalt', 'Anforderungen an Schwingungsmesser für Ganzkörper-, Hand-Arm- und Gebäudeschwingungen', 'Requirements for vibration meters for whole-body, hand-arm and building vibration'],
                ['', 'Frequenzbewertungen', 'Wb, Wc, Wd, We, Wf, Wh, Wj, Wk, Wm – passend zu ISO 2631 und DIN EN ISO 5349', 'Wb, Wc, Wd, We, Wf, Wh, Wj, Wk, Wm – matching ISO 2631 and ISO 5349'],
                ['', 'Prüfungen', 'Baumusterprüfung, regelmäßige Überprüfung und Funktionsprüfung vor Ort', 'Pattern evaluation, periodic verification and in-situ check'],
            ],
        ],

        /* ---------------------------------------------------------------- Kalibrierung */

        'iso-16063' => [
            'params' => [['Primär', 'Laserinterferometrie', 'Primary', 'Laser interferometry'], ['Sekundär', 'Vergleichskalibrierung', 'Secondary', 'Comparison calibration']],
            'req' => [
                ['', 'Teile', '-11 Primärkalibrierung Schwingung (Laserinterferometer), -13 Primärkalibrierung Schock, -21 Vergleichskalibrierung Schwingung, -22 Vergleichskalibrierung Schock', '-11 primary vibration calibration (laser interferometry), -13 primary shock calibration, -21 vibration calibration by comparison, -22 shock calibration by comparison'],
                ['', 'Kalibriergrößen', 'Übertragungsfaktor nach Betrag und Phase über der Frequenz, Linearität, Querempfindlichkeit', 'Sensitivity in magnitude and phase over frequency, linearity, transverse sensitivity'],
                ['', 'Referenzbedingungen', 'Übliche Referenzfrequenz z. B. 160 Hz', 'Common reference frequency e.g. 160 Hz'],
                ['', 'Rückführung', 'Vergleich gegen ein rückgeführt kalibriertes Referenznormal (Back-to-back)', 'Comparison against a traceably calibrated reference standard (back-to-back)'],
                ['', 'Messunsicherheit', 'Ermittlung nach GUM, Angabe im Kalibrierschein', 'Determined according to the GUM, stated in the calibration certificate'],
            ],
        ],

        'iso-5348' => [
            'new' => [
                'code' => 'ISO 5348', 'category' => 'kalibrierung', 'sort' => 252,
                'title' => 'Mechanische Schwingungen und Stöße – Mechanische Ankopplung von Beschleunigungsaufnehmern',
                'excerpt' => 'Wie die Befestigung eines Beschleunigungsaufnehmers den nutzbaren Frequenzbereich bestimmt.',
                'en' => ['title' => 'Mechanical vibration and shock – Mechanical mounting of accelerometers', 'excerpt' => 'How the mounting of an accelerometer determines its usable frequency range.'],
            ],
            'req' => [
                ['', 'Ziel', 'Die Ankopplung darf den Frequenzgang und die Messung nicht verfälschen', 'The mounting must not distort the frequency response and the measurement'],
                ['', 'Befestigung (von steif nach weich)', 'Stiftschraube auf plan bearbeiteter Fläche mit Koppelmittel, Klebstoff, Wachs bzw. Klebepad, Magnet, Tastspitze', 'Stud on a flat machined surface with coupling fluid, adhesive, wax or adhesive pad, magnet, hand-held probe'],
                ['', 'Einfluss', 'Je weicher die Ankopplung, desto tiefer die Montageresonanz und damit die obere Frequenzgrenze', 'The softer the mounting, the lower the mounted resonance and thus the upper frequency limit'],
                ['', 'Masse', 'Aufnehmermasse klein gegenüber der Messstelle halten (Massenbelastung)', 'Keep the transducer mass small compared with the measuring point (mass loading)'],
                ['', 'Kabel', 'Kabel nahe am Aufnehmer fixieren, um Kabelbewegung und Störsignale zu vermeiden', 'Fix the cable close to the transducer to avoid cable whip and spurious signals'],
            ],
        ],
    ],
];
