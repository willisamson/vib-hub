<?php

namespace Database\Seeders;

/**
 * Englische Fassungen der Startinhalte (Website unter /en).
 * Schlüssel: Slug (Leistungen, Schulungen) bzw. Normbezeichnung.
 */
class EnglishContent
{
    public static function services(): array
    {
        return [
            'sinusschwingpruefung' => [
                'title' => 'Sine vibration testing',
                'excerpt' => 'Swept sine and resonance dwell testing to IEC 60068-2-6 – with resonance search before and after the endurance run.',
                'features' => ['Resonance search (sweep) before and after the test', 'Swept sine, fixed frequency and resonance dwell', 'Displacement/acceleration crossover to your specification', 'Testing in three axes with full test report'],
                'body' => '<p>Sine vibration testing is the classic way to find resonances and to prove that a product withstands periodic vibration. We run frequency range, amplitude and sweep rate exactly to your specification – typically with constant displacement at low and constant acceleration at high frequencies.</p><h3>Procedure</h3><ol><li>Agreeing the test specification and mounting to IEC 60068-2-47</li><li>Resonance search at low amplitude</li><li>Endurance test (swept sine or dwell at resonances)</li><li>Final resonance search – frequency shifts reveal damage</li></ol><p>You receive a traceable test report with control profiles, transfer functions and photo documentation.</p>',
            ],
            'random-pruefung-breitbandrauschen' => [
                'title' => 'Random vibration testing',
                'excerpt' => 'Broadband random vibration to IEC 60068-2-64 – realistic loading with a defined power spectral density (PSD).',
                'features' => ['PSD profiles from standards or field data', 'Grms, stroke and force requirement calculated in advance', 'Multi-point control (average/extremal)', 'Derivation of profiles from field measurements'],
                'body' => '<p>Real environments – road, rail, aircraft, shipping – do not produce clean sine waves but broadband random signals. In random testing we control the shaker to a defined power spectral density (PSD, in g²/Hz) and excite all resonances of your product at the same time.</p><h3>What we clarify for you in advance</h3><ul><li>RMS value (Grms) and crest factor of the profile</li><li>Required stroke (peak-to-peak displacement) and shaker force</li><li>Test duration per axis and time compression from field data</li></ul>',
            ],
            'schockpruefung' => [
                'title' => 'Shock testing',
                'excerpt' => 'Half-sine, sawtooth and trapezoidal shocks to IEC 60068-2-27, bump testing and shock response spectra (SRS).',
                'features' => ['Half-sine, sawtooth, trapezoid', 'Single shocks and bump testing', 'Shock response spectrum (SRS)', 'Tolerance bands documented to the standard'],
                'body' => '<p>Shocks during transport, mishandling or operation are short but severe. Shock testing proves that your product survives defined impacts without malfunction – for example 30 g / 18 ms or 50 g / 11 ms half-sine, three shocks in each direction of all three axes.</p><p>For complex service conditions we work with shock response spectra (SRS) and synthesise suitable test signals from them.</p>',
            ],
            'resonanzbeseitigung' => [
                'title' => 'Resonance elimination',
                'excerpt' => 'We find the cause of disturbing vibration and eliminate it – with stiffening, damping, isolation or a tuned mass damper.',
                'features' => ['Root cause analysis with FFT, order analysis and ODS', 'Measures verified by simulation', 'Design and tuning of vibration absorbers', 'Proof of effectiveness by measurement'],
                'body' => '<p>Resonance occurs when an excitation frequency – such as shaft speed, gear mesh or pump pulsation – coincides with a natural frequency of the structure. The result: noise, cracks, bearing damage or measurement errors. We work systematically:</p><ol><li><strong>Measure</strong> – operating vibration, run-up/coast-down, transfer functions</li><li><strong>Analyse</strong> – FFT, Campbell diagram, operating deflection shapes (ODS), modal analysis</li><li><strong>Simulate</strong> – correlate the FE model and evaluate variants</li><li><strong>Implement</strong> – stiffen, change mass, add damping, isolate or absorb</li><li><strong>Verify</strong> – control measurement with before/after comparison</li></ol><p>Try below how each measure affects the resonance amplification.</p>',
            ],
            'modalanalyse-betriebsschwingformen' => [
                'title' => 'Modal analysis & operating deflection shapes',
                'excerpt' => 'Experimental modal analysis with impact hammer or shaker, and ODS analysis directly in operation.',
                'features' => ['Natural frequencies, damping, mode shapes', 'Impact hammer and shaker excitation', 'Animated mode shapes', 'Data for FE model correlation'],
                'body' => '<p>Experimental modal analysis determines the dynamic properties of your structure: natural frequencies, modal damping and mode shapes. Operating deflection shape (ODS) analysis additionally shows how the structure actually moves under real excitation.</p>',
            ],
            'fem-berechnung-simulation' => [
                'title' => 'FE analysis & simulation',
                'excerpt' => 'Modal, frequency response, random and shock analyses in the FE model – before the first prototype is built.',
                'features' => ['Modal and harmonic analysis', 'PSD/random response and fatigue life', 'Shock and SRS analysis', 'Design of test fixtures'],
                'body' => '<p>Simulation saves prototypes and test loops. We calculate natural frequencies and stresses under sine, random and shock loading and assess fatigue life. Data from testing and modal analysis is used to correlate the model – so the results are reliable.</p>',
            ],
            'schwingungsmessung-maschinendiagnose' => [
                'title' => 'Vibration measurement & machine diagnostics',
                'excerpt' => 'On-site measurements on machines and plants: evaluation to ISO 20816, unbalance, misalignment, bearing damage.',
                'features' => ['Evaluation to ISO 20816 / ISO 10816', 'Envelope analysis for rolling bearings', 'Field balancing', 'Trend measurements and condition monitoring'],
                'body' => '<p>We come to you with mobile measurement equipment and assess the vibration condition of your machines. Anomalies such as unbalance, misalignment, looseness or incipient bearing damage are detected early – before downtime occurs.</p>',
            ],
            'erschuetterungsmessung-an-bauwerken' => [
                'title' => 'Vibration measurement on buildings',
                'excerpt' => 'Measurement and assessment to DIN 4150-2 and -3 – for construction sites, plants, traffic and vibration-sensitive equipment.',
                'features' => ['Effects on people (DIN 4150-2)', 'Effects on structures (DIN 4150-3)', 'Continuous monitoring with alarms', 'Isolation of machine foundations'],
                'body' => '<p>Vibration from construction work, traffic or machinery can disturb residents and damage buildings. We measure, assess to DIN 4150 and plan measures – from elastic mounts to foundation isolation.</p>',
            ],
            'pruefvorrichtungen-adapter' => [
                'title' => 'Test fixtures & adapters',
                'excerpt' => 'Stiff, low-resonance test fixtures – calculated, manufactured and verified by measurement.',
                'features' => ['First natural frequency above the test range', 'Mounting to IEC 60068-2-47', 'Lightweight design for high accelerations', 'Acceptance by transfer function measurement'],
                'body' => '<p>A test is only as good as its fixture. If a fixture resonance lies within the test range, the specimen is loaded incorrectly. We design fixtures by FE analysis and prove their suitability with a transfer function measurement.</p>',
            ],
            'schulungen-workshops' => [
                'title' => 'Training & workshops',
                'excerpt' => 'Practical seminars on vibration fundamentals, test standards, signal analysis and resonance elimination – open or in-house.',
                'features' => ['Open dates and in-house training', 'Live demonstrations on the shaker', 'Certificate of attendance', 'Reference documents'],
                'body' => '<p>We pass on our knowledge – clearly, practically and with many live demonstrations. All dates can be found under <a href="/en/schulungen">Training</a>.</p>',
            ],
        ];
    }

