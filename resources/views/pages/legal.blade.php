@php
    $isImprint = $type === 'imprint';
    $title = $isImprint ? __('Impressum') : __('Datenschutzerklärung');
    $text = (string) $site->get($isImprint ? 'imprint' : 'privacy', '');
    $paragraphs = array_values(array_filter(preg_split("/\R{2,}/", trim($text)) ?: [], fn ($p) => trim($p) !== ''));
@endphp
@extends('layouts.app')

@section('title', $title)

@section('content')
    @include('partials.page-head', ['title' => $title, 'crumbs' => [$title => null]])

    <div class="section">
        <div class="container">
            @if (count($paragraphs))
                <div class="prose">
                    @foreach ($paragraphs as $p)
                        <p>{!! nl2br(e($p)) !!}</p>
                    @endforeach
                </div>
            @else
                <div class="empty">{{ __('Dieser Text wird gerade erstellt.') }}</div>
            @endif

            @unless ($isImprint)
                <section class="panel cookie-table" id="cookies" style="margin-top:2.5rem">
                    <div class="panel__head">
                        <h2>{{ __('Cookies auf dieser Website') }}</h2>
                        <button type="button" class="btn btn--small" data-consent-open>{{ __('Cookie-Einstellungen ändern') }}</button>
                    </div>
                    <div class="panel__body">
                        <table>
                            <thead><tr><th>{{ __('Name') }}</th><th>{{ __('Zweck') }}</th><th>{{ __('Kategorie') }}</th><th>{{ __('Speicherdauer') }}</th></tr></thead>
                            <tbody>
                                <tr><td class="mono">{{ config('session.cookie') }}</td><td>{{ __('Hält Ihre Sitzung aufrecht, z. B. für Formulare und die Anmeldung im Kundenkonto.') }}</td><td>{{ __('Notwendig') }}</td><td>{{ __(':count Minuten', ['count' => config('session.lifetime', 120)]) }}</td></tr>
                                <tr><td class="mono">XSRF-TOKEN</td><td>{{ __('Schützt Formulare vor gefälschten Absendungen.') }}</td><td>{{ __('Notwendig') }}</td><td>{{ __(':count Minuten', ['count' => config('session.lifetime', 120)]) }}</td></tr>
                                <tr><td class="mono">remember_customer_*</td><td>{{ __('Nur bei „Angemeldet bleiben“: hält Sie im Kundenkonto angemeldet.') }}</td><td>{{ __('Notwendig') }}</td><td>{{ __('bis zur Abmeldung') }}</td></tr>
                                <tr><td class="mono">vibhub_consent</td><td>{{ __('Speichert Ihre Cookie-Auswahl.') }}</td><td>{{ __('Notwendig') }}</td><td>{{ __('180 Tage') }}</td></tr>
                                @if (filled($site->get('consent_stats_snippet')))
                                    <tr><td>{{ $site->get('consent_stats_name') ?: __('Besucherstatistik') }}</td><td>{{ __('Auswertung der Seitennutzung, nur nach Einwilligung.') }}</td><td>{{ __('Statistik') }}</td><td>{{ __('laut Anbieter') }}</td></tr>
                                @endif
                            </tbody>
                        </table>
                        <p class="muted" style="margin-top:1rem; font-size:.92rem">{{ __('Schriften und alle Dateien dieser Website werden von unserem eigenen Server geladen, nicht von Drittanbietern.') }}</p>
                        @if ($site->statsEnabled())
                            <h3 style="margin-top:1.4rem; font-size:1.05rem">{{ __('Besucherstatistik') }}</h3>
                            <p style="margin-top:.4rem; font-size:.95rem">{{ $site->statsRequireConsent()
                                ? __('Mit Ihrer Einwilligung in „Statistik“ zählen wir Seitenaufrufe auf unserem eigenen Server, ohne Cookies. Gespeichert werden die aufgerufene Seite, Datum, Sprache, Gerätetyp (Computer, Tablet, Smartphone) und die Domain der verweisenden Website. Ihre IP-Adresse speichern wir nicht; um Besucher eines Tages nur einmal zu zählen, bilden wir einen täglich wechselnden, nicht rückrechenbaren Prüfwert. Die Daten werden nach spätestens 400 Tagen gelöscht und nicht an Dritte weitergegeben.')
                                : __('Wir zählen Seitenaufrufe auf unserem eigenen Server, ohne Cookies und ohne Daten an Dritte weiterzugeben. Gespeichert werden die aufgerufene Seite, Datum, Sprache, Gerätetyp (Computer, Tablet, Smartphone) und die Domain der verweisenden Website. Ihre IP-Adresse speichern wir nicht; um Besucher eines Tages nur einmal zu zählen, bilden wir einen täglich wechselnden, nicht rückrechenbaren Prüfwert. Rückschlüsse auf Ihre Person sind damit nicht möglich. Die Daten werden nach spätestens 400 Tagen gelöscht. Ist in Ihrem Browser „Do Not Track“ oder „Global Privacy Control“ aktiviert, zählen wir Sie nicht.') }}</p>
                        @endif
                        <h3 style="margin-top:1.4rem; font-size:1.05rem">{{ __('Weiterleitung an unseren Anlagenpartner') }}</h3>
                        <p style="margin-top:.4rem; font-size:.95rem">{{ __('Bei Anfragen zu Schwingprüfanlagen können Sie im Anfrageformular zustimmen, dass wir Ihre Anfrage (Name, Firma, Kontaktdaten und Nachricht) zur Erstellung eines Angebots an :partner weiterleiten. Ohne diese Zustimmung geben wir Ihre Angaben nicht weiter. Sie können die Zustimmung jederzeit per E-Mail an uns widerrufen.', ['partner' => $site->partnerName()]) }}</p>
                    </div>
                </section>
            @endunless
        </div>
    </div>
@endsection
