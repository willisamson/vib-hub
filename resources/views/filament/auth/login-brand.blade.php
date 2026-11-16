@php
    $site = app(\App\Support\Site::class);
@endphp
{{-- Markenbereich der Admin-Anmeldung. Styles inline, da außerhalb des Filament-Themes. --}}
<style>
    .fi-simple-layout:has(> .vh-brand) { display: grid; grid-template-columns: minmax(360px, 44%) 1fr; align-items: stretch; justify-content: stretch; min-height: 100vh; min-height: 100dvh; padding: 0; background: #f2f5f7; }
    .fi-simple-layout:has(> .vh-brand) .fi-simple-main-ctn { display: flex; align-items: center; justify-content: center; padding: 3rem 1.5rem; min-height: 0; }
    .fi-simple-layout:has(> .vh-brand) .fi-simple-main { width: 100%; max-width: 27rem; margin: 0; box-shadow: 0 24px 60px -30px rgba(11, 26, 38, .35); }
    .fi-simple-layout:has(> .vh-brand) .fi-simple-header-heading { font-size: 1.6rem; letter-spacing: -.01em; }
    .vh-brand { position: relative; overflow: hidden; display: grid; grid-template-rows: auto 1fr auto auto; gap: 2rem; min-height: 100vh; min-height: 100dvh; padding: clamp(2rem, 5vw, 3.5rem); background: #0c1c26; color: #c9d6de; }
    .vh-brand__logo img { height: clamp(2.6rem, 4vw, 3.4rem); width: auto; max-width: 100%; }
    .vh-brand__copy { position: relative; z-index: 1; max-width: 26rem; }
    .vh-brand__copy h2 { margin: 0 0 .6rem; font-size: clamp(1.6rem, 2.6vw, 2.2rem); line-height: 1.15; font-weight: 600; color: #fff; letter-spacing: -.015em; }
    .vh-brand__copy p { margin: 0; font-size: 1rem; line-height: 1.55; color: #9fb3bf; }
    .vh-brand__scope { width: 100%; height: 100%; min-height: 160px; max-height: 340px; align-self: center; }
    .vh-brand__scope path.trace { fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; stroke-dasharray: 1000; stroke-dashoffset: 1000; animation: vh-draw 2.6s cubic-bezier(.4, .1, .2, 1) forwards; }
    .vh-brand__scope path.t2 { animation-delay: .35s; }
    .vh-brand__scope path.t3 { animation-delay: .7s; }
    @keyframes vh-draw { to { stroke-dashoffset: 0; } }
    .vh-brand__foot { position: relative; z-index: 1; display: flex; justify-content: space-between; gap: 1rem; flex-wrap: wrap; font-size: .875rem; color: #7f95a2; }
    .vh-brand__foot a { color: #c9d6de; text-decoration: none; }
    .vh-brand__foot a:hover { color: #fff; text-decoration: underline; }
    .vh-back { display: block; margin-top: 1.2rem; text-align: center; font-size: .875rem; color: rgb(100 116 139); }
    .vh-back:hover { color: #0b7fa0; }
    @media (max-width: 900px) {
        .fi-simple-layout:has(> .vh-brand) { grid-template-columns: 1fr; grid-template-rows: auto 1fr; }
        .vh-brand { min-height: 0; grid-template-rows: auto auto; padding: 1.4rem 1.5rem; gap: .8rem; }
        .vh-brand__copy p, .vh-brand__foot, .vh-brand__scope { display: none; }
        .vh-brand__copy h2 { font-size: 1.15rem; margin: 0; }
        .fi-simple-layout:has(> .vh-brand) .fi-simple-main-ctn { padding: 2rem 1rem; align-items: flex-start; }
    }
    @media (prefers-reduced-motion: reduce) { .vh-brand__scope path.trace { animation: none; stroke-dashoffset: 0; } }
</style>

<aside class="vh-brand" aria-label="{{ $site->name() }}">
    <a class="vh-brand__logo" href="{{ url('/') }}">
        <img src="{{ $site->logoOnDarkUrl() }}" alt="{{ $site->name() }}">
    </a>

    <svg class="vh-brand__scope" viewBox="0 0 600 260" preserveAspectRatio="none" aria-hidden="true">
        <g stroke="rgba(201,214,222,.07)" stroke-width="1">
            @for ($x = 0; $x <= 600; $x += 60)<line x1="{{ $x }}" y1="0" x2="{{ $x }}" y2="260"/>@endfor
            @for ($y = 0; $y <= 260; $y += 52)<line x1="0" y1="{{ $y }}" x2="600" y2="{{ $y }}"/>@endfor
        </g>
        @php
            $sine = collect(range(0, 120))->map(fn ($i) => round($i * 5, 1).','.round(70 + 34 * sin($i * 5 / 600 * 2 * M_PI * (2 + $i / 60)), 1))->implode(' L');
            mt_srand(7);
            $rand = collect(range(0, 150))->map(fn ($i) => round($i * 4, 1).','.round(140 + 26 * (mt_rand(-100, 100) / 100) * (0.5 + 0.5 * sin($i / 150 * M_PI)), 1))->implode(' L');
            $shock = collect(range(0, 120))->map(function ($i) {
                $x = $i * 5;
                $t = ($x - 260) / 70;
                $y = ($t >= 0 && $t <= 1) ? 215 - 52 * sin(M_PI * $t) : 215 + (($t > 1 && $t < 1.6) ? 9 * sin(M_PI * ($t - 1) / 0.6) : 0);
                return $x.','.round($y, 1);
            })->implode(' L');
        @endphp
        <path class="trace" pathLength="1000" d="M{{ $sine }}" stroke="#2bb3d6"/>
        <path class="trace t2" pathLength="1000" d="M{{ $rand }}" stroke="#9b7bf0"/>
        <path class="trace t3" pathLength="1000" d="M{{ $shock }}" stroke="#e39a3c"/>
    </svg>

    <div class="vh-brand__copy">
        <h2>Verwaltung {{ $site->name() }}</h2>
        <p>Inhalte, Anfragen, Kunden und Besucherzahlen der Website an einem Ort.</p>
    </div>

    <div class="vh-brand__foot">
        <span>© {{ date('Y') }} {{ $site->name() }}</span>
        <a href="{{ url('/') }}">Zur Website</a>
    </div>
</aside>
