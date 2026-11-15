@extends('layouts.app')

@section('title', __('Regler-Simulator'))
@section('description', __('Schwingregler im Browser: Sinus, Random und Schock mit Sollkurve, Warn- und Abschaltgrenzen – so verhält sich die Regelung bei Resonanzen.'))

@section('content')
    @include('partials.page-head', [
        'title' => __('Regler-Simulator'),
        'lede' => __('Erleben Sie, wie ein Schwingregler arbeitet: Sollkurve, Warn- und Abschaltgrenzen, Pegelstufen und Regelschleife – für Sinus, Random und Schock. Wählen Sie ein Szenario und starten Sie die Prüfung.'),
        'crumbs' => [__('Berechnungen') => lroute('calculators'), __('Regler-Simulator') => null],
    ])

    <div class="section" style="padding-top: 2.5rem">
        <div class="container">
            <div class="sim" data-sim data-mode="sine">
                <div class="sim__top">
                    <div class="sim__modes" role="tablist" aria-label="{{ __('Prüfart') }}">
                        <button type="button" role="tab" data-sim-mode="sine" aria-selected="true">{{ __('Sinus') }}</button>
                        <button type="button" role="tab" data-sim-mode="random" aria-selected="false">Random</button>
                        <button type="button" role="tab" data-sim-mode="shock" aria-selected="false">{{ __('Schock') }}</button>
                    </div>
                    <div class="sim__status" data-sim-status data-state="idle" role="status" aria-live="polite">
                        <span class="sim__lamp" aria-hidden="true"></span>
                        <strong data-sim-status-text>{{ __('Bereit') }}</strong>
                    </div>
                </div>

                <div class="sim__body">
                    <aside class="sim__setup">
                        <form data-sim-setup="sine" onsubmit="return false">
                            <h2>{{ __('Sinus-Sweep') }}</h2>
                            <div class="form-grid">
                                <div class="field"><label>{{ __('Start') }}</label><div class="unit-input"><input class="input input--mono" name="f1" value="10" inputmode="decimal"><span>Hz</span></div></div>
                                <div class="field"><label>{{ __('Ende') }}</label><div class="unit-input"><input class="input input--mono" name="f2" value="2000" inputmode="decimal"><span>Hz</span></div></div>
                                <div class="field"><label>{{ __('Weg (Spitze)') }}</label><div class="unit-input"><input class="input input--mono" name="disp" value="{{ dec('0,75') }}" inputmode="decimal"><span>mm</span></div></div>
                                <div class="field"><label>{{ __('Beschleunigung') }}</label><div class="unit-input"><input class="input input--mono" name="acc" value="5" inputmode="decimal"><span>g</span></div></div>
                                <div class="field"><label>{{ __('Sweep-Rate') }}</label><div class="unit-input"><input class="input input--mono" name="rate" value="1" inputmode="decimal"><span>{{ __('Okt/min') }}</span></div></div>
                                <div class="field"><label>{{ __('Zeitraffer') }}</label>
                                    <select class="select" name="speed"><option value="5">5×</option><option value="10" selected>10×</option><option value="30">30×</option></select>
                                </div>
                                <div class="field"><label>{{ __('Warngrenze') }}</label><div class="unit-input"><input class="input input--mono" name="warn" value="3" inputmode="decimal"><span>±dB</span></div></div>
                                <div class="field"><label>{{ __('Abschaltgrenze') }}</label><div class="unit-input"><input class="input input--mono" name="abort" value="6" inputmode="decimal"><span>±dB</span></div></div>
                            </div>
                        </form>

                        <form data-sim-setup="random" onsubmit="return false" hidden>
                            <h2>Random</h2>
                            <div class="form-grid">
                                <div class="field full"><label>{{ __('Profil') }}</label>
                                    <select class="select" name="profile">
                                        <option value="navmat">NAVMAT P-9492 ({{ dec('6,06') }} Grms)</option>
                                        <option value="gevs">NASA GEVS ({{ dec('14,1') }} Grms)</option>
                                        <option value="flat">{{ __('Flach 20–2000 Hz') }} ({{ dec('4,45') }} Grms)</option>
                                    </select>
                                </div>
                                <div class="field"><label>{{ __('Freiheitsgrade') }}</label><div class="unit-input"><input class="input input--mono" name="dof" value="120" inputmode="numeric"><span>DOF</span></div></div>
                                <div class="field"><label>{{ __('Prüfdauer') }}</label><div class="unit-input"><input class="input input--mono" name="duration" value="5" inputmode="decimal"><span>min</span></div></div>
                                <div class="field"><label>{{ __('Warngrenze') }}</label><div class="unit-input"><input class="input input--mono" name="warn" value="3" inputmode="decimal"><span>±dB</span></div></div>
                                <div class="field"><label>{{ __('Abschaltgrenze') }}</label><div class="unit-input"><input class="input input--mono" name="abort" value="6" inputmode="decimal"><span>±dB</span></div></div>
                            </div>
                        </form>

                        <form data-sim-setup="shock" onsubmit="return false" hidden>
                            <h2>{{ __('Halbsinus-Schock') }}</h2>
                            <div class="form-grid">
                                <div class="field"><label>{{ __('Spitzenwert') }}</label><div class="unit-input"><input class="input input--mono" name="peak" value="30" inputmode="decimal"><span>g</span></div></div>
                                <div class="field"><label>{{ __('Dauer') }}</label><div class="unit-input"><input class="input input--mono" name="duration" value="11" inputmode="decimal"><span>ms</span></div></div>
                                <div class="field"><label>{{ __('Anzahl Pulse') }}</label><input class="input input--mono" name="count" value="3" inputmode="numeric"></div>
                                <div class="field"><label>{{ __('Hub der Anlage') }}</label><div class="unit-input"><input class="input input--mono" name="stroke" value="51" inputmode="decimal"><span>mm</span></div></div>
                                <div class="field"><label>{{ __('Toleranzband') }}</label><div class="unit-input"><input class="input input--mono" name="tol" value="15" inputmode="decimal"><span>±%</span></div></div>
                                <div class="field"><label>{{ __('Abschaltgrenze') }}</label><div class="unit-input"><input class="input input--mono" name="abortTol" value="30" inputmode="decimal"><span>±%</span></div></div>
                            </div>
                        </form>

                        <fieldset class="sim__global" data-sim-global>
                            <legend>{{ __('Prüfaufbau') }}</legend>
                            <p class="sim__hint">{{ __('Tragen Sie die Resonanzen Ihres Aufbaus ein. Eine hohe Güte bedeutet geringe Dämpfung und eine schärfere, schwerer zu regelnde Resonanz.') }}</p>
                            <div class="sim__reso">
                                <span class="sim__reso-tag">R1</span>
                                <div class="field"><label for="sim-r1f">{{ __('Resonanz 1') }}</label><div class="unit-input"><input class="input input--mono" id="sim-r1f" name="r1f" value="1200" inputmode="decimal"><span>Hz</span></div></div>
                                <div class="field"><label for="sim-r1q">{{ __('Güte Q') }}</label><input class="input input--mono" id="sim-r1q" name="r1q" value="20" inputmode="decimal"></div>
                            </div>
                            <div class="sim__reso">
                                <label class="sim__reso-tag sim__reso-toggle" title="{{ __('Zweite Resonanz verwenden') }}"><input type="checkbox" name="r2on" checked> R2</label>
                                <div class="field"><label for="sim-r2f">{{ __('Resonanz 2') }}</label><div class="unit-input"><input class="input input--mono" id="sim-r2f" name="r2f" value="1900" inputmode="decimal"><span>Hz</span></div></div>
                                <div class="field"><label for="sim-r2q">{{ __('Güte Q') }}</label><input class="input input--mono" id="sim-r2q" name="r2q" value="30" inputmode="decimal"></div>
                            </div>
                            <div class="sim__presets" role="group" aria-label="{{ __('Vorlagen') }}">
                                <button type="button" class="sim__preset" data-sim-preset="1200,20,1900,30">1200 / 1900 Hz</button>
                                <button type="button" class="sim__preset" data-sim-preset="320,12,,">{{ __('Eine Resonanz') }} 320 Hz</button>
                                <button type="button" class="sim__preset" data-sim-preset="1200,150,1900,120">{{ __('Kaum gedämpft') }}</button>
                            </div>
                            <div class="field">
                                <label for="sim-scenario">{{ __('Verhalten des Prüflings') }}</label>
                                <select class="select" id="sim-scenario" name="scenario">
                                    <option value="normal">{{ __('Bleibt fest eingespannt') }}</option>
                                    <option value="loose">{{ __('Löst sich während der Prüfung') }}</option>
                                </select>
                            </div>
                        </fieldset>

                        <div class="sim__controls">
                            <button type="button" class="btn btn--primary" data-sim-action="start">{{ __('Start') }}</button>
                            <button type="button" class="btn" data-sim-action="pause" disabled>{{ __('Pause') }}</button>
                            <button type="button" class="btn sim__stop" data-sim-action="stop" disabled>{{ __('Stopp') }}</button>
                        </div>
                    </aside>

                    <div class="sim__main">
                        <div class="sim__plot">
                            <canvas data-sim-canvas role="img" aria-label="{{ __('Sollkurve, Grenzen und Regelkurve') }}"></canvas>
                        </div>
                        <ul class="sim__legend" aria-label="{{ __('Legende') }}">
                            <li><i class="is-ref"></i>{{ __('Sollkurve') }}</li>
                            <li><i class="is-ctrl"></i>{{ __('Regelkurve (Ist)') }}</li>
                            <li><i class="is-warn"></i>{{ __('Warngrenze') }}</li>
                            <li><i class="is-abort"></i>{{ __('Abschaltgrenze') }}</li>
                        </ul>
                        <dl class="sim__readouts" data-sim-readouts></dl>
                        <div class="sim__logwrap">
                            <h2>{{ __('Ereignisprotokoll') }}</h2>
                            <ol class="sim__log" data-sim-log></ol>
                        </div>
                    </div>
                </div>
            </div>

            <p class="calc__note" style="margin-top:1.4rem; max-width: 80ch">{{ __('Vereinfachtes Lehrmodell mit einem Prüfaufbau aus Schwingerreger, Vorrichtung und bis zu zwei einstellbaren Resonanzen. Es zeigt das Prinzip der Regelung und ersetzt keine Prüfstandssoftware. Für echte Prüfungen beraten wir Sie gern.') }}</p>
        </div>
    </div>
@endsection
