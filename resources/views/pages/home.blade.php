@extends('layouts.app')

@section('content')
    @php
        $testRows = [
            'sine' => [
                'title' => 'Sinus',
                'norm' => 'DIN EN 60068-2-6',
                'text' => 'Gleitsinus und Resonanzverweilen. Findet Eigenfrequenzen und weist die Festigkeit gegen periodische Anregung nach.',
                'spec' => ['Frequenz' => '5–2000 Hz', 'Regelung' => 'Weg / Beschleunigung', 'Sweep' => '1 Okt./min'],
            ],
            'random' => [
                'title' => 'Random',
                'norm' => 'DIN EN 60068-2-64',
                'text' => 'Breitbandrauschen nach Leistungsdichtespektrum. Belastet alle Resonanzen gleichzeitig – so wie Straße, Schiene und Flug.',
                'spec' => ['Profil' => 'PSD in g²/Hz', 'Kennwert' => 'Grms', 'Herkunft' => 'Norm oder Feldmessung'],
            ],
            'shock' => [
                'title' => 'Schock',
                'norm' => 'DIN EN 60068-2-27',
                'text' => 'Halbsinus, Sägezahn, Trapez und Schock-Antwortspektren. Für Stöße bei Transport, Handhabung und Betrieb.',
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
                <h1 class="h-display" id="hero-title">{{ $site->get('hero_title') }}</h1>
                <p class="lede">{{ $site->get('hero_text') }}</p>
                <div class="hero__actions">
                    <a class="btn btn--primary" href="{{ route('contact', ['thema' => 'pruefung']) }}">Prüfung anfragen</a>
                    <a class="btn" href="#resonanz">Resonanz-Demo ausprobieren</a>
                </div>
            </div>
            <div>
                <div class="bezel">
                    <div class="softkeys" role="group" aria-label="Signalart anzeigen">
                        <button type="button" class="softkey" data-scope-mode="sine" aria-pressed="true">Sinus</button>
                        <button type="button" class="softkey" data-scope-mode="random" aria-pressed="false">Random</button>
                        <button type="button" class="softkey" data-scope-mode="shock" aria-pressed="false">Schock</button>
                    </div>
                    <dl class="readouts" data-scope-readouts aria-live="polite">
                        <div><dt>Frequenz</dt><dd>80 Hz</dd></div>
                        <div><dt>Spitze</dt><dd>5 g</dd></div>
                        <div><dt>Effektiv</dt><dd>3,536 g</dd></div>
                        <div><dt>Weg</dt><dd>±0,194 mm</dd></div>
                    </dl>
                    <div class="bezel__norm"><span>Prüfnorm</span><strong data-scope-norm>DIN EN 60068-2-6</strong></div>
                </div>
                <p class="hero__hint">Bewegen Sie den Mauszeiger über das Signal, um Zeit und Beschleunigung abzulesen.</p>
            </div>
        </div>
    </section>

    {{-- Prüfarten --}}
    <section class="section" aria-labelledby="tests-title">
        <div class="container">
            <div class="section-head">
                <h2 class="h-2" id="tests-title">Drei Signale. Eine Frage: Hält Ihr Produkt?</h2>
                <p class="lede">Jede Umgebung hat ihre eigene Signatur. Wir wählen das passende Prüfverfahren, leiten die Schärfe aus Norm oder Feldmessung ab und dokumentieren jeden Lauf nachvollziehbar.</p>
            </div>
            <div class="tests">
                @foreach ($testRows as $type => $row)
                    @php $svc = $tests[$type] ?? null; @endphp
                    <article class="test-row" data-type="{{ $type }}">
                        <div class="test-row__trace" aria-hidden="true"><canvas data-mini-scope="{{ $type }}"></canvas></div>
                        <div>
                            <h3><a href="{{ $svc ? route('services.show', $svc) : route('contact', ['thema' => 'pruefung']) }}">{{ $row['title'] }}</a></h3>
                            <div class="test-row__norm">{{ $row['norm'] }}</div>
                            <p>{{ $row['text'] }}</p>
                        </div>
                        <dl class="spec">
                            @foreach ($row['spec'] as $k => $v)
                                <dt>{{ $k }}</dt><dd>{{ $v }}</dd>
                            @endforeach
                        </dl>
                        <span class="test-row__go" aria-hidden="true"><x-glyph name="arrow" style="width:20px;height:20px" /></span>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Resonanzbeseitigung --}}
    <section class="section section--panel" id="resonanz" aria-labelledby="reso-title">
        <div class="container reso">
            <div>
                <h2 class="h-2" id="reso-title">Resonanzen beseitigen, nicht Symptome behandeln</h2>
                <p class="lede" style="margin-top:1.2rem">Trifft eine Drehzahl auf eine Eigenfrequenz, schaukelt sich die Struktur auf – hier um das 25-fache. Wählen Sie rechts eine Maßnahme und verschieben Sie die Betriebsfrequenz.</p>
                <ol class="steps">
                    <li><strong>Messen</strong><span>Betriebsschwingungen, Hochlauf, Übertragungsfunktionen</span></li>
                    <li><strong>Analysieren</strong><span>FFT, Campbell-Diagramm, Betriebsschwingformen, Modalanalyse</span></li>
                    <li><strong>Simulieren</strong><span>FE-Modell abgleichen, Varianten bewerten</span></li>
                    <li><strong>Umsetzen</strong><span>Versteifen, dämpfen, isolieren oder tilgen</span></li>
                    <li><strong>Nachweisen</strong><span>Kontrollmessung mit Vorher-Nachher-Vergleich</span></li>
                </ol>
                @if ($resonance)
                    <p style="margin-top:1.6rem"><a class="link" href="{{ route('services.show', $resonance) }}">Mehr zur Resonanzbeseitigung</a></p>
                @endif
            </div>
            @include('partials.demo-resonance')
        </div>
    </section>

    {{-- Leistungen --}}
    <section class="section" aria-labelledby="svc-title">
        <div class="container">
            <div class="section-head">
                <h2 class="h-2" id="svc-title">Vom Prüfstand bis zur Simulation</h2>
                <p class="lede">Prüfung, Messung und Berechnung greifen bei uns ineinander. Das spart Schleifen: Was im Versuch auffällt, klären wir im Modell – und umgekehrt.</p>
            </div>
            <div class="svc-groups">
                @foreach (\App\Support\Catalog::SERVICE_CATEGORIES as $key => $label)
                    @continue(! isset($servicesByCategory[$key]))
                    <div class="svc-group">
                        <h3>{{ $label }}</h3>
                        <ul class="svc-list">
                            @foreach ($servicesByCategory[$key] as $service)
                                <li>
                                    <a class="svc-link" href="{{ route('services.show', $service) }}">
                                        <x-glyph :name="$service->icon" />
                                        <strong>{{ $service->title }}</strong>
                                        <span>{{ $service->excerpt }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Berechnungen --}}
    <section class="section section--panel section--bolts" aria-labelledby="calc-title">
        <div class="container calc-teaser">
            <div>
                <h2 class="h-2" id="calc-title">Rechnen, bevor es schwingt</h2>
                <p class="lede" style="margin-top:1.2rem">Unsere Rechner nutzen wir selbst im Prüffeld. Sie stehen Ihnen frei zur Verfügung – ohne Anmeldung.</p>
                <ul class="tool-list" style="margin-top:2rem">
                    <li><a href="{{ route('calculators') }}#sinus"><div><strong>Sinus-Umrechner</strong><span>Weg, Geschwindigkeit, Beschleunigung und Übergangsfrequenz</span></div><span class="mono">a = (2πf)²·x</span></a></li>
                    <li><a href="{{ route('calculators') }}#random"><div><strong>Random: Grms aus PSD</strong><span>Effektivwert, Hub und Steigungen in dB/Oktave</span></div><span class="mono">√∫G(f) df</span></a></li>
                    <li><a href="{{ route('calculators') }}#schock"><div><strong>Schock</strong><span>Geschwindigkeitsänderung, Fallhöhe, Pulsform</span></div><span class="mono">Δv = 2AD/π</span></a></li>
                    <li><a href="{{ route('calculators') }}#eigenfrequenz"><div><strong>Eigenfrequenz</strong><span>Masse, Steifigkeit, statische Einfederung</span></div><span class="mono">f₀ = √(k/m)/2π</span></a></li>
                    <li><a href="{{ route('calculators') }}#isolierung"><div><strong>Schwingungsisolierung</strong><span>Übertragungsfaktor und Isolierwirkung</span></div><span class="mono">T(f/f₀, D)</span></a></li>
                    <li><a href="{{ route('calculators') }}#schwingstaerke"><div><strong>Schwingstärke</strong><span>Zonenbewertung für Maschinen</span></div><span class="mono">v<sub>eff</sub> in mm/s</span></a></li>
                </ul>
            </div>
            <div class="panel" data-calc="sine">
                <div class="panel__head"><h3>Sinus-Umrechner</h3><span class="muted" style="font-size:.9rem">Spitzenwerte</span></div>
                <div class="panel__body stack-m">
                    <div class="form-grid">
                        <div class="field">
                            <label for="h-f">Frequenz</label>
                            <div class="unit-input"><input class="input input--mono" id="h-f" name="f" inputmode="decimal" value="80"><span>Hz</span></div>
                        </div>
                        <div class="field">
                            <label for="h-v">Wert</label>
                            <input class="input input--mono" id="h-v" name="value" inputmode="decimal" value="5">
                        </div>
                        <div class="field full">
                            <label for="h-k">Gegeben ist</label>
                            <select class="select" id="h-k" name="kind">
                                <option value="acc_g">Beschleunigung in g</option>
                                <option value="acc_ms2">Beschleunigung in m/s²</option>
                                <option value="vel">Geschwindigkeit in mm/s</option>
                                <option value="disp_peak">Weg (Spitze) in mm</option>
                                <option value="disp_pp">Weg (Spitze-Spitze) in mm</option>
                            </select>
                        </div>
                    </div>
                    <p class="error-text" data-error hidden></p>
                    <dl class="results">
                        <div class="is-key"><dt>Beschleunigung</dt><dd><span data-out="acc-g">–</span><small>g</small></dd></div>
                        <div><dt>Geschwindigkeit</dt><dd><span data-out="vel-peak">–</span><small>mm/s</small></dd></div>
                        <div><dt>Weg Spitze</dt><dd><span data-out="disp-peak">–</span><small>mm</small></dd></div>
                        <div><dt>Weg Spitze-Spitze</dt><dd><span data-out="disp-pp">–</span><small>mm</small></dd></div>
                    </dl>
                </div>
            </div>
        </div>
    </section>

    {{-- Schulungen --}}
    <section class="section" aria-labelledby="training-title">
        <div class="container">
            <div class="section-head">
                <h2 class="h-2" id="training-title">Nächste Schulungstermine</h2>
                <p class="lede">Grundlagen, Prüfnormen, Signalanalyse und Resonanzbeseitigung – mit Live-Versuchen am Schwingerreger. Alle Seminare gibt es auch als Inhouse-Schulung.</p>
            </div>
            @if ($upcoming->isEmpty())
                <div class="empty">Gerade sind keine offenen Termine geplant. <a class="link" href="{{ route('contact', ['thema' => 'schulung']) }}">Fragen Sie eine Inhouse-Schulung an.</a></div>
            @else
                <div class="dates">
                    @foreach ($upcoming as $date)
                        @include('partials.date-row', ['date' => $date, 'training' => $date['training']])
                    @endforeach
                </div>
                <p style="margin-top:1.6rem"><a class="link" href="{{ route('trainings.index') }}">Alle Schulungen ansehen</a></p>
            @endif
        </div>
    </section>

    {{-- Normen --}}
    <section class="section section--panel" aria-labelledby="norm-title">
        <div class="container">
            <div class="section-head">
                <h2 class="h-2" id="norm-title">Normen, nach denen wir prüfen</h2>
                <p class="lede">Von der Grundnorm DIN EN 60068 über Fahrzeug-, Bahn- und Luftfahrtnormen bis zur Bewertung von Maschinen und Bauwerken. Die Farbe zeigt die Signalart.</p>
            </div>
            <div class="norm-index">
                @foreach ($norms as $norm)
                    <a class="norm-chip" data-category="{{ $norm->category }}" href="{{ route('norms.show', $norm) }}">
                        <strong>{{ $norm->code }}</strong>
                        <span>{{ $norm->title }}</span>
                    </a>
                @endforeach
            </div>
            <p style="margin-top:1.6rem"><a class="link" href="{{ route('norms.index') }}">Alle {{ $normCount }} Normen durchsuchen</a></p>
        </div>
    </section>

    @include('partials.cta')
@endsection
