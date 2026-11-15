@php
    $hasStats = filled($site->get('consent_stats_snippet'));
    $statsName = $site->get('consent_stats_name') ?: __('Besucherstatistik');
@endphp
{{-- Cookie-Einwilligung: erscheint, bis eine Auswahl gespeichert ist; über „Cookie-Einstellungen“ im Seitenfuß jederzeit änderbar --}}
<section class="consent" data-consent data-version="{{ $site->consentVersion() }}" role="dialog" aria-modal="false" aria-labelledby="consent-title" aria-describedby="consent-text" hidden>
    <div class="consent__inner">
        <div class="consent__head">
            <span class="consent__icon" aria-hidden="true"><x-glyph name="cookie" /></span>
            <h2 id="consent-title">{{ $site->get('consent_title') }}</h2>
        </div>
        <p id="consent-text">{{ $site->get('consent_text') }} <a class="link" href="{{ lroute('privacy') }}#cookies">{{ __('Mehr in der Datenschutzerklärung') }}</a>.</p>

        <form class="consent__prefs" data-consent-prefs hidden onsubmit="return false">
            <label class="consent__cat">
                <input type="checkbox" checked disabled>
                <span><strong>{{ __('Notwendig') }}</strong> <em>{{ __('immer aktiv') }}</em>
                    <small>{{ __('Sitzung, Schutz vor gefälschten Formularen, Anmeldung im Kundenkonto und das Speichern dieser Auswahl.') }}</small></span>
            </label>
            @if ($hasStats)
                <label class="consent__cat">
                    <input type="checkbox" name="stats" data-consent-cat="stats">
                    <span><strong>{{ __('Statistik') }}</strong>
                        <small>{{ __(':name hilft uns zu verstehen, welche Seiten genutzt werden. Die Auswertung erfolgt ohne Werbung.', ['name' => $statsName]) }}</small></span>
                </label>
            @endif
            <label class="consent__cat">
                <input type="checkbox" name="media" data-consent-cat="media">
                <span><strong>{{ __('Externe Medien') }}</strong>
                    <small>{{ __('Eingebettete Videos oder Karten anderer Anbieter. Beim Laden werden Daten wie Ihre IP-Adresse an den Anbieter übertragen.') }}</small></span>
            </label>
        </form>

        <div class="consent__actions">
            <button type="button" class="btn btn--primary" data-consent-all>{{ __('Alle akzeptieren') }}</button>
            <button type="button" class="btn btn--primary" data-consent-necessary>{{ __('Nur notwendige') }}</button>
            <button type="button" class="btn" data-consent-toggle aria-expanded="false">{{ __('Einstellungen') }}</button>
            <button type="button" class="btn" data-consent-save hidden>{{ __('Auswahl speichern') }}</button>
        </div>
    </div>
</section>

@if ($hasStats)
    {{-- Wird erst nach Einwilligung in „Statistik“ eingefügt und ausgeführt --}}
    <template data-consent-load="stats">{!! $site->get('consent_stats_snippet') !!}</template>
@endif
