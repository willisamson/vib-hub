<footer class="site-footer">
    <div class="container">
        <div class="site-footer__grid">
            <div class="stack-m">
                <img src="{{ $site->logoUrl() }}" alt="{{ $site->name() }}" height="34" style="height:34px;width:auto">
                <p style="max-width: 34ch">{{ $site->get('footer_text') }}</p>
            </div>
            <div>
                <h2>{{ __('Leistungen') }}</h2>
                <ul>
                    <li><a href="{{ lroute('services.index') }}">{{ __('Alle Leistungen') }}</a></li>
                    <li><a href="{{ lroute('calculators') }}">{{ __('Berechnungen') }}</a></li>
                    <li><a href="{{ lroute('simulator') }}">{{ __('Regler-Simulator') }}</a></li>
                    <li><a href="{{ lroute('norms.index') }}">{{ __('Normen') }}</a></li>
                    <li><a href="{{ lroute('trainings.index') }}">{{ __('Schulungen') }}</a></li>
                    @if ($site->appUrl())
                        <li><a href="{{ lroute('app') }}">App: {{ $site->get('app_name') }}</a></li>
                    @endif
                </ul>
            </div>
            <div>
                <h2>{{ __('Kontakt') }}</h2>
                <ul>
                    @if ($site->get('email'))
                        <li><a href="mailto:{{ $site->get('email') }}">{{ $site->get('email') }}</a></li>
                    @endif
                    @if ($site->get('phone'))
                        <li><a href="tel:{{ preg_replace('/[^0-9+]/', '', $site->get('phone')) }}">{{ $site->get('phone') }}</a></li>
                    @endif
                    @if ($site->get('address'))
                        <li><address>{{ $site->get('address') }}</address></li>
                    @endif
                    <li><a href="{{ lroute('contact') }}">{{ __('Anfrageformular') }}</a></li>
                </ul>
            </div>
            <div>
                <h2>{{ __('Rechtliches') }}</h2>
                <ul>
                    <li><a href="{{ lroute('imprint') }}">{{ __('Impressum') }}</a></li>
                    <li><a href="{{ lroute('privacy') }}">{{ __('Datenschutz') }}</a></li>
                    @if ($site->get('linkedin'))
                        <li><a href="{{ $site->get('linkedin') }}" rel="noopener" target="_blank">LinkedIn</a></li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="site-footer__base">
            <span>© {{ date('Y') }} {{ $site->name() }}</span>
            <span>{{ __('Schwingungsprüfung, Analyse und Resonanzbeseitigung') }}</span>
        </div>
    </div>
</footer>
