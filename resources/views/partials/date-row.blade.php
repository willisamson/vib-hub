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
        <h3><a href="{{ route('trainings.show', $training) }}">{{ $training->title }}</a></h3>
        <div class="date-row__meta">
            <span>{{ $range }}</span>
            @if ($training->duration)<span>{{ $training->duration }}</span>@endif
            <span>{{ $date['location'] ?: $training->formatLabel() }}</span>
            <span>{{ $training->levelLabel() }}</span>
        </div>
    </div>
    <div>
        @if (! is_null($date['seats']))
            <span class="pill {{ $date['seats'] > 3 ? 'pill--ok' : 'pill--warn' }}">
                {{ $date['seats'] > 0 ? $date['seats'].' Plätze frei' : 'Warteliste' }}
            </span>
        @endif
    </div>
    <a class="btn btn--small" href="{{ route('contact', ['thema' => 'schulung', 'bezug' => $training->title.' am '.$range]) }}">Platz anfragen</a>
</article>
