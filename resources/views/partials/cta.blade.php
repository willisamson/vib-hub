<section class="section section--bolts" aria-labelledby="cta-title">
    <div class="container cta">
        <div>
            <h2 class="hd-1" id="cta-title">{{ __('Was bringt Ihr Produkt zum Schwingen?') }}</h2>
            <p class="lede">{{ __('Schicken Sie uns Ihre Spezifikation, eine Messung oder einfach eine Beschreibung des Problems. Sie bekommen eine fachliche Rückmeldung – kein Formschreiben.') }}</p>
            <div class="cta__actions">
                <a class="btn btn--primary" href="{{ lroute('contact') }}">{{ __('Anfrage stellen') }}</a>
                @if ($site->get('phone'))
                    <a class="btn" href="tel:{{ preg_replace('/[^0-9+]/', '', $site->get('phone')) }}"><x-glyph name="phone" /> {{ $site->get('phone') }}</a>
                @elseif ($site->get('email'))
                    <a class="btn" href="mailto:{{ $site->get('email') }}"><x-glyph name="mail" /> {{ $site->get('email') }}</a>
                @endif
            </div>
        </div>
        <dl class="kv">
            @foreach ($site->stats() as $stat)
                <div><dt>{{ $stat['label'] ?? '' }}</dt><dd>{{ $stat['value'] ?? '' }}{{ $stat['suffix'] ?? '' }}</dd></div>
            @endforeach
        </dl>
    </div>
</section>
