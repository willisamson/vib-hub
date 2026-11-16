@extends('layouts.app')

@section('content')
    @php
        $testRows = [
            'sine' => [
                'title' => __('Sinus'),
                'norm' => 'DIN EN 60068-2-6',
                'text' => __('Gleitsinus und Resonanzverweilen. Findet Eigenfrequenzen und weist die Festigkeit gegen periodische Anregung nach.'),
                'spec' => ['Frequenz' => '5–2000 Hz', 'Regelung' => 'Weg / Beschleunigung', 'Sweep' => '1 Okt./min'],
            ],
            'random' => [
                'title' => __('Random'),
                'norm' => 'DIN EN 60068-2-64',
                'text' => __('Breitbandrauschen nach Leistungsdichtespektrum. Belastet alle Resonanzen gleichzeitig – so wie Straße, Schiene und Flug.'),
                'spec' => ['Profil' => 'PSD in g²/Hz', 'Kennwert' => 'Grms', 'Herkunft' => 'Norm oder Feldmessung'],
            ],
            'shock' => [
                'title' => __('Schock'),
                'norm' => 'DIN EN 60068-2-27',
                'text' => __('Halbsinus, Sägezahn, Trapez und Schock-Antwortspektren. Für Stöße bei Transport, Handhabung und Betrieb.'),
                'spec' => ['Beispiel' => '50 g / 11 ms', 'Anzahl' => '3 je Richtung', 'Auswertung' => 'Toleranzband, SRS'],
            ],
        ];
    @endphp

    {{-- Hero: das Oszilloskop --}}
    <section class="hero" data-hero-scope data-mode="sine" aria-labelledby="hero-title">
        <div class="hero__screen" aria-hidden="true">
            <canvas data-scope-canvas></canvas>
        </div>
        <div class="container hero__inner">
            <div class="hero__copy">
                <p class="hero__kicker">{{ $site->get('hero_kicker') }}</p>
                <h1 class="hd-display" id="hero-title">{{ $site->get('hero_title') }}</h1>
                <p class="lede">{{ $site->get('hero_text') }}</p>
                <div class="hero__actions">
                    <a class="btn btn--primary" href="{{ lroute('contact', ['thema' => 'pruefstand']) }}">{{ __('Beratung anfragen') }}</a>
                    <a class="btn" href="#resonanz">{{ __('Resonanz-Demo ausprobieren') }}</a>
                </div>
            </div>
            <div>
                <div class="bezel">
                    <div class="softkeys" role="group" aria-label="{{ __('Signalart anzeigen') }}">
                        <button type="button" class="softkey" data-scope-mode="sine" aria-pressed="true">{{ __('Sinus') }}</button>
                        <button type="button" class="softkey" data-scope-mode="random" aria-pressed="false">Random</button>
                        <button type="button" class="softkey" data-scope-mode="shock" aria-pressed="false">{{ __('Schock') }}</button>
                    </div>
                    <dl class="readouts" data-scope-readouts aria-live="polite">
                        <div><dt>{{ __('Frequenz') }}</dt><dd>{{ __('80 Hz') }}</dd></div>
                        <div><dt>{{ __('Spitze') }}</dt><dd>5 g</dd></div>
                        <div><dt>{{ __('Effektiv') }}</dt><dd>{{ dec('3,536') }} g</dd></div>
                        <div><dt>{{ __('Weg') }}</dt><dd>±{{ dec('0,194') }} mm</dd></div>
                    </dl>
                    <div class="bezel__norm"><span>{{ __('Prüfnorm') }}</span><strong data-scope-norm>{{ __('DIN EN 60068-2-6') }}</strong></div>
                </div>
                <p class="hero__hint">{{ __('Bewegen Sie den Mauszeiger über das Signal, um Zeit und Beschleunigung abzulesen.') }}</p>
            </div>
        </div>
    </section>

    {{-- Prüfarten --}}
    <section class="section" aria-labelledby="tests-title">
        <div class="container">
            <div class="section-head">
                <h2 class="hd-2" id="tests-title">{{ __('Drei Signale. Eine Frage: Hält Ihr Produkt?') }}</h2>
                <p class="lede">{{ __('Jede Umgebung hat ihre eigene Signatur. Wir wählen mit Ihnen das passende Prüfverfahren, leiten die Schärfe aus Norm oder Feldmessung ab und richten Ihre Anlage so ein, dass jeder Lauf sauber regelt.') }}</p>
            </div>
            <div class="tests">
                @foreach ($testRows as $type => $row)
                    @php $svc = $tests[$type] ?? null; @endphp
                    <article class="test-row" data-type="{{ $type }}">
                        <div class="test-row__trace" aria-hidden="true"><canvas data-mini-scope="{{ $type }}"></canvas></div>
                        <div>
                            <h3><a href="{{ $svc ? lroute('services.show', $svc) : lroute('contact', ['thema' => 'pruefung']) }}">{{ $row['title'] }}</a></h3>
                            <div class="test-row__norm">{{ __($row['norm']) }}</div>
                            <p>{{ $row['text'] }}</p>
                        </div>
                        <dl class="spec">
                            @foreach ($row['spec'] as $k => $v)
                                <dt>{{ __($k) }}</dt><dd>{{ __($v) }}</dd>
                            @endforeach
                        </dl>
                        <span class="test-row__go" aria-hidden="true"><x-glyph name="arrow" style="width:20px;height:20px" /></span>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    @include('partials.app-promo')

    {{-- Resonanzbeseitigung --}}
    <section class="section section--panel" id="resonanz" aria-labelledby="reso-title">
        <div class="container reso">
            <div>
                <h2 class="hd-2" id="reso-title">{{ __('Resonanzen beseitigen, nicht Symptome behandeln') }}</h2>
                <p class="lede" style="margin-top:1.2rem">{{ __('Trifft eine Drehzahl auf eine Eigenfrequenz, schaukelt sich die Struktur auf – hier um das 25-fache. Wählen Sie rechts eine Maßnahme und verschieben Sie die Betriebsfrequenz.') }}</p>
                <ol class="steps">
                    <li><strong>{{ __('Messen') }}</strong><span>{{ __('Betriebsschwingungen, Hochlauf, Übertragungsfunktionen') }}</span></li>
                    <li><strong>{{ __('Analysieren') }}</strong><span>{{ __('FFT, Campbell-Diagramm, Betriebsschwingformen, Modalanalyse') }}</span></li>
                    <li><strong>{{ __('Simulieren') }}</strong><span>{{ __('FE-Modell abgleichen, Varianten bewerten') }}</span></li>
                    <li><strong>{{ __('Umsetzen') }}</strong><span>{{ __('Versteifen, dämpfen, isolieren oder tilgen') }}</span></li>
                    <li><strong>{{ __('Nachweisen') }}</strong><span>{{ __('Kontrollmessung mit Vorher-Nachher-Vergleich') }}</span></li>
                </ol>
                @if ($resonance)
                    <p style="margin-top:1.6rem"><a class="link" href="{{ lroute('services.show', $resonance) }}">{{ __('Mehr zur Resonanzbeseitigung') }}</a></p>
                @endif
            </div>
            @include('partials.demo-resonance')
        </div>
    </section>

    {{-- Leistungen --}}
    <section class="section" aria-labelledby="svc-title">
        <div class="container">
            <div class="section-head">
                <h2 class="hd-2" id="svc-title">{{ __('Vom Prüfstand bis zur Simulation') }}</h2>
                <p class="lede">{{ __('Planung, Messung und Berechnung greifen bei uns ineinander. Das spart Schleifen: Was am Prüfstand auffällt, klären wir im Modell – und umgekehrt.') }}</p>
            </div>
            <div class="svc-groups">
                @foreach (\App\Support\Catalog::SERVICE_CATEGORIES as $key => $label)
                    @continue(! isset($servicesByCategory[$key]))
                    <div class="svc-group">
                        <h3>{{ __($label) }}</h3>
                        <ul class="svc-list">
                            @foreach ($servicesByCategory[$key] as $service)
                                <li>
                                    <a class="svc-link" href="{{ lroute('services.show', $service) }}">
                                        <x-glyph :name="$service->icon" />
                                        <strong>{{ $service->tr('title') }}</strong>
                                        <span>{{ $service->tr('excerpt') }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
            <p class="partner-note">{{ __('Für Neuanschaffungen arbeiten wir mit') }} <a class="link" href="https://rms-testsystems.de" target="_blank" rel="noopener">RMS Dynamic Test Systems</a> {{ __('zusammen – Schwingprüfanlagen aus Reinbek. Bestehende Anlagen anderer Hersteller optimieren wir genauso.') }}</p>
        </div>
    </section>

    {{-- Berechnungen --}}
    <section class="section section--panel section--bolts" aria-labelledby="calc-title">
        <div class="container calc-teaser">
            <div>
                <h2 class="hd-2" id="calc-title">{{ __('Rechnen, bevor es schwingt') }}</h2>
                <p class="lede" style="margin-top:1.2rem">{{ __('Unsere Rechner nutzen wir selbst in jedem Projekt. Sie stehen Ihnen frei zur Verfügung – ohne Anmeldung.') }}</p>
                <ul class="tool-list" style="margin-top:2rem">
                    <li><a href="{{ lroute('calculators') }}#sinus"><div><strong>{{ __('Sinus-Umrechner') }}</strong><span>{{ __('Weg, Geschwindigkeit, Beschleunigung und Übergangsfrequenz') }}</span></div><span class="mono">a = (2πf)²·x</span></a></li>
                    <li><a href="{{ lroute('calculators') }}#random"><div><strong>{{ __('Random: Grms aus PSD') }}</strong><span>{{ __('Effektivwert, Hub und Steigungen in dB/Oktave') }}</span></div><span class="mono">{{ __('√∫G(f) df') }}</span></a></li>
                    <li><a href="{{ lroute('calculators') }}#schock"><div><strong>{{ __('Schock') }}</strong><span>{{ __('Geschwindigkeitsänderung, Fallhöhe, Pulsform') }}</span></div><span class="mono">Δv = 2AD/π</span></a></li>
                    <li><a href="{{ lroute('calculators') }}#eigenfrequenz"><div><strong>{{ __('Eigenfrequenz') }}</strong><span>{{ __('Masse, Steifigkeit, statische Einfederung') }}</span></div><span class="mono">{{ __('f₀ = √(k/m)/2π') }}</span></a></li>
                    <li><a href="{{ lroute('calculators') }}#isolierung"><div><strong>{{ __('Schwingungsisolierung') }}</strong><span>{{ __('Übertragungsfaktor und Isolierwirkung') }}</span></div><span class="mono">{{ __('T(f/f₀, D)') }}</span></a></li>
                    <li><a href="{{ lroute('planner') }}"><div><strong>{{ __('Shaker-Auslegung') }}</strong><span>{{ __('Welche Anlage passt zu Ihren Prüfprofilen?') }}</span></div><span class="mono">F = m·a</span></a></li>
                    <li><a href="{{ lroute('simulator') }}"><div><strong>{{ __('Regler-Simulator') }}</strong><span>{{ __('Sollkurve, Warn- und Abschaltgrenzen live erleben') }}</span></div><span class="mono">±3 / ±6 dB</span></a></li>
                    <li><a href="{{ lroute('calculators') }}#schwingstaerke"><div><strong>{{ __('Schwingstärke') }}</strong><span>{{ __('Zonenbewertung für Maschinen') }}</span></div><span class="mono">v<sub>eff</sub> in mm/s</span></a></li>
                </ul>
            </div>
            <div class="panel" data-calc="sine">
                <div class="panel__head"><h3>{{ __('Sinus-Umrechner') }}</h3><span class="muted" style="font-size:.9rem">{{ __('Spitzenwerte') }}</span></div>
                <div class="panel__body stack-m">
                    <div class="form-grid">
                        <div class="field">
                            <label for="h-f">{{ __('Frequenz') }}</label>
                            <div class="unit-input"><input class="input input--mono" id="h-f" name="f" inputmode="decimal" value="80"><span>Hz</span></div>
                        </div>
                        <div class="field">
                            <label for="h-v">{{ __('Wert') }}</label>
                            <input class="input input--mono" id="h-v" name="value" inputmode="decimal" value="5">
                        </div>
                        <div class="field full">
                            <label for="h-k">{{ __('Gegeben ist') }}</label>
                            <select class="select" id="h-k" name="kind">
                                <option value="acc_g">{{ __('Beschleunigung in g') }}</option>
                                <option value="acc_ms2">{{ __('Beschleunigung in m/s²') }}</option>
                                <option value="vel">{{ __('Geschwindigkeit in mm/s') }}</option>
                                <option value="disp_peak">{{ __('Weg (Spitze) in mm') }}</option>
                                <option value="disp_pp">{{ __('Weg (Spitze-Spitze) in mm') }}</option>
                            </select>
                        </div>
                    </div>
                    <p class="error-text" data-error hidden></p>
                    <dl class="results">
                        <div class="is-key"><dt>{{ __('Beschleunigung') }}</dt><dd><span data-out="acc-g">–</span><small>g</small></dd></div>
                        <div><dt>{{ __('Geschwindigkeit') }}</dt><dd><span data-out="vel-peak">–</span><small>mm/s</small></dd></div>
                        <div><dt>{{ __('Weg Spitze') }}</dt><dd><span data-out="disp-peak">–</span><small>mm</small></dd></div>
                        <div><dt>{{ __('Weg Spitze-Spitze') }}</dt><dd><span data-out="disp-pp">–</span><small>mm</small></dd></div>
                    </dl>
                </div>
            </div>
        </div>
    </section>

    {{-- Schulungen --}}
    <section class="section" aria-labelledby="training-title">
        <div class="container">
            <div class="section-head">
                <h2 class="hd-2" id="training-title">{{ __('Nächste Schulungstermine') }}</h2>
                <p class="lede">{{ __('Grundlagen, Prüfnormen, Prüfstandsplanung, Signalanalyse und Resonanzbeseitigung – mit Live-Demos im Regler-Simulator. Alle Seminare gibt es auch als Inhouse-Schulung, auf Wunsch an Ihrer Anlage.') }}</p>
            </div>
            @if ($upcoming->isEmpty())
                <div class="empty">{{ __('Gerade sind keine offenen Termine geplant.') }} <a class="link" href="{{ lroute('contact', ['thema' => 'schulung']) }}">{{ __('Fragen Sie eine Inhouse-Schulung an.') }}</a></div>
            @else
                <div class="dates">
                    @foreach ($upcoming as $date)
                        @include('partials.date-row', ['date' => $date, 'training' => $date['training']])
                    @endforeach
                </div>
                <p style="margin-top:1.6rem"><a class="link" href="{{ lroute('trainings.index') }}">{{ __('Alle Schulungen ansehen') }}</a></p>
            @endif
        </div>
    </section>

    {{-- Normen --}}
    <section class="section section--panel" aria-labelledby="norm-title">
        <div class="container">
            <div class="section-head">
                <h2 class="hd-2" id="norm-title">{{ __('Normen, die wir mit Ihnen umsetzen') }}</h2>
                <p class="lede">{{ __('Von der Grundnorm DIN EN 60068 über Fahrzeug-, Bahn- und Luftfahrtnormen bis zur Bewertung von Maschinen und Bauwerken. Die Farbe zeigt die Signalart.') }}</p>
            </div>
            <div class="norm-index">
                @foreach ($norms as $norm)
                    <a class="norm-chip" data-category="{{ $norm->category }}" href="{{ lroute('norms.show', $norm) }}">
                        <strong>{{ $norm->code }}</strong>
                        <span>{{ $norm->tr('title') }}</span>
                    </a>
                @endforeach
            </div>
            <p style="margin-top:1.6rem"><a class="link" href="{{ lroute('norms.index') }}">{{ __('Alle :count Normen durchsuchen', ['count' => $normCount]) }}</a></p>
        </div>
    </section>

    @include('partials.cta')
@endsection
