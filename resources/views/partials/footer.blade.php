<footer class="site-footer">
    <div class="container">
        <div class="site-footer__grid">
            <div class="stack-m">
                <img src="{{ $site->logoUrl() }}" alt="{{ $site->name() }}" height="34" style="height:34px;width:auto">
                <p style="max-width: 34ch">{{ $site->get('footer_text') }}</p>
            </div>
            <div>
                <h2>Leistungen</h2>
                <ul>
                    <li><a href="{{ route('services.index') }}">Alle Leistungen</a></li>
                    <li><a href="{{ route('calculators') }}">Berechnungen</a></li>
                    <li><a href="{{ route('norms.index') }}">Normen</a></li>
                    <li><a href="{{ route('trainings.index') }}">Schulungen</a></li>
                </ul>
            </div>
            <div>
                <h2>Kontakt</h2>
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
                    <li><a href="{{ route('contact') }}">Anfrageformular</a></li>
                </ul>
            </div>
            <div>
                <h2>Rechtliches</h2>
                <ul>
                    <li><a href="{{ route('imprint') }}">Impressum</a></li>
                    <li><a href="{{ route('privacy') }}">Datenschutz</a></li>
                    @if ($site->get('linkedin'))
                        <li><a href="{{ $site->get('linkedin') }}" rel="noopener" target="_blank">LinkedIn</a></li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="site-footer__base">
            <span>© {{ date('Y') }} {{ $site->name() }}</span>
            <span>Schwingungsprüfung, Analyse und Resonanzbeseitigung</span>
        </div>
    </div>
</footer>