    public static function trainings(): array
    {
        return [
            'grundlagen-der-schwingungstechnik' => [
                'title' => 'Fundamentals of vibration engineering',
                'duration' => '2 days',
                'excerpt' => 'From the spring-mass oscillator to resonance: the foundation for everyone who works with vibration.',
                'topics' => ['Single-degree-of-freedom system, natural frequency and damping', 'Displacement, velocity, acceleration – conversion', 'Resonance and transfer function', 'Measurement chain: sensor, amplifier, data acquisition', 'Live demonstration on the shaker'],
                'body' => '<p>The seminar explains the physics clearly and without unnecessary formulas. Every unit is accompanied by experiments on the shaker.</p>',
            ],
            'schwingpruefung-nach-din-en-60068-sinus-random-schock' => [
                'title' => 'Vibration testing to IEC 60068: sine, random, shock',
                'duration' => '2 days',
                'excerpt' => 'Reading test standards correctly, deriving specifications and running tests safely.',
                'topics' => ['IEC 60068-2-6, -2-27, -2-64 in detail', 'Mounting to IEC 60068-2-47', 'PSD, Grms, crest factor and shaker sizing', 'Half-sine, sawtooth, trapezoid – tolerances', 'Test reports and typical mistakes'],
                'body' => '<p>For test engineers, developers and quality managers who specify, commission or run vibration tests themselves.</p>',
            ],
            'signalanalyse-fft-in-der-praxis' => [
                'title' => 'Signal analysis & FFT in practice',
                'duration' => '1 day',
                'excerpt' => 'FFT, windowing, averaging, order analysis – interpreting measurement data with confidence.',
                'topics' => ['Sampling, aliasing and resolution', 'Window functions and leakage', 'Power spectral density (PSD)', 'Order analysis and Campbell diagram', 'Transfer function and coherence'],
                'body' => '<p>With many examples from real measurements – you work with genuine data sets.</p>',
            ],
            'resonanzen-erkennen-und-beseitigen' => [
                'title' => 'Detecting and eliminating resonances',
                'duration' => '2 days',
                'excerpt' => 'From root cause analysis to an effective measure: stiffening, damping, isolation and absorbers.',
                'topics' => ['Modal analysis and operating deflection shapes', 'Assessing measures with simple models', 'Designing vibration absorbers (Den Hartog)', 'Elastic mounting and isolation', 'Case studies from machinery and electronics'],
                'body' => '<p>The expert seminar for everyone who wants to solve vibration problems for good – with many case studies and calculation exercises.</p>',
            ],
            'inhouse-schulung-nach-mass' => [
                'title' => 'Tailor-made in-house training',
                'duration' => 'by arrangement',
                'excerpt' => 'We adapt the content to your products, standards and test equipment – at your site.',
                'topics' => ['Freely combinable content', 'Work on your own specimens and data', 'For teams of 4 to 16 people'],
                'body' => '<p>Get in touch – we will put together a suitable programme.</p>',
            ],
        ];
    }

