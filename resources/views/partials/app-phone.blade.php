{{-- Smartphone mit Screenshot (aus dem Admin) oder gezeichneter Illustration --}}
<div class="phone" aria-hidden="{{ $site->appImageUrl() ? 'false' : 'true' }}">
    <div class="phone__screen">
        @if ($site->appImageUrl())
            <img src="{{ $site->appImageUrl() }}" alt="Screenshot der App {{ $site->get('app_name') }}" loading="lazy">
        @else
            <div class="phone__bar"><span>{{ $site->get('app_name') }}</span></div>
            <div class="phone__tabs"><span>{{ __('Einfach') }}</span><span aria-current="true">PSD</span></div>
            <svg class="phone__chart" viewBox="0 0 220 140" role="presentation">
                <g stroke="var(--line)" stroke-width="1">
                    <line x1="24" y1="10" x2="24" y2="118"/><line x1="24" y1="118" x2="214" y2="118"/>
                    <line x1="24" y1="46" x2="214" y2="46" stroke-dasharray="2 3"/><line x1="24" y1="82" x2="214" y2="82" stroke-dasharray="2 3"/>
                    <line x1="87" y1="10" x2="87" y2="118" stroke-dasharray="2 3"/><line x1="150" y1="10" x2="150" y2="118" stroke-dasharray="2 3"/>
                </g>
                <path d="M28 88 L66 50 L110 50 L206 96 L206 118 L28 118 Z" fill="rgba(109,63,224,.12)"/>
                <path d="M28 88 L66 50 L110 50 L206 96" fill="none" stroke="var(--random)" stroke-width="2.2" stroke-linejoin="round"/>
                <g fill="var(--random)"><circle cx="28" cy="88" r="3"/><circle cx="66" cy="50" r="3"/><circle cx="110" cy="50" r="3"/><circle cx="206" cy="96" r="3"/></g>
                <g font-family="var(--font-mono)" font-size="8" fill="var(--muted)">
                    <text x="20" y="130" text-anchor="middle">20</text><text x="87" y="130" text-anchor="middle">100</text><text x="150" y="130" text-anchor="middle">500</text><text x="206" y="130" text-anchor="middle">2k</text>
                </g>
            </svg>
            <dl class="phone__values">
                <div><dt>Grms</dt><dd>{{ dec('6,06') }} g</dd></div>
                <div><dt>{{ __('Kraft 3σ') }}</dt><dd>{{ dec('2,14') }} kN</dd></div>
                <div><dt>{{ __('Hub p-p') }}</dt><dd>{{ dec('1,16') }} mm</dd></div>
                <div class="is-ok"><dt>{{ __('Grenzwert') }}</dt><dd>OK</dd></div>
            </dl>
            <div class="phone__btn">{{ __('Als PDF exportieren') }}</div>
        @endif
    </div>
</div>
