@if ($site->appUrl())
<section class="section app-promo" id="app" aria-labelledby="app-title">
    <div class="container app-promo__grid">
        <div class="app-promo__copy">
            <p class="app-badge"><x-glyph name="phone-app" /> {{ __('Android-App von vib-hub') }}</p>
            <h2 class="hd-2" id="app-title">{{ $site->get('app_name') }}</h2>
            <p class="lede">{{ $site->get('app_text') }}</p>
            <ul class="checklist app-promo__list">
                <li><x-glyph name="check" /><span>{{ __('Maximal-, Summen- und Gesamtkräfte für den Schwingerreger') }}</span></li>
                <li><x-glyph name="check" /><span>{{ __('Spitze-Spitze-Hub mit Grenzwertprüfung') }}</span></li>
                <li><x-glyph name="check" /><span>{{ __('Einfacher Modus (a') }}<sub>rms</sub> {{ __('je Frequenz) oder PSD-Modus in g²/Hz') }}</span></li>
                <li><x-glyph name="check" /><span>{{ __('Diagramme linear und logarithmisch, Export als PDF oder PNG') }}</span></li>
                <li><x-glyph name="check" /><span>{{ __('Deutsch, Englisch, Tschechisch – komplett offline') }}</span></li>
            </ul>
            <div class="cta__actions">
                <a class="btn btn--primary" href="{{ $site->appUrl() }}" target="_blank" rel="noopener"><x-glyph name="download" /> {{ __('Bei Google Play laden') }}</a>
                @unless (request()->routeIs('app', 'en.app'))
                    <a class="btn" href="{{ lroute('app') }}">{{ __('Mehr zur App') }}</a>
                @endunless
            </div>
            <p class="app-promo__note">{{ __('Ohne Werbung und ohne Datensammlung.') }}</p>
        </div>
        <div class="app-promo__visual">
            @include('partials.app-phone')
        </div>
    </div>
</section>
@endif
