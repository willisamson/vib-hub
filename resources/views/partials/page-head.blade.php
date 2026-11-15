{{-- Erwartet: $title, optional $lede, $crumbs (array label => url) --}}
<header class="page-head">
    <div class="container">
        @if (! empty($crumbs))
            <ol class="crumbs" aria-label="Brotkrumen">
                <li><a href="{{ route('home') }}">Start</a></li>
                @foreach ($crumbs as $label => $url)
                    <li>@if ($url)<a href="{{ $url }}">{{ $label }}</a>@else<span aria-current="page">{{ $label }}</span>@endif</li>
                @endforeach
            </ol>
        @endif
        <h1 class="h-1" style="max-width: 18ch">{{ $title }}</h1>
        @if (! empty($lede))
            <p class="lede">{{ $lede }}</p>
        @endif
        {{ $slot ?? '' }}
    </div>
</header>