    public static function norms(): array
    {
        $p = fn (array $pairs) => array_map(fn ($x) => ['label' => $x[0], 'value' => $x[1]], $pairs);

        return [
            'DIN EN 60068-2-6' => ['title' => 'Environmental testing – Test Fc: Vibration (sinusoidal)', 'excerpt' => 'Basic standard for sine vibration testing: swept sine, resonance search and dwell testing.', 'parameters' => $p([['Signal', 'Sine, swept sine'], ['Typical range', '10–500 Hz or 10–2000 Hz'], ['Sweep rate', '1 octave/min'], ['Axes', '3 (X, Y, Z)']])],
            'DIN EN 60068-2-64' => ['title' => 'Environmental testing – Test Fh: Vibration, broadband random (digital control)', 'excerpt' => 'Basic standard for random testing with a defined power spectral density.', 'parameters' => $p([['Signal', 'Broadband random (PSD)'], ['Parameters', 'Grms, g²/Hz'], ['Axes', '3 (X, Y, Z)']])],
            'DIN EN 60068-2-27' => ['title' => 'Environmental testing – Test Ea: Shock', 'excerpt' => 'Basic standard for single shocks: half-sine, sawtooth and trapezoid with tolerance bands.', 'parameters' => $p([['Pulse shapes', 'Half-sine, sawtooth, trapezoid'], ['Examples', '30 g / 18 ms, 50 g / 11 ms'], ['Number', 'typ. 3 per direction, 18 in total']])],
            'DIN EN 60068-2-47' => ['title' => 'Environmental testing – Mounting of specimens for vibration, impact and similar dynamic tests', 'excerpt' => 'Rules for mounting specimens and for the position of control and measuring points.'],
            'DIN EN 60068-2-57' => ['title' => 'Environmental testing – Test Ff: Vibration – Time-history and sine-beat method', 'excerpt' => 'Time-history tests, e.g. to simulate earthquake loading.'],
            'DIN EN 60068-2-80' => ['title' => 'Environmental testing – Test Fi: Vibration – Mixed mode', 'excerpt' => 'Superimposed signals: sine-on-random (SoR) and random-on-random (RoR).'],
            'DIN EN 60068-2-31' => ['title' => 'Environmental testing – Test Ec: Rough handling shocks, primarily for equipment-type specimens', 'excerpt' => 'Drop and topple tests for equipment exposed to impacts during handling.'],
            'DIN EN 61373' => ['title' => 'Railway applications – Rolling stock equipment – Shock and vibration tests', 'excerpt' => 'Functional random test, long-life test and shocks for railway components by mounting category.', 'parameters' => $p([['Categories', '1A/1B (car body), 2 (bogie), 3 (axle)']])],
            'ISO 16750-3' => ['title' => 'Road vehicles – Environmental conditions and testing for electrical and electronic equipment – Part 3: Mechanical loads', 'excerpt' => 'Vibration and shock tests for E/E components depending on the mounting location in the vehicle.'],
            'MIL-STD-810 Method 514' => ['excerpt' => 'Military test standard with a tailoring approach: test profiles are derived from the life cycle.'],
            'MIL-STD-810 Method 516' => ['excerpt' => 'Shock tests including shock response spectra (SRS), crash hazard and transport shocks.'],
            'RTCA DO-160' => ['title' => 'Environmental Conditions and Test Procedures for Airborne Equipment (Sections 7 and 8)', 'excerpt' => 'Airborne equipment environmental tests: section 7 operational shocks and crash safety, section 8 vibration.'],
            'DIN EN ISO 13355' => ['title' => 'Packaging – Complete, filled transport packages and unit loads – Vertical random vibration test', 'excerpt' => 'Random test for packages to simulate road and rail transport.'],
            'ASTM D4169' => ['excerpt' => 'Test sequences for shipping units: handling, stacking, transport vibration.'],
            'ISTA 3A' => ['excerpt' => 'Simulation test for parcels in parcel delivery: drop, vibration, compression.'],
            'ISO 20816-1' => ['title' => 'Mechanical vibration – Measurement and evaluation of machine vibration – Part 1: General guidelines', 'excerpt' => 'Successor of ISO 10816-1: evaluation via vibration severity and zones A to D.', 'parameters' => $p([['Quantity', 'Vibration velocity v_rms in mm/s'], ['Evaluation', 'Zones A / B / C / D']])],
            'ISO 20816-3' => ['title' => 'Mechanical vibration – Measurement and evaluation of machine vibration – Part 3: Industrial machinery with a power rating above 15 kW', 'excerpt' => 'Evaluation of industrial machines with nominal speeds between 120 and 30,000 r/min.'],
            'DIN 4150-2' => ['title' => 'Vibrations in buildings – Part 2: Effects on persons in buildings', 'excerpt' => 'Reference values for assessing vibration in dwellings and similarly used rooms.'],
            'DIN 4150-3' => ['title' => 'Vibrations in buildings – Part 3: Effects on structures', 'excerpt' => 'Reference values for vibration velocity below which no damage is to be expected.'],
            'ISO 2631-1' => ['title' => 'Mechanical vibration and shock – Evaluation of human exposure to whole-body vibration – Part 1: General requirements', 'excerpt' => 'Frequency-weighted acceleration for assessing comfort and health.'],
            'DIN EN ISO 5349-1' => ['title' => 'Mechanical vibration – Measurement and evaluation of human exposure to hand-transmitted vibration – Part 1: General requirements', 'excerpt' => 'Basis for assessing hand-arm vibration from tools and machines.'],
            'VDI 2057' => ['title' => 'Human exposure to mechanical vibrations', 'excerpt' => 'Guideline series for whole-body and hand-arm vibration.'],
            'VDI 2062' => ['title' => 'Vibration insulation', 'excerpt' => 'Terms, methods and elements of vibration isolation.'],
            'VDI 3830' => ['title' => 'Damping of materials and members', 'excerpt' => 'Parameters and measurement methods for the damping of materials and components.'],
            'ISO 16063' => ['title' => 'Methods for the calibration of vibration and shock transducers', 'excerpt' => 'Primary and comparison calibration of accelerometers.'],
            'DIN EN 60721-3' => ['title' => 'Classification of environmental conditions – Part 3: Classification of groups of environmental parameters and their severities', 'excerpt' => 'Classes for storage, transport and stationary use – the basis for deriving test severities.'],
        ];
    }
}
