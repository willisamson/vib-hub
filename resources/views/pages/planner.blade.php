@extends('layouts.app')

@section('title', __('Shaker-Auslegung'))
@section('description', __('Welche Schwingprüfanlage passt zu Ihren Prüfungen? Kraft, Hub und Geschwindigkeit aus Sinus-, Random- und Schockprofil berechnen – mit Beispielauslegung auf Shakern von RMS Dynamic Test Systems.'))

@section('content')
    @include('partials.page-head', [
        'title' => __('Shaker-Auslegung'),
        'lede' => __('Geben Sie Prüfling und Prüfprofile ein – der Rechner ermittelt Kraft, Hub und Geschwindigkeit und zeigt, welche Anlage der RMS-Serie die Aufgabe mit Reserve schafft. Mit einem Klick wird daraus eine Anfrage.'),
        'crumbs' => [__('Berechnungen') => lroute('calculators'), __('Shaker-Auslegung') => null],
    ])

    <div class="section" style="padding-top: 2.5rem">
        <div class="container planner" data-planner data-models='@json($models)' data-contact="{{ lroute('contact') }}">
            <form class="panel planner__form" onsubmit="return false" novalidate>
                <div class="panel__body stack-m">
                    <fieldset class="planner__set">
                        <legend>{{ __('Prüfling und Aufbau') }}</legend>
                        <div class="form-grid">
                            <div class="field"><label for="p-m">{{ __('Masse Prüfling') }}</label><div class="unit-input"><input class="input input--mono" id="p-m" name="m_dut" value="12" inputmode="decimal"><span>kg</span></div></div>
                            <div class="field"><label for="p-fix">{{ __('Vorrichtung / Kopfplatte') }}</label><div class="unit-input"><input class="input input--mono" id="p-fix" name="m_fix" value="8" inputmode="decimal"><span>kg</span></div></div>
                            <div class="field"><label for="p-axis">{{ __('Prüfachsen') }}</label>
                                <select class="select" id="p-axis" name="axis">
                                    <option value="v">{{ __('Nur vertikal') }}</option>
                                    <option value="h" selected>{{ __('Vertikal + Gleittisch') }}</option>
                                </select>
                            </div>
                            <div class="field" data-when-axis="h"><label for="p-slip">{{ __('Masse Gleittischplatte') }}</label><div class="unit-input"><input class="input input--mono" id="p-slip" name="m_slip" value="35" inputmode="decimal"><span>kg</span></div></div>
                        </div>
                    </fieldset>

                    <fieldset class="planner__set" data-part="sine">
                        <legend><label><input type="checkbox" name="use_sine" checked> {{ __('Sinus') }}</label></legend>
                        <div class="form-grid">
                            <div class="field"><label for="p-sf1">{{ __('Frequenz von') }}</label><div class="unit-input"><input class="input input--mono" id="p-sf1" name="s_f1" value="10" inputmode="decimal"><span>Hz</span></div></div>
                            <div class="field"><label for="p-sf2">{{ __('bis') }}</label><div class="unit-input"><input class="input input--mono" id="p-sf2" name="s_f2" value="2000" inputmode="decimal"><span>Hz</span></div></div>
                            <div class="field"><label for="p-sd">{{ __('Weg (Spitze-Spitze)') }}</label><div class="unit-input"><input class="input input--mono" id="p-sd" name="s_disp" value="{{ dec('1,5') }}" inputmode="decimal"><span>mm</span></div></div>
                            <div class="field"><label for="p-sa">{{ __('Beschleunigung') }}</label><div class="unit-input"><input class="input input--mono" id="p-sa" name="s_acc" value="10" inputmode="decimal"><span>g</span></div></div>
                        </div>
                        <p class="calc__note">{{ __('Weg leer lassen, wenn über den ganzen Bereich konstante Beschleunigung gilt.') }}</p>
                    </fieldset>

                    <fieldset class="planner__set" data-part="random">
                        <legend><label><input type="checkbox" name="use_random" checked> Random</label></legend>
                        <div class="field">
                            <label for="p-preset">{{ __('Profil') }}</label>
                            <select class="select" id="p-preset" name="r_preset">
                                <option value="gevs">{{ __('NASA GEVS – Qualifikation (14,1 grms)') }}</option>
                                <option value="navmat">{{ __('NAVMAT P-9492 (6,1 grms)') }}</option>
                                <option value="flat">{{ __('Flach 20–2000 Hz, 0,01 g²/Hz') }}</option>
                                <option value="custom">{{ __('Eigene Stützstellen') }}</option>
                            </select>
                        </div>
                        <table class="planner__psd">
                            <thead><tr><th scope="col">{{ __('Frequenz') }} (Hz)</th><th scope="col">PSD (g²/Hz)</th></tr></thead>
                            <tbody data-psd-rows></tbody>
                        </table>
                        <div class="planner__psd-actions">
                            <button type="button" class="btn btn--small" data-psd-add>{{ __('Stützstelle hinzufügen') }}</button>
                            <button type="button" class="btn btn--small" data-psd-remove>{{ __('Letzte entfernen') }}</button>
                        </div>
                    </fieldset>

                    <fieldset class="planner__set" data-part="shock">
                        <legend><label><input type="checkbox" name="use_shock" checked> {{ __('Schock (Halbsinus)') }}</label></legend>
                        <div class="form-grid">
                            <div class="field"><label for="p-ha">{{ __('Spitzenwert') }}</label><div class="unit-input"><input class="input input--mono" id="p-ha" name="h_acc" value="50" inputmode="decimal"><span>g</span></div></div>
                            <div class="field"><label for="p-hd">{{ __('Dauer') }}</label><div class="unit-input"><input class="input input--mono" id="p-hd" name="h_dur" value="11" inputmode="decimal"><span>ms</span></div></div>
                        </div>
                    </fieldset>
                </div>
            </form>

            <div class="planner__out">
                <div class="panel">
                    <div class="panel__head"><h2 class="hd-3">{{ __('Bedarf') }}</h2><span class="muted" style="font-size:.9rem">{{ __('ohne Anker') }}</span></div>
                    <div class="panel__body stack-m">
                        <dl class="results">
                            <div class="is-key"><dt>{{ __('Bewegte Masse') }}</dt><dd><span data-out="mass">–</span><small>kg</small></dd></div>
                            <div><dt>{{ __('Kraft Sinus') }}</dt><dd><span data-out="f-sine">–</span><small>kN</small></dd></div>
                            <div><dt>{{ __('Kraft Random (eff.)') }}</dt><dd><span data-out="f-random">–</span><small>kN</small></dd></div>
                            <div><dt>{{ __('Kraft Schock') }}</dt><dd><span data-out="f-shock">–</span><small>kN</small></dd></div>
                            <div><dt>{{ __('Weg max. (Spitze-Spitze)') }}</dt><dd><span data-out="disp">–</span><small>mm</small></dd></div>
                            <div><dt>{{ __('Geschwindigkeit max.') }}</dt><dd><span data-out="vel">–</span><small>m/s</small></dd></div>
                        </dl>
                        <p class="error-text" data-error hidden></p>
                        <p class="calc__note" data-out="detail"></p>
                    </div>
                </div>

                <div class="planner__pick" data-pick hidden>
                    <span class="planner__pick-label">{{ __('Empfehlung') }}</span>
                    <strong data-pick-name></strong>
                    <span data-pick-text></span>
                    <a class="btn btn--primary" data-inquiry href="{{ lroute('contact', ['thema' => 'pruefstand']) }}">{{ __('Anfrage mit dieser Auslegung') }}</a>
                </div>
            </div>

            <div class="panel planner__models">
                <div class="panel__head"><h2 class="hd-3">{{ __('Beispielauslegung: RMS-Serie') }}</h2><span class="muted" style="font-size:.9rem">{{ __('Auslastung inkl. Anker, Ziel ≤ 80 %') }}</span></div>
                <div class="planner__table-wrap">
                    <table class="planner__table">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Anlage') }}</th>
                                <th scope="col">{{ __('Sinus') }}</th>
                                <th scope="col">Random</th>
                                <th scope="col">{{ __('Schock') }}</th>
                                <th scope="col">{{ __('Weg') }}</th>
                                <th scope="col">{{ __('Geschw.') }}</th>
                                <th scope="col">{{ __('Last') }}</th>
                                <th scope="col">{{ __('Ergebnis') }}</th>
                            </tr>
                        </thead>
                        <tbody data-model-rows></tbody>
                    </table>
                </div>
                <div class="panel__body">
                    <p class="calc__note">{{ __('Beispielwerte nach Herstellerdatenblatt, ohne Gewähr. Vereinfachte Abschätzung: Schockweg und -geschwindigkeit bei symmetrischer Vor- und Nachpulskompensation (20 %), Random-Weg als 3σ-Spitze-Spitze. Verstärker, Kühlung, Gleittisch, Klimakammer und Aufstellung legen wir gemeinsam mit Ihnen und RMS fest.') }}</p>
                </div>
            </div>
        </div>
    </div>

    @include('partials.cta')
@endsection
