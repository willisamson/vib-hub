@php($en = app()->getLocale() === 'en')
<!DOCTYPE html>
<html lang="{{ $en ? 'en' : 'de' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ $site->name() }} – {{ $site->get('construction_title') }}</title>
    <meta name="description" content="{{ $site->get('construction_text') }}">
    <link rel="icon" href="{{ $site->faviconUrl() }}">
    <meta name="theme-color" content="#f2f5f7">
    @fonts
    @vite(['resources/css/app.css'])
    <style>
        .uc { min-height: 100vh; min-height: 100dvh; display: grid; grid-template-rows: auto 1fr auto; padding: clamp(1.2rem, 4vw, 2.5rem) var(--gutter, 1.5rem); }
        .uc__top { display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
        .uc__logo { height: 38px; width: auto; }
        .uc__lang { display: flex; gap: .3rem; }
        .uc__lang a { padding: .25rem .65rem; border-radius: 999px; font: 600 .8rem/1.2 var(--font-sans); color: var(--muted); text-decoration: none; }
        .uc__lang a[aria-current="true"] { background: var(--strong); color: #fff; }
        .uc__main { align-self: center; max-width: 46rem; margin: 3rem auto; width: 100%; }
        .uc__trace { width: 100%; height: 90px; display: block; margin-bottom: 2rem; overflow: visible; }
        .uc__trace path { fill: none; stroke-width: 2.5; stroke-linecap: round; }
        .uc__trace .t1 { stroke: var(--sine); stroke-dasharray: 1000; stroke-dashoffset: 1000; animation: uc-draw 2.4s ease-out forwards; }
        .uc__trace .t2 { stroke: var(--random); opacity: .45; stroke-width: 1.5; stroke-dasharray: 1000; stroke-dashoffset: 1000; animation: uc-draw 3.2s .3s ease-out forwards; }
        .uc__trace .base { stroke: var(--line); stroke-width: 1; }
        @keyframes uc-draw { to { stroke-dashoffset: 0; } }
        @media (prefers-reduced-motion: reduce) { .uc__trace .t1, .uc__trace .t2 { animation: none; stroke-dashoffset: 0; } }
        .uc h1 { font-size: clamp(2.2rem, 6vw, 3.6rem); line-height: 1.05; letter-spacing: -.02em; color: var(--strong); margin: 0 0 1.2rem; }
        .uc__text { font-size: 1.15rem; line-height: 1.6; color: var(--text-2, #3d4f5c); max-width: 38rem; margin: 0 0 2rem; }
        .uc__mail { display: inline-flex; align-items: center; gap: .6rem; padding: .85rem 1.3rem; border-radius: var(--radius-s, 8px); background: var(--sine); color: #fff; font-weight: 600; text-decoration: none; }
        .uc__mail:hover { background: var(--strong); }
        .uc__foot { display: flex; flex-wrap: wrap; justify-content: space-between; gap: .8rem 1.5rem; font-size: .9rem; color: var(--muted); border-top: 1px solid var(--line); padding-top: 1.2rem; }
        .uc__foot a { color: inherit; }
        .uc__foot nav { display: flex; gap: 1.2rem; }
    </style>
</head>
<body>
    <div class="uc">
        <header class="uc__top">
            <img class="uc__logo" src="{{ $site->logoUrl() }}" alt="{{ $site->name() }}">
            <nav class="uc__lang" aria-label="{{ $en ? 'Language' : 'Sprache' }}">
                <a href="{{ url('/') }}" @if (! $en) aria-current="true" @endif lang="de">DE</a>
                <a href="{{ url('/en') }}" @if ($en) aria-current="true" @endif lang="en">EN</a>
            </nav>
        </header>

        <main class="uc__main">
            <svg class="uc__trace" viewBox="0 0 600 90" preserveAspectRatio="none" aria-hidden="true">
                <path class="base" d="M0 45 H600" />
                <path class="t2" pathLength="1000" d="M0 45 L20 38 L35 55 L52 30 L70 58 L88 41 L105 50 L122 26 L140 62 L160 44 L178 52 L196 33 L214 60 L232 40 L250 49 L268 28 L288 63 L306 42 L324 51 L342 31 L362 59 L380 43 L398 48 L416 29 L436 61 L454 45 L472 50 L490 35 L510 57 L528 42 L546 47 L564 37 L582 53 L600 45" />
                <path class="t1" pathLength="1000" d="M0 45 C25 5, 50 5, 75 45 S125 85, 150 45 S200 5, 225 45 S275 85, 300 45 S350 5, 375 45 S425 85, 450 45 S500 5, 525 45 S575 85, 600 45" />
            </svg>
            <h1>{{ $site->get('construction_title') }}</h1>
            <p class="uc__text">{{ $site->get('construction_text') }}</p>
            @if ($site->get('email'))
                <a class="uc__mail" href="mailto:{{ $site->get('email') }}">{{ $site->get('email') }}</a>
            @endif
        </main>

        <footer class="uc__foot">
            <span>© {{ date('Y') }} {{ $site->name() }}</span>
            <nav>
                <a href="{{ lroute('imprint') }}">{{ $en ? 'Legal notice' : 'Impressum' }}</a>
                <a href="{{ lroute('privacy') }}">{{ $en ? 'Privacy' : 'Datenschutz' }}</a>
            </nav>
        </footer>
    </div>
</body>
</html>
