@extends('layouts.app')

@section('title', $service->tr('title'))
@section('description', $service->tr('excerpt'))

@section('content')
    @include('partials.page-head', [
        'title' => $service->tr('title'),
        'lede' => $service->tr('excerpt'),
        'crumbs' => [__('Leistungen') => lroute('services.index'), $service->tr('title') => null],
    ])

    <div class="section">
        <div class="container layout-aside">
            <article class="stack-l">
                @if ($service->tr('body'))
                    <div class="prose">{!! consent_embeds($service->tr('body')) !!}</div>
                @endif

                @if ($service->demo === 'resonance')
                    <div class="stack-m">
                        <h2 class="hd-3">{{ __('Probieren Sie es aus') }}</h2>
                        @include('partials.demo-resonance')
                    </div>
                @elseif ($service->demo === 'isolation')
                    <div class="stack-m">
                        <h2 class="hd-3">{{ __('So wirkt eine elastische Lagerung') }}</h2>
                        @include('partials.demo-isolation')
                    </div>
                @elseif (in_array($service->demo, ['sine', 'random', 'shock'], true))
                    <div class="instrument">
                        <div class="instrument__plot" style="background: var(--scope-bg)">
                            <canvas data-mini-scope="{{ $service->demo }}" role="img" aria-label="{{ __('Beispielsignal') }}"></canvas>
                        </div>
                    </div>
                @endif
            </article>

            <aside>
                @if (! empty($service->tr('features')))
                    <div class="panel">
                        <div class="panel__head"><h2 class="hd-3" style="font-size:1.35rem">{{ __('Auf einen Blick') }}</h2></div>
                        <div class="panel__body">
                            <ul class="checklist">
                                @foreach ($service->tr('features') as $feature)
                                    <li><x-glyph name="check" /><span>{{ $feature }}</span></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                <div class="panel">
                    <div class="panel__body stack-m">
                        <h2 class="hd-3" style="font-size:1.35rem">{{ __('Projekt besprechen') }}</h2>
                        <p class="muted">{{ __('Beschreiben Sie kurz Ihre Aufgabe, Ihre Anlage und den Zeitrahmen – wir melden uns mit einem Vorschlag.') }}</p>
                        <a class="btn btn--primary" style="width:100%" href="{{ lroute('contact', ['thema' => in_array($service->category, ['pruefung', 'pruefstand'], true) ? $service->category : ($service->demo === 'resonance' ? 'resonanz' : ($service->category === 'engineering' ? 'berechnung' : 'service')), 'bezug' => $service->tr('title')]) }}">{{ __('Anfrage zu dieser Leistung') }}</a>
                    </div>
                </div>
                @if ($related->isNotEmpty())
                    <div class="panel">
                        <div class="panel__head"><h2 class="hd-3" style="font-size:1.35rem">{{ __('Passt dazu') }}</h2></div>
                        <div class="panel__body" style="padding-block:.6rem">
                            <ul class="svc-list">
                                @foreach ($related as $item)
                                    <li><a class="svc-link" href="{{ lroute('services.show', $item) }}" style="margin-inline:0"><x-glyph :name="$item->icon" /><strong style="font-size:1.12rem">{{ $item->tr('title') }}</strong></a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </div>
@endsection
