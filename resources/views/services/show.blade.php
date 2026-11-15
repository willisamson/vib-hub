@extends('layouts.app')

@section('title', $service->title)
@section('description', $service->excerpt)

@section('content')
    @include('partials.page-head', [
        'title' => $service->title,
        'lede' => $service->excerpt,
        'crumbs' => ['Leistungen' => route('services.index'), $service->title => null],
    ])

    <div class="section">
        <div class="container layout-aside">
            <article class="stack-l">
                @if ($service->body)
                    <div class="prose">{!! $service->body !!}</div>
                @endif

                @if ($service->demo === 'resonance')
                    <div class="stack-m">
                        <h2 class="hd-3">Probieren Sie es aus</h2>
                        @include('partials.demo-resonance')
                    </div>
                @elseif ($service->demo === 'isolation')
                    <div class="stack-m">
                        <h2 class="hd-3">So wirkt eine elastische Lagerung</h2>
                        @include('partials.demo-isolation')
                    </div>
                @elseif (in_array($service->demo, ['sine', 'random', 'shock'], true))
                    <div class="instrument">
                        <div class="instrument__plot" style="background: var(--scope-bg)">
                            <canvas data-mini-scope="{{ $service->demo }}" role="img" aria-label="Beispielsignal"></canvas>
                        </div>
                    </div>
                @endif
            </article>

            <aside>
                @if (! empty($service->features))
                    <div class="panel">
                        <div class="panel__head"><h2 class="hd-3" style="font-size:1.35rem">Auf einen Blick</h2></div>
                        <div class="panel__body">
                            <ul class="checklist">
                                @foreach ($service->features as $feature)
                                    <li><x-glyph name="check" /><span>{{ $feature }}</span></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                <div class="panel">
                    <div class="panel__body stack-m">
                        <h2 class="hd-3" style="font-size:1.35rem">Projekt besprechen</h2>
                        <p class="muted">Beschreiben Sie kurz Prüfling, Anforderung und Termin – wir melden uns mit einem Vorschlag.</p>
                        <a class="btn btn--primary" style="width:100%" href="{{ route('contact', ['thema' => $service->category === 'pruefung' ? 'pruefung' : ($service->demo === 'resonance' ? 'resonanz' : ($service->category === 'engineering' ? 'berechnung' : 'service')), 'bezug' => $service->title]) }}">Anfrage zu dieser Leistung</a>
                    </div>
                </div>
                @if ($related->isNotEmpty())
                    <div class="panel">
                        <div class="panel__head"><h2 class="hd-3" style="font-size:1.35rem">Passt dazu</h2></div>
                        <div class="panel__body" style="padding-block:.6rem">
                            <ul class="svc-list">
                                @foreach ($related as $item)
                                    <li><a class="svc-link" href="{{ route('services.show', $item) }}" style="margin-inline:0"><x-glyph :name="$item->icon" /><strong style="font-size:1.12rem">{{ $item->title }}</strong></a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </div>
@endsection
