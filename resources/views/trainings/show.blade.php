@extends('layouts.app')

@section('title', $training->tr('title'))
@section('description', $training->tr('excerpt'))

@section('content')
    @include('partials.page-head', [
        'title' => $training->tr('title'),
        'lede' => $training->tr('excerpt'),
        'crumbs' => [__('Schulungen') => lroute('trainings.index'), $training->tr('title') => null],
    ])

    @php $dates = $training->upcomingDates(); @endphp

    <div class="section">
        <div class="container layout-aside">
            <article class="stack-l">
                @if (! empty($training->tr('topics')))
                    <section class="stack-m">
                        <h2 class="hd-3">{{ __('Inhalte') }}</h2>
                        <ul class="checklist">
                            @foreach ($training->tr('topics') as $topic)
                                <li><x-glyph name="check" /><span>{{ $topic }}</span></li>
                            @endforeach
                        </ul>
                    </section>
                @endif
                @if ($training->tr('body'))
                    <div class="prose">{!! consent_embeds($training->tr('body')) !!}</div>
                @endif

                <section class="stack-m">
                    <h2 class="hd-3">{{ __('Termine') }}</h2>
                    @if ($dates->isEmpty())
                        <div class="empty">{{ __('Aktuell kein offener Termin. Wir planen gern einen Termin für Ihr Team.') }}</div>
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
                            <div><dt>{{ __('Niveau') }}</dt><dd style="font-size:1.2rem">{{ $training->levelLabel() }}</dd></div>
                            <div><dt>{{ __('Format') }}</dt><dd style="font-size:1.2rem">{{ $training->formatLabel() }}</dd></div>
                            @if ($training->tr('duration'))<div><dt>{{ __('Dauer') }}</dt><dd style="font-size:1.2rem">{{ $training->tr('duration') }}</dd></div>@endif
                            <div><dt>{{ __('Preis') }}</dt><dd style="font-size:1.2rem">{{ $training->price !== null ? number_format((float) $training->price, 0, ',', '.').' €' : __('auf Anfrage') }}</dd></div>
                        </dl>
                        @if ($training->price !== null)<p class="muted" style="font-size:.85rem">{{ __('Preis je Person zzgl. MwSt., inkl. Unterlagen und Teilnahmebescheinigung.') }}</p>@endif
                        <a class="btn btn--primary" style="width:100%" href="{{ lroute('contact', ['thema' => 'schulung', 'bezug' => $training->tr('title')]) }}">{{ __('Platz anfragen') }}</a>
                        <a class="btn" style="width:100%" href="{{ lroute('contact', ['thema' => 'schulung', 'bezug' => 'Inhouse: '.$training->tr('title')]) }}">{{ __('Als Inhouse-Schulung anfragen') }}</a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection
