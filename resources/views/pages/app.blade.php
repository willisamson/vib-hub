@extends('layouts.app')

@section('title', $site->get('app_name').' – App')
@section('description', $site->get('app_text'))

@section('content')
    @include('partials.page-head', [
        'title' => 'Die App für Schwingprüfungen',
        'lede' => 'Die Android-App von vib-hub für alle, die Schwingprüfungen planen: Kräfte und Hubwege aus dem Prüfprofil – in Sekunden, auch ohne Netz im Prüffeld.',
        'crumbs' => ['App' => null],
    ])

    @include('partials.app-promo')

    <section class="section section--panel" aria-labelledby="modes-title">
        <div class="container">
            <div class="section-head">
                <h2 class="hd-2" id="modes-title">Zwei Wege zum Ergebnis</h2>
                <p class="lede">Je nachdem, wie Ihre Spezifikation vorliegt, geben Sie Beschleunigungen oder ein Leistungsdichtespektrum ein.</p>
            </div>
            <div class="cards">
                <div class="card">
                    <x-glyph name="sine" />
                    <h3>Einfacher Modus</h3>
                    <p>Sie geben den Effektivwert der Beschleunigung a<sub>rms</sub> je Frequenz ein – ideal für schnelle Abschätzungen und Messdaten.</p>
                </div>
                <div class="card">
                    <x-glyph name="random" style="color: var(--random)" />
                    <h3>PSD-Modus</h3>
                    <p>Sie definieren Stützstellen des Leistungsdichtespektrums in g²/Hz – so, wie Profile in Normen und Lastenheften stehen.</p>
                </div>
                <div class="card">
                    <x-glyph name="shield" />
                    <h3>Für wen?</h3>
                    <p>Für Ingenieure, Prüftechniker und Studierende in Schwingprüfung und Mechanik. Die Ergebnisse dienen der Auslegung und Dokumentation und ersetzen keine Prüfstandssoftware.</p>
                </div>
            </div>
            <p style="margin-top:2rem" class="muted">Lieber am Rechner? Den <a class="link" href="{{ route('calculators') }}#random">Grms-Rechner</a> gibt es auch hier auf der Website.</p>
        </div>
    </section>

    @include('partials.cta')
@endsection
