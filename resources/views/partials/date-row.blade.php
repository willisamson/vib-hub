@php
    /** @var array $date */
    $start = $date['start'];
    $end = $date['end'] ?? null;
    $range = $end && ! $end->isSameDay($start)
        ? $start->format('d.m.').'–'.$end->format('d.m.Y')
        : $start->format('d.m.Y');
@endphp
<article class="date-row">
    <div class="date-badge" aria-hidden="true">
        <strong>{{ $start->format('d') }}</strong>
        <span>{{ $start->translatedFormat('M Y') }}</span>
    </div>
    <div>
        <h3><a href="{{ lroute('trainings.show', $training) }}">{{ $training->tr('title') }}</a></h3>
        <div class="date-row__meta">
            <span>{{ $range }}</span>
            @if ($training->tr('duration'))<span>{{ $training->tr('duration') }}</span>@endif
            <span>{{ $date['location'] ? __($date['location']) : $training->formatLabel() }}</span>
            <span>{{ $training->levelLabel() }}</span>
        </div>
    </div>
    <div>
        @if (! is_null($date['seats']))
            <span class="pill {{ $date['seats'] > 3 ? 'pill--ok' : 'pill--warn' }}">
                {{ $date['seats'] > 0 ? __(':count Plätze frei', ['count' => $date['seats']]) : __('Warteliste') }}
            </span>
        @endif
    </div>
    <a class="btn btn--small" href="{{ lroute('contact', ['thema' => 'schulung', 'bezug' => $training->tr('title').' – '.$range]) }}">{{ __('Platz anfragen') }}</a>
</article>
