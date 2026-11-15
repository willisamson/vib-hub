<aside class="account-aside">
    <div class="panel">
        <div class="panel__body stack-m">
            <h2 class="hd-3">{{ __('Ihr Kundenkonto') }}</h2>
            <ul class="checklist">
                <li><x-glyph name="check" /><span>{{ __('Anfragen ohne erneutes Ausfüllen der Kontaktdaten') }}</span></li>
                <li><x-glyph name="check" /><span>{{ __('Bearbeitungsstand jeder Anfrage auf einen Blick') }}</span></li>
                <li><x-glyph name="check" /><span>{{ __('Antworten unseres Teams direkt im Konto') }}</span></li>
                <li><x-glyph name="check" /><span>{{ __('Konto und Daten jederzeit selbst löschen') }}</span></li>
            </ul>
            <p class="muted" style="font-size:.92rem">{{ __('Kein Konto nötig? Sie können uns auch ohne Anmeldung schreiben.') }} <a class="link" href="{{ lroute('contact') }}">{{ __('Zum Anfrageformular') }}</a></p>
        </div>
    </div>
</aside>
