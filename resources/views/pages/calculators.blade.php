@extends('layouts.app')

@section('title', 'Berechnungen')
@section('description', 'Kostenlose Rechner für die Schwingungstechnik: Sinus-Umrechnung, Grms aus PSD, Schock-Geschwindigkeitsänderung, Eigenfrequenz, Schwingungsisolierung und Schwingstärke.')

@section('content')
    @include('partials.page-head', [
        'title' => 'Berechnungen',
        'lede' => 'Die Rechner, die wir täglich im Prüffeld nutzen. Alle Ergebnisse werden direkt in Ihrem Browser berechnet – nichts wird übertragen. Dezimalzahlen dürfen mit Komma oder Punkt eingegeben werden.',
        'crumbs' => ['Berechnungen' => null],
    ])

    <div class="section">
        <div class="container calc-layout">
            <nav class="calc-nav" aria-label="Rechner">
                <a href="#sinus">Sinus-Umrechner</a>
                <a href="#random" style="--c: var(--random)">Random: Grms aus PSD</a>
                <a href="#schock" style="--c: var(--shock)">Schock</a>
                <a href="#eigenfrequenz" style="--c: var(--neutral-cat)">Eigenfrequenz</a>
                <a href="#isolierung" style="--c: var(--neutral-cat)">Schwingungsisolierung</a>
                <a href="#schwingstaerke" style="--c: var(--neutral-cat)">Schwingstärke</a>
            </nav>

            <div class="calc-stack">

                {{-- ============================================== Sinus --}}
                <section class="panel calc" id="sinus" data-calc="sine" aria-labelledby="t-sine">
                    <div class="panel__head">
                        <h2 id="t-sine">Sinus-Umrechner</h2>
                        <span class="mono muted">a = (2πf)²·x &nbsp; v = 2πf·x</span>
                    </div>
                    <div class="panel__body">
                        <div class="form-grid form-grid--3">
                            <div class="field">
                                <label for="s-f">Frequenz</label>
                                <div class="unit-input"><input class="input input--mono" id="s-f" name="f" inputmode="decimal" value="100"><span>Hz</span></div>
                            </div>
                            <div class="field">
                                <label for="s-k">Gegeben ist</label>
                                <select class="select" id="s-k" name="kind">
                                    <option value="acc_g">Beschleunigung (Spitze) in g</option>
                                    <option value="acc_ms2">Beschleunigung (Spitze) in m/s²</option>
                                    <option value="vel">Geschwindigkeit (Spitze) in mm/s</option>
                                    <option value="disp_peak">Weg (Spitze) in mm</option>
                                    <option value="disp_pp">Weg (Spitze-Spitze) in mm</option>
                                </select>
                            </div>
                            <div class="field">
                                <label for="s-v">Wert</label>
                                <input class="input input--mono" id="s-v" name="value" inputmode="decimal" value="10">
                            </div>
                        </div>
                        <p class="error-text" data-error hidden></p>
                        <dl class="results">
                            <div class="is-key"><dt>Beschleunigung Spitze</dt><dd><span data-out="acc-g">–</span><small>g</small></dd></div>
                            <div><dt>Beschleunigung Spitze</dt><dd><span data-out="acc-ms2">–</span><small>m/s²</small></dd></div>
                            <div><dt>Beschleunigung effektiv</dt><dd><span data-out="acc-rms">–</span><small>g</small></dd></div>
                            <div><dt>Geschwindigkeit Spitze</dt><dd><span data-out="vel-peak">–</span><small>mm/s</small></dd></div>
                            <div><dt>Geschwindigkeit effektiv</dt><dd><span data-out="vel-rms">–</span><small>mm/s</small></dd></div>
                            <div><dt>Weg Spitze</dt><dd><span data-out="disp-peak">–</span><small>mm</small></dd></div>
                            <div><dt>Weg Spitze-Spitze</dt><dd><span data-out="disp-pp">–</span><small>mm</small></dd></div>
                        </dl>

                        <div class="calc__sub">
                            <h3>Übergangsfrequenz Weg / Beschleunigung</h3>
                            <p class="calc__note">Typisch für Prüfungen nach DIN EN 60068-2-6: unterhalb der Übergangsfrequenz konstanter Weg, darüber konstante Beschleunigung.</p>
                            <div class="form-grid">
                                <div class="field">
                                    <label for="s-cx">Weg (Spitze)</label>
                                    <div class="unit-input"><input class="input input--mono" id="s-cx" name="cx" inputmode="decimal" value="0,75"><span>mm</span></div>
                                </div>
                                <div class="field">
                                    <label for="s-ca">Beschleunigung (Spitze)</label>
                                    <div class="unit-input"><input class="input input--mono" id="s-ca" name="ca" inputmode="decimal" value="10"><span>g</span></div>
                                </div>
                            </div>
                            <dl class="results">
                                <div class="is-key"><dt>Übergangsfrequenz</dt><dd><span data-out="fc">–</span><small>Hz</small></dd></div>
                            </dl>
                            <div class="calc__plot"><canvas role="img" aria-label="Prüfprofil Beschleunigung über Frequenz"></canvas></div>
                        </div>
                    </div>
                </section>

                {{-- ============================================== Random --}}
                <section class="panel calc" id="random" data-calc="random" aria-labelledby="t-random">
                    <div class="panel__head">
                        <h2 id="t-random">Random: Grms aus PSD</h2>
                        <span class="mono muted">Grms = √∫ G(f) df</span>
                    </div>
                    <div class="panel__body">
                        <div class="field" style="max-width: 460px">
                            <label for="r-preset">Vorlage laden</label>
                            <select class="select" id="r-preset" name="preset">
                                @foreach (['navmat' => 'NAVMAT P-9492 (Screening)', 'gevs' => 'NASA GEVS – Qualifikation (Bauteile ≤ 22,7 kg)', 'flat' => 'Flaches Profil 20–2000 Hz, 0,01 g²/Hz'] as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="calc__split calc__split--psd">
                            <div class="stack-s">
                                <table class="psd-table">
                                    <thead>
                                        <tr><th>Frequenz (Hz)</th><th>PSD (g²/Hz)</th><th>oder dB/Okt.</th><th><span class="sr-only">Aktion</span></th></tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <template>
                                    <tr>
                                        <td><input class="input" data-f inputmode="decimal" aria-label="Frequenz in Hz"></td>
                                        <td><input class="input" data-psd inputmode="decimal" aria-label="PSD in g²/Hz"></td>
                                        <td><input class="input" data-slope inputmode="decimal" aria-label="Steigung in dB pro Oktave"><div data-slope-info></div></td>
                                        <td><button type="button" class="icon-btn" data-remove-row aria-label="Zeile entfernen"><x-glyph name="x" style="width:16px;height:16px" /></button></td>
                                    </tr>
                                </template>
                                <button type="button" class="btn btn--small" data-add-row><x-glyph name="plus" /> Stützstelle hinzufügen</button>
                                <p class="calc__note">Je Zeile entweder PSD-Wert oder Steigung zur vorherigen Stützstelle angeben (z. B. +3 / −6 dB/Okt.).</p>
                            </div>
                            <div class="calc__plot" style="height: 320px"><canvas role="img" aria-label="Leistungsdichtespektrum"></canvas></div>
                        </div>
                        <p class="error-text" data-error hidden></p>
                        <dl class="results">
                            <div class="is-key"><dt>Effektivwert Grms</dt><dd><span data-out="grms">–</span><small>g</small></dd></div>
                            <div><dt>Geschwindigkeit eff.</dt><dd><span data-out="vrms">–</span><small>mm/s</small></dd></div>
                            <div><dt>Weg eff.</dt><dd><span data-out="drms">–</span><small>mm</small></dd></div>
                            <div><dt>Hub 3σ (Spitze-Spitze)</dt><dd><span data-out="dpp">–</span><small>mm</small></dd></div>
                        </dl>
                        <p class="calc__note">Der 3σ-Hub hilft bei der Auswahl des Schwingerregers: Er muss kleiner sein als der nutzbare Hub der Anlage.</p>
                    </div>
                </section>

                {{-- ============================================== Schock --}}
                <section class="panel calc" id="schock" data-calc="shock" aria-labelledby="t-shock">
                    <div class="panel__head">
                        <h2 id="t-shock">Schock</h2>
                        <span class="mono muted">Δv = ∫ a(t) dt</span>
                    </div>
                    <div class="panel__body">
                        <div class="calc__split">
                            <div class="stack-m">
                                <div class="field">
                                    <label for="k-shape">Pulsform</label>
                                    <select class="select" id="k-shape" name="shape">
                                        <option value="halfsine">Halbsinus</option>
                                        <option value="sawtooth">Sägezahn (Endspitze)</option>
                                        <option value="trapezoid">Trapez (10 % Flanken)</option>
                                    </select>
                                </div>
                                <div class="form-grid">
                                    <div class="field">
                                        <label for="k-peak">Spitzenwert</label>
                                        <div class="unit-input"><input class="input input--mono" id="k-peak" name="peak" inputmode="decimal" value="50"><span>g</span></div>
                                    </div>
                                    <div class="field">
                                        <label for="k-dur">Dauer</label>
                                        <div class="unit-input"><input class="input input--mono" id="k-dur" name="duration" inputmode="decimal" value="11"><span>ms</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="calc__plot" style="height: 230px"><canvas role="img" aria-label="Pulsform"></canvas></div>
                        </div>
                        <p class="error-text" data-error hidden></p>
                        <dl class="results">
                            <div class="is-key"><dt>Geschwindigkeitsänderung Δv</dt><dd><span data-out="dv">–</span><small>m/s</small></dd></div>
                            <div><dt>Entspricht freiem Fall aus</dt><dd><span data-out="drop">–</span><small>mm</small></dd></div>
                            <div><dt>Weg während des Pulses</dt><dd><span data-out="disp">–</span><small>mm</small></dd></div>
                        </dl>

                        <div class="calc__sub">
                            <h3>Halbsinus aus Fallhöhe</h3>
                            <div class="form-grid form-grid--3">
                                <div class="field">
                                    <label for="k-h">Fallhöhe</label>
                                    <div class="unit-input"><input class="input input--mono" id="k-h" name="h" inputmode="decimal" value="0,5"><span>m</span></div>
                                </div>
                                <div class="field">
                                    <label for="k-d2">Stoßdauer</label>
                                    <div class="unit-input"><input class="input input--mono" id="k-d2" name="d2" inputmode="decimal" value="6"><span>ms</span></div>
                                </div>
                                <div class="field">
                                    <label for="k-e">Rückprallfaktor</label>
                                    <input class="input input--mono" id="k-e" name="e" inputmode="decimal" value="0">
                                    <span class="hint">0 = kein Rückprall, 1 = vollständig</span>
                                </div>
                            </div>
                            <dl class="results">
                                <div class="is-key"><dt>Erforderlicher Spitzenwert</dt><dd><span data-out="req-g">–</span><small>g</small></dd></div>
                                <div><dt>Geschwindigkeitsänderung</dt><dd><span data-out="req-dv">–</span><small>m/s</small></dd></div>
                            </dl>
                        </div>
                    </div>
                </section>

                {{-- ============================================== Eigenfrequenz --}}
                <section class="panel calc" id="eigenfrequenz" data-calc="natural" aria-labelledby="t-nat">
                    <div class="panel__head">
                        <h2 id="t-nat">Eigenfrequenz des Einmassenschwingers</h2>
                        <span class="mono muted">f₀ = 1/2π · √(k/m)</span>
                    </div>
                    <div class="panel__body">
                        <div class="form-grid">
                            <div class="field">
                                <label for="n-m">Masse</label>
                                <div class="unit-input"><input class="input input--mono" id="n-m" name="m" inputmode="decimal" value="25"><span>kg</span></div>
                            </div>
                            <div class="field">
                                <label for="n-k">Steifigkeit</label>
                                <div class="unit-input"><input class="input input--mono" id="n-k" name="k" inputmode="decimal" value="120"><span>N/mm</span></div>
                            </div>
                        </div>
                        <dl class="results">
                            <div class="is-key"><dt>Eigenfrequenz</dt><dd><span data-out="f0">–</span><small>Hz</small></dd></div>
                            <div><dt>Statische Einfederung</dt><dd><span data-out="delta">–</span><small>mm</small></dd></div>
                        </dl>
                        <div class="calc__split">
                            <div class="calc__sub">
                                <h3>Aus der Einfederung</h3>
                                <div class="field">
                                    <label for="n-d">Statische Einfederung</label>
                                    <div class="unit-input"><input class="input input--mono" id="n-d" name="delta" inputmode="decimal" value="2"><span>mm</span></div>
                                </div>
                                <dl class="results"><div class="is-key"><dt>Eigenfrequenz</dt><dd><span data-out="f0-delta">–</span><small>Hz</small></dd></div></dl>
                            </div>
                            <div class="calc__sub">
                                <h3>Steifigkeit für Ziel-Frequenz</h3>
                                <div class="form-grid">
                                    <div class="field">
                                        <label for="n-m2">Masse</label>
                                        <div class="unit-input"><input class="input input--mono" id="n-m2" name="m2" inputmode="decimal" value="25"><span>kg</span></div>
                                    </div>
                                    <div class="field">
                                        <label for="n-ft">Ziel f₀</label>
                                        <div class="unit-input"><input class="input input--mono" id="n-ft" name="ft" inputmode="decimal" value="8"><span>Hz</span></div>
                                    </div>
                                </div>
                                <dl class="results"><div class="is-key"><dt>Nötige Steifigkeit</dt><dd><span data-out="k-req">–</span><small>N/mm</small></dd></div></dl>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- ============================================== Isolierung --}}
                <section class="panel calc" id="isolierung" aria-labelledby="t-iso">
                    <div class="panel__head">
                        <h2 id="t-iso">Schwingungsisolierung</h2>
                        <span class="mono muted">T = √((1+(2Dη)²) / ((1−η²)²+(2Dη)²))</span>
                    </div>
                    <div class="panel__body">
                        <p class="calc__note" style="margin:0">Eine elastische Lagerung isoliert erst oberhalb des √2-fachen ihrer Eigenfrequenz. Darunter verstärkt sie – im Resonanzfall erheblich. η = f / f₀, D = Lehrsches Dämpfungsmaß.</p>
                        @include('partials.demo-isolation')
                    </div>
                </section>

                {{-- ============================================== Schwingstärke --}}
                <section class="panel calc" id="schwingstaerke" data-calc="severity" aria-labelledby="t-sev">
                    <div class="panel__head">
                        <h2 id="t-sev">Schwingstärke von Maschinen</h2>
                        <span class="mono muted">v<sub>eff</sub> 10–1000 Hz</span>
                    </div>
                    <div class="panel__body">
                        <div class="form-grid">
                            <div class="field">
                                <label for="v-v">Gemessene Schwinggeschwindigkeit (effektiv)</label>
                                <div class="unit-input"><input class="input input--mono" id="v-v" name="v" inputmode="decimal" value="3,2"><span>mm/s</span></div>
                            </div>
                            <div class="field">
                                <label for="v-c">Maschinenklasse</label>
                                <select class="select" id="v-c" name="cls">
                                    <option value="I">Klasse I – kleine Maschinen bis 15 kW</option>
                                    <option value="II" selected>Klasse II – mittlere Maschinen 15–75 kW</option>
                                    <option value="III">Klasse III – große Maschinen, starres Fundament</option>
                                    <option value="IV">Klasse IV – große Maschinen, weiches Fundament</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <div class="severity" data-severity-bar role="img" aria-label="Zonen A bis D"></div>
                            <div class="severity-labels" aria-hidden="true">
                                <span style="left: var(--ab, 25%)" data-limit="0"></span>
                                <span style="left: var(--bc, 50%)" data-limit="1"></span>
                                <span style="left: var(--cd, 75%)" data-limit="2"></span>
                            </div>
                        </div>
                        <div style="display:flex; gap:1rem; align-items:center">
                            <span class="zone-badge" data-out="zone">–</span>
                            <p style="margin:0; font-size:1.1rem; color:#fff" data-out="zone-text"></p>
                        </div>
                        <p class="calc__note">Orientierungswerte nach ISO 10816-1, Anhang B (informativ). Die Norm wurde durch die Reihe ISO 20816 abgelöst, deren maschinenspezifische Teile eigene Grenzwerte festlegen. Für eine verbindliche Bewertung messen wir gern vor Ort.</p>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
