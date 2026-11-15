@extends('layouts.app')

@section('title', $training->title)
@section('description', $training->excerpt)

@section('content')
    @include('partials.page-head', [
        'title' => $training->title,
        'lede' => $training->excerpt,
        'crumbs' => ['Schulungen' => route('trainings.index'), $training->title => null],
    ])

    @php $dates = $training->upcomingDates(); @endphp

    <div class="section">
        <div class="container layout-aside">
            <article class="stack-l">
                @if (! empty($training->topics))
                    <section class="stack-m">
                        <h2 class="hd-3">Inhalte</h2>
                        <ul class="checklist">
                            @foreach ($training->topics as $topic)
                                <li><x-glyph name="check" /><span>{{ $topic }}</span></li>
                            @endforeach
                        </ul>
                    </section>
                @endif
                @if ($training->body)
                    <div class="prose">{!! $training->body !!}</div>
                @endif

                <section class="stack-m">
                    <h2 class="hd-3">Termine</h2>
                    @if ($dates->isEmpty())
                        <div class="empty">Aktuell kein offener Termin. Wir planen gern einen Termin für Ihr Team.</div>
                    @else
                        <div class="dates">
                            @foreach ($dates as $date)
                                @include('partials.date-row', ['date' => $date, 'training' => $training])
                            @endforeach
                        </div>
                    @endif
                </section>
            </article>

            <aside>
                <div class="panel">
                    <div class="panel__body stack-m">
                        <dl class="kv" style="border-top:0">
                            <div><dt>Niveau</dt><dd style="font-size:1.2rem">{{ $training->levelLabel() }}</dd></div>
                            <div><dt>Format</dt><dd style="font-size:1.2rem">{{ $training->formatLabel() }}</dd></div>
                            @if ($training->duration)<div><dt>Dauer</dt><dd style="font-size:1.2rem">{{ $training->duration }}</dd></div>@endif
                            <div><dt>Preis</dt><dd style="font-size:1.2rem">{{ $training->price !== null ? number_format((float) $training->price, 0, ',', '.').' €' : 'auf Anfrage' }}</dd></div>
                        </dl>
                        @if ($training->price !== null)<p class="muted" style="font-size:.85rem">Preis je Person zzgl. MwSt., inkl. Unterlagen und Teilnahmebescheinigung.</p>@endif
                        <a class="btn btn--primary" style="width:100%" href="{{ route('contact', ['thema' => 'schulung', 'bezug' => $training->title]) }}">Platz anfragen</a>
                        <a class="btn" style="width:100%" href="{{ route('contact', ['thema' => 'schulung', 'bezug' => 'Inhouse: '.$training->title]) }}">Als Inhouse-Schulung anfragen</a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection
