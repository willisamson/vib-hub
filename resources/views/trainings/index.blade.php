@extends('layouts.app')

@section('title', 'Schulungen')
@section('description', 'Seminare zu Schwingungsgrundlagen, Schwingprüfung nach DIN EN 60068, Signalanalyse und Resonanzbeseitigung – offen oder inhouse.')

@section('content')
    @include('partials.page-head', [
        'title' => 'Schulungen',
        'lede' => 'Verständlich, praxisnah und mit Versuchen am Schwingerreger. Offene Termine oder als Inhouse-Schulung zugeschnitten auf Ihre Produkte.',
        'crumbs' => ['Schulungen' => null],
    ])

    <div class="section">
        <div class="container">
            @if ($trainings->isEmpty())
                <div class="empty">Neue Schulungen werden gerade geplant.</div>
            @else
                <div class="cards">
                    @foreach ($trainings as $training)
                        @php $next = $training->upcomingDates()->first(); @endphp
                        <a class="card" data-spotlight href="{{ route('trainings.show', $training) }}">
                            <div class="date-row__meta" style="margin:0">
                                <span class="pill">{{ $training->levelLabel() }}</span>
                                <span class="pill">{{ $training->formatLabel() }}</span>
                            </div>
                            <h2>{{ $training->title }}</h2>
                            <p>{{ $training->excerpt }}</p>
                            <div class="card__foot">
                                <span>
                                    @if ($next)
                                        <x-glyph name="calendar" style="width:16px;height:16px;vertical-align:-3px" /> {{ $next['start']->format('d.m.Y') }}
                                    @else
                                        Termin auf Anfrage
                                    @endif
                                </span>
                                <span class="mono">{{ $training->duration }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @include('partials.cta')
@endsection
