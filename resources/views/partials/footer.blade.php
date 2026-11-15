<footer class="site-footer">
    <div class="container">
        <div class="site-footer__grid">
            <div class="stack-m">
                <img src="{{ $site->logoUrl() }}" alt="{{ $site->name() }}" height="34" style="height:34px;width:auto">
                <p style="max-width: 34ch">{{ $site->get('footer_text') }}</p>
            </div>
            <div>
                <h2>{{ __('Übersicht') }}</h2>
                <ul>
                    @foreach ($menu->items('footer') as $item)
                        <li><a href="{{ $item->href() }}" @if ($item->new_tab) target="_blank" rel="noopener" @endif>{{ $item->route === 'app' && $item->label === 'App' ? __('App').': '.$site->get('app_name') : $item->text() }}</a></li>
                    @endforeach
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
                    @if ($site->accountsEnabled())
                        <li><a href="{{ $customer ? lroute('account.dashboard') : lroute('account.register') }}">{{ $customer ? __('Mein Konto') : __('Kundenkonto anlegen') }}</a></li>
                    @endif
                </ul>
            </div>
            <div>
                <h2>{{ __('Rechtliches') }}</h2>
                <ul>
                    @foreach ($menu->items('legal') as $item)
                        <li><a href="{{ $item->href() }}" @if ($item->new_tab) target="_blank" rel="noopener" @endif>{{ $item->text() }}</a></li>
                    @endforeach
                    <li><button type="button" class="footer-link" data-consent-open>{{ __('Cookie-Einstellungen') }}</button></li>
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
