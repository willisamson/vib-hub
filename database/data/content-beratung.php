<?php

/*
 * Neuausrichtung (11/2026): vib-hub betreibt keinen eigenen Shaker.
 * Schwerpunkt ist Beratung und der resonanzfreie Aufbau von Schwingprüfanlagen beim Kunden.
 * Eingespielt von App\Support\ContentPatch (Migration und ContentSeeder).
 *
 * settings: [alter Standardtext, neuer Standardtext] – nur ersetzt, solange der alte Text unverändert ist.
 * services/trainings: Felder werden gesetzt; 'create' legt einen fehlenden Eintrag an.
 */

$partner = '<a href="https://rms-testsystems.de" target="_blank" rel="noopener">RMS Dynamic Test Systems</a>';

return [
    'settings' => [
        'hero_kicker' => ['Labor und Engineering für Schwingungstechnik', 'Beratung und Engineering für Schwingungstechnik'],
        'hero_kicker_en' => ['Laboratory and engineering for vibration technology', 'Consulting and engineering for vibration technology'],
        'hero_text' => [
            'Sinus, Random und Schock nach DIN EN 60068 – dazu Messung, Berechnung und Resonanzbeseitigung aus einer Hand. Wir machen sichtbar, was Ihr Produkt zum Schwingen bringt, und sorgen dafür, dass es hält.',
            'Wir planen Schwingprüfanlagen von Grund auf und bauen sie bei Ihnen resonanzfrei auf – von der Auslegung über Fundament und Vorrichtungen bis zur Abnahme. Dazu Beratung zu Sinus, Random und Schock, Messung, Berechnung und Resonanzbeseitigung.',
        ],
        'hero_text_en' => [
            'Sine, random and shock to IEC 60068 – plus measurement, calculation and resonance elimination from a single source. We reveal what makes your product vibrate and make sure it holds.',
            'We plan vibration test systems from the ground up and set them up resonance-free at your site – from sizing through foundation and fixtures to acceptance. Plus consulting on sine, random and shock, measurement, calculation and resonance elimination.',
        ],
        'tagline' => ['Schwingungsprüfung, Analyse und Resonanzbeseitigung', 'Prüfstandsplanung, Beratung und Resonanzbeseitigung'],
        'tagline_en' => ['Vibration testing, analysis and resonance elimination', 'Test system planning, consulting and resonance elimination'],
        'meta_description' => [
            'vib-hub – Schwingungsprüfung (Sinus, Random, Schock) nach DIN EN 60068, Resonanzbeseitigung, Berechnungen, Service und Schulungen rund um die Schwingungstechnik.',
            'vib-hub – Shaker-Prüfstände resonanzfrei planen und aufbauen, Beratung zu Sinus-, Random- und Schockprüfungen nach DIN EN 60068, Resonanzbeseitigung, Berechnungen und Schulungen.',
        ],
        'meta_description_en' => [
            'vib-hub – vibration testing (sine, random, shock) to IEC 60068, resonance elimination, calculations, field service and training in vibration engineering.',
            'vib-hub – planning and setting up resonance-free shaker test systems, consulting on sine, random and shock testing to IEC 60068, resonance elimination, calculations and training.',
        ],
        'footer_text' => [
            'Ingenieurdienstleistungen für Schwingungsprüfung, Resonanzanalyse und dynamische Auslegung.',
            'Ingenieurdienstleistungen für Prüfstandsplanung, Prüfberatung, Resonanzanalyse und dynamische Auslegung.',
        ],
        'footer_text_en' => [
            'Engineering services for vibration testing, resonance analysis and dynamic design.',
            'Engineering services for test system planning, test consulting, resonance analysis and dynamic design.',
        ],
        'stats' => [
            [
                ['value' => '20', 'suffix' => '–2000 Hz', 'label' => 'Typischer Prüffrequenzbereich'],
                ['value' => '3', 'suffix' => ' Achsen', 'label' => 'Prüfung in X, Y und Z'],
                ['value' => '30', 'suffix' => '+ Normen', 'label' => 'DIN, EN, ISO, IEC, MIL, VDI'],
                ['value' => '24', 'suffix' => ' h', 'label' => 'Rückmeldung auf Anfragen'],
            ],
            [
                ['value' => '1', 'suffix' => ' Ansprechpartner', 'label' => 'Von der Auslegung bis zur Abnahme'],
                ['value' => '3', 'suffix' => ' Achsen', 'label' => 'Aufbau für X, Y und Z mit Gleittisch'],
                ['value' => '40', 'suffix' => '+ Normen', 'label' => 'DIN, EN, ISO, IEC, MIL, VDI'],
                ['value' => '24', 'suffix' => ' h', 'label' => 'Rückmeldung auf Anfragen'],
            ],
        ],
    ],

    'services' => [
        'shaker-pruefstand-aufbauen' => [
            'create' => ['category' => 'pruefstand', 'icon' => 'fixture', 'demo' => 'isolation', 'is_featured' => true, 'sort' => 5],
            'title' => 'Shaker-Prüfstand resonanzfrei aufbauen',
            'excerpt' => 'Wir planen Ihre Schwingprüfanlage von Grund auf und bauen sie bei Ihnen auf – so, dass keine Resonanz von Fundament, Gleittisch oder Vorrichtung die Prüfung verfälscht.',
            'features' => ['Auslegung: Kraft, Hub, Geschwindigkeit, Gleittisch', 'Fundament, Seismikmasse und Isolierung zum Gebäude', 'Resonanzarme Kopfplatten und Vorrichtungen', 'Inbetriebnahme mit Abnahmemessung und Einweisung'],
            'body' => '<p>Eine Schwingprüfanlage ist mehr als Shaker und Verstärker. Ob die Regelung später sauber läuft, entscheidet sich beim Aufbau: Fundament, Kopplung zum Gebäude, Gleittisch, Kopfplatte, Vorrichtung und Messkette bilden zusammen ein schwingfähiges System. Liegt eine dieser Resonanzen im Prüfbereich, kämpft der Regler dagegen an – mit Abbrüchen, überhöhten Pegeln am Prüfling und ungültigen Prüfungen.</p>'
                .'<h3>So gehen wir vor</h3><ol>'
                .'<li><strong>Bedarf klären</strong> – Normen, Prüflinge, Massen, Profile und künftige Anforderungen</li>'
                .'<li><strong>Anlage auslegen</strong> – Kraft, Hub und Geschwindigkeit aus Sinus-, Random- und Schockprofilen; Gleittisch, Kühlung, Klimakammer</li>'
                .'<li><strong>Aufstellung planen</strong> – Fundament oder Seismikblock, Isolierung gegen das Gebäude (DIN 4150, VDI 2062), Platzbedarf und Medien</li>'
                .'<li><strong>Vorrichtungen auslegen</strong> – Kopfplatten und Adapter per FEM, erste Eigenfrequenz oberhalb des Prüfbereichs</li>'
                .'<li><strong>Messkette und Regelung</strong> – Aufnehmer und Ankopplung (ISO 5348), Kabelführung, Regelstrategie und Abbruchgrenzen</li>'
                .'<li><strong>Inbetriebnahme und Abnahme</strong> – Übertragungsfunktionen, Querbewegung, Leer- und Lastprofile, Dokumentation und Einweisung Ihres Teams</li>'
                .'</ol>'
                .'<h3>Neuanschaffung</h3><p>Für neue Anlagen empfehlen wir elektrodynamische Schwingprüfsysteme von unserem Partner '.$partner.' aus Reinbek. Wir stimmen die Auslegung mit Ihnen und dem Hersteller ab und begleiten Lieferung, Aufstellung und Abnahme. Bestehende Anlagen anderer Hersteller optimieren wir genauso.</p>'
                .'<p>Eine erste Abschätzung, welche Anlage zu Ihren Prüfprofilen passt, liefert unsere <a href="/shaker-auslegung">Shaker-Auslegung</a>. Unten können Sie ausprobieren, wie die Abstimmung der Lagerung die Übertragung ins Gebäude verändert.</p>',
            'en' => [
                'title' => 'Setting up a resonance-free shaker test system',
                'excerpt' => 'We plan your vibration test system from the ground up and set it up at your site – so that no resonance of foundation, slip table or fixture distorts the test.',
                'features' => ['Sizing: force, stroke, velocity, slip table', 'Foundation, seismic mass and isolation from the building', 'Low-resonance head expanders and fixtures', 'Commissioning with acceptance measurement and training'],
                'body' => '<p>A vibration test system is more than a shaker and an amplifier. Whether control will run cleanly is decided during set-up: foundation, coupling to the building, slip table, head expander, fixture and measurement chain together form one dynamic system. If one of these resonances lies in the test range, the controller fights against it – with aborts, overtesting and wrongly loaded specimens.</p>'
                    .'<h3>How we work</h3><ol>'
                    .'<li><strong>Define requirements</strong> – standards, specimens, masses, profiles and future needs</li>'
                    .'<li><strong>Size the system</strong> – force, stroke and velocity from sine, random and shock profiles; slip table, cooling, climatic chamber</li>'
                    .'<li><strong>Plan the installation</strong> – foundation or seismic block, isolation from the building (DIN 4150, VDI 2062), space and utilities</li>'
                    .'<li><strong>Design fixtures</strong> – head expanders and adapters by FEM, first natural frequency above the test range</li>'
                    .'<li><strong>Measurement chain and control</strong> – transducers and mounting (ISO 5348), cable routing, control strategy and abort limits</li>'
                    .'<li><strong>Commissioning and acceptance</strong> – transfer functions, cross-axis motion, bare-table and loaded profiles, documentation and training of your team</li>'
                    .'</ol>'
                    .'<h3>New purchase</h3><p>For new systems we recommend electrodynamic vibration test systems from our partner '.$partner.' in Reinbek, Germany. We coordinate the sizing with you and the manufacturer and accompany delivery, installation and acceptance. We optimise existing systems from other manufacturers just the same.</p>'
                    .'<p>For a first estimate of which system fits your test profiles, use our <a href="/en/shaker-auslegung">shaker sizing tool</a>. Below you can try how the tuning of the mounting changes the transmission into the building.</p>',
            ],
        ],

        'sinusschwingpruefung' => [
            'title' => 'Sinusprüfung: Spezifikation und Begleitung',
            'excerpt' => 'Wir leiten Ihre Sinusprüfung nach DIN EN 60068-2-6 ab, richten Aufbau und Regler auf Ihrer Anlage ein und bewerten die Resonanzsuche.',
            'features' => ['Prüfschärfe aus Norm oder Einsatzbedingungen', 'Regler-Setup: Sweep, Übergangsfrequenz, Abbruchgrenzen', 'Resonanzsuche vorher und nachher auswerten', 'Begleitung auf Ihrer Anlage oder im Prüflabor Ihrer Wahl'],
            'body' => '<p>Die Sinusprüfung ist der Klassiker, um Resonanzen zu finden und die Festigkeit gegen periodische Schwingungen nachzuweisen. Damit sie aussagekräftig ist, müssen Prüfschärfe, Befestigung und Regelung zusammenpassen – hier unterstützen wir Sie.</p><h3>Was wir für Sie übernehmen</h3><ol><li>Prüfspezifikation abstimmen: Frequenzbereich, Weg-/Beschleunigungsübergang, Sweep-Rate, Verweilzeiten</li><li>Befestigung nach DIN EN 60068-2-47 planen und die Vorrichtung auf Resonanzen prüfen</li><li>Regler einrichten: Kontrollpunkte, Mittelwert- oder Extremwertregelung, Warn- und Abbruchgrenzen</li><li>Resonanzsuchen vor und nach der Belastung vergleichen – Frequenzverschiebungen zeigen Schäden</li></ol><p>Die Prüfung läuft auf Ihrer Anlage oder bei einem Prüflabor Ihrer Wahl; wir begleiten sie und bewerten die Ergebnisse mit Ihnen.</p>',
            'en' => [
                'title' => 'Sine testing: specification and support',
                'excerpt' => 'We derive your sine test to IEC 60068-2-6, set up fixture and controller on your system and evaluate the resonance search.',
                'features' => ['Test severity from standard or service conditions', 'Controller set-up: sweep, crossover frequency, abort limits', 'Evaluating resonance searches before and after', 'Support on your system or at a test lab of your choice'],
                'body' => '<p>Sine testing is the classic way to find resonances and to prove strength against periodic vibration. To be meaningful, test severity, mounting and control have to match – that is where we support you.</p><h3>What we take care of</h3><ol><li>Agree the test specification: frequency range, displacement/acceleration crossover, sweep rate, dwell times</li><li>Plan the mounting to IEC 60068-2-47 and check the fixture for resonances</li><li>Set up the controller: check points, averaging or extremal control, warning and abort limits</li><li>Compare resonance searches before and after loading – frequency shifts reveal damage</li></ol><p>The test runs on your system or at a test lab of your choice; we accompany it and evaluate the results with you.</p>',
            ],
        ],

        'random-pruefung-breitbandrauschen' => [
            'title' => 'Random-Prüfung: Profile und Begleitung',
            'excerpt' => 'PSD-Profile nach DIN EN 60068-2-64 oder aus Feldmessungen ableiten, Anlagenbedarf vorab berechnen und die Regelung sauber einrichten.',
            'features' => ['PSD-Profile nach Norm oder aus Messdaten', 'Grms, Hub und Kraftbedarf vorab berechnet', 'Mehrpunkt-Regelung (Mittelwert/Extremwert) einrichten', 'Zeitraffung und Prüfdauer begründet festlegen'],
            'body' => '<p>Reale Umgebungen – Fahrzeug, Bahn, Flugzeug, Transport – erzeugen keine sauberen Sinusschwingungen, sondern ein breitbandiges, zufälliges Signal. Bei der Random-Prüfung regelt der Schwingerreger auf ein Leistungsdichtespektrum (PSD, in g²/Hz) und belastet alle Resonanzen des Prüflings gleichzeitig.</p><h3>Was wir vorab für Sie klären</h3><ul><li>Effektivwert (Grms) und Crest-Faktor des Profils</li><li>Erforderlicher Hub (Spitze-Spitze), Geschwindigkeit und Kraftbedarf – passt das Profil auf Ihre Anlage?</li><li>Prüfdauer je Achse und Zeitraffung aus Felddaten</li><li>Kontrollpunkte, Freiheitsgrade und Toleranzen im Regler</li></ul>',
            'en' => [
                'title' => 'Random testing: profiles and support',
                'excerpt' => 'Derive PSD profiles to IEC 60068-2-64 or from field measurements, calculate the system demand in advance and set up control properly.',
                'features' => ['PSD profiles from standards or measured data', 'Grms, stroke and force demand calculated in advance', 'Setting up multipoint control (average/extremal)', 'Justified time compression and test duration'],
                'body' => '<p>Real environments – road, rail, aircraft, shipping – do not produce clean sine waves but broadband random signals. In random testing the shaker is controlled to a power spectral density (PSD, in g²/Hz) and loads all resonances of the specimen at the same time.</p><h3>What we clarify for you in advance</h3><ul><li>RMS value (Grms) and crest factor of the profile</li><li>Required stroke (peak-to-peak), velocity and force – does the profile fit your system?</li><li>Test duration per axis and time compression from field data</li><li>Check points, degrees of freedom and tolerances in the controller</li></ul>',
            ],
        ],

        'schockpruefung' => [
            'title' => 'Schockprüfung: Pulse und SRS',
            'excerpt' => 'Halbsinus-, Sägezahn- und Trapezschocks nach DIN EN 60068-2-27 sowie Schock-Antwortspektren (SRS) – spezifiziert, eingerichtet und bewertet.',
            'features' => ['Halbsinus, Sägezahn, Trapez', 'Machbarkeit: Hub, Geschwindigkeit, Vor- und Nachpuls', 'Schock-Antwortspektrum (SRS) synthetisieren', 'Toleranzbänder nach Norm prüfen und dokumentieren'],
            'body' => '<p>Stöße beim Transport, bei Fehlbedienung oder im Betrieb sind kurz, aber heftig. Auf einem Shaker lässt sich nicht jeder Puls fahren: Hub, Geschwindigkeit und die nötigen Vor- und Nachpulse begrenzen, was möglich ist. Wir prüfen die Machbarkeit vorab – zum Beispiel für 30 g / 18 ms oder 50 g / 11 ms Halbsinus – und richten die Schockregelung auf Ihrer Anlage ein.</p><p>Bei komplexen Einsatzbedingungen arbeiten wir mit Schock-Antwortspektren (SRS) und synthetisieren daraus geeignete Prüfsignale.</p>',
            'en' => [
                'title' => 'Shock testing: pulses and SRS',
                'excerpt' => 'Half-sine, sawtooth and trapezoidal shocks to IEC 60068-2-27 and shock response spectra (SRS) – specified, set up and evaluated.',
                'features' => ['Half-sine, sawtooth, trapezoid', 'Feasibility: stroke, velocity, pre- and post-pulse', 'Synthesising shock response spectra (SRS)', 'Checking and documenting tolerance bands'],
                'body' => '<p>Shocks during transport, mishandling or operation are short but severe. Not every pulse can be run on a shaker: stroke, velocity and the necessary pre- and post-pulses limit what is possible. We check feasibility in advance – for example for 30 g / 18 ms or 50 g / 11 ms half-sine – and set up shock control on your system.</p><p>For complex service conditions we work with shock response spectra (SRS) and synthesise suitable test signals from them.</p>',
            ],
        ],

        'modalanalyse-betriebsschwingformen' => [
            'excerpt' => 'Experimentelle Modalanalyse mit Impulshammer oder über Ihren Shaker und ODS-Analyse direkt im Betrieb.',
            'features' => ['Eigenfrequenzen, Dämpfungen, Eigenformen', 'Impulshammer oder Anregung über Ihre Anlage', 'Animierte Schwingformen', 'Daten für den FE-Modellabgleich'],
            'en' => [
                'excerpt' => 'Experimental modal analysis with impact hammer or via your shaker, and ODS analysis directly in operation.',
                'features' => ['Natural frequencies, damping, mode shapes', 'Impact hammer or excitation via your system', 'Animated mode shapes', 'Data for FE model correlation'],
            ],
        ],

        'pruefvorrichtungen-adapter' => [
            'excerpt' => 'Steife, resonanzarme Vorrichtungen, Kopfplatten und Adapter – per FEM ausgelegt und auf Ihrer Anlage messtechnisch abgenommen.',
            'en' => [
                'excerpt' => 'Stiff, low-resonance fixtures, head expanders and adapters – designed by FEM and accepted by measurement on your system.',
            ],
        ],

        'schulungen-workshops' => [
            'features' => ['Offene Termine und Inhouse-Schulungen', 'Live-Demos im Regler-Simulator, inhouse an Ihrer Anlage', 'Teilnahmebescheinigung', 'Unterlagen zum Nachschlagen'],
            'en' => [
                'features' => ['Open dates and in-house training', 'Live demos in the controller simulator, in-house on your system', 'Certificate of attendance', 'Reference documents'],
            ],
        ],
    ],

    'trainings' => [
        'grundlagen-der-schwingungstechnik' => [
            'topics' => ['Einmassenschwinger, Eigenfrequenz und Dämpfung', 'Weg, Geschwindigkeit, Beschleunigung – Umrechnung', 'Resonanz und Übertragungsfunktion', 'Messkette: Sensor, Verstärker, Datenerfassung', 'Live-Demo im Regler-Simulator'],
            'body' => '<p>Das Seminar vermittelt die physikalischen Grundlagen anschaulich und ohne unnötige Formelflut. Jede Einheit wird mit Beispielen und Live-Demonstrationen im Regler-Simulator begleitet – als Inhouse-Schulung auch an Ihrer eigenen Anlage.</p>',
            'en' => [
                'topics' => ['Single-degree-of-freedom system, natural frequency and damping', 'Displacement, velocity, acceleration – conversion', 'Resonance and transfer function', 'Measurement chain: sensor, amplifier, data acquisition', 'Live demo in the controller simulator'],
                'body' => '<p>The seminar explains the physics clearly and without unnecessary formulas. Every unit is accompanied by examples and live demonstrations in the controller simulator – as in-house training also on your own system.</p>',
            ],
        ],

        'shaker-pruefstand-planen-und-betreiben' => [
            'create' => ['level' => 'fortgeschritten', 'format' => 'inhouse', 'price' => null, 'dates' => [], 'sort' => 45],
            'title' => 'Shaker-Prüfstand planen und betreiben',
            'duration' => '1 Tag',
            'excerpt' => 'Für alle, die eine Schwingprüfanlage anschaffen, aufbauen oder besser nutzen wollen: Auslegung, Aufstellung, Vorrichtungen und Regelung.',
            'topics' => ['Kraft, Hub und Geschwindigkeit aus Prüfprofilen ableiten', 'Fundament, Seismikmasse und Gebäudeisolierung', 'Gleittisch, Kopfplatte und Vorrichtung resonanzarm auslegen', 'Messkette, Kontrollpunkte und Abbruchgrenzen', 'Abnahme und typische Fehler im Betrieb'],
            'body' => '<p>Kompaktes Seminar für Prüfingenieure, Laborleiter und Planer. Am Beispiel Ihrer Prüfaufgaben zeigen wir, worauf es bei Auswahl, Aufstellung und Betrieb einer Schwingprüfanlage ankommt – damit Resonanzen gar nicht erst entstehen.</p>',
            'en' => [
                'title' => 'Planning and operating a shaker test system',
                'duration' => '1 day',
                'excerpt' => 'For anyone buying, setting up or wanting to make better use of a vibration test system: sizing, installation, fixtures and control.',
                'topics' => ['Deriving force, stroke and velocity from test profiles', 'Foundation, seismic mass and building isolation', 'Low-resonance design of slip table, head expander and fixture', 'Measurement chain, check points and abort limits', 'Acceptance and typical mistakes in operation'],
                'body' => '<p>Compact seminar for test engineers, lab managers and planners. Using your own test tasks as examples, we show what matters when selecting, installing and operating a vibration test system – so that resonances do not arise in the first place.</p>',
            ],
        ],
    ],
];
