@extends('layouts.app')

@section('title', __('Anfrage vom :date', ['date' => $inquiry->created_at->format(app()->getLocale() === 'en' ? 'j M Y' : 'd.m.Y')]))

@section('content')
    @include('partials.page-head', [
        'title' => $inquiry->topicLabel(),
        'lede' => __('Anfrage vom :date', ['date' => $inquiry->created_at->translatedFormat(app()->getLocale() === 'en' ? 'j F Y, H:i' : 'd. F Y, H:i')]),
        'crumbs' => [__('Mein Konto') => lroute('account.dashboard'), __('Anfrage') => null],
    ])

    <div class="section">
        <div class="container account-layout">
            @include('account.partials.nav')

            <div class="account-main stack-l">
                <ol class="progress-steps" aria-label="{{ __('Bearbeitungsstand') }}">
                    @php $order = array_keys(\App\Support\Catalog::CUSTOMER_STATUSES); $pos = array_search($inquiry->status, $order, true); @endphp
                    @foreach (\App\Support\Catalog::CUSTOMER_STATUSES as $key => $label)
                        <li @class(['is-done' => $loop->index < $pos, 'is-current' => $loop->index === $pos]) @if ($loop->index === $pos) aria-current="step" @endif>{{ __($label) }}</li>
                    @endforeach
                </ol>

                @if ($inquiry->reply)
                    <div class="panel reply">
                        <div class="panel__head">
                            <h2>{{ __('Antwort von :name', ['name' => $site->name()]) }}</h2>
                            @if ($inquiry->replied_at)<span class="muted">{{ $inquiry->replied_at->format(app()->getLocale() === 'en' ? 'j M Y' : 'd.m.Y') }}</span>@endif
                        </div>
                        <div class="panel__body"><p style="white-space: pre-line">{{ $inquiry->reply }}</p></div>
                    </div>
                @endif

                <div class="panel">
                    <div class="panel__head"><h2>{{ __('Ihre Anfrage') }}</h2></div>
                    <div class="panel__body stack-m">
                        @if ($inquiry->reference)
                            <p><span class="muted">{{ __('Bezug') }}:</span> {{ ltrim(str_replace('[EN]', '', $inquiry->reference)) }}</p>
                        @endif
                        <p style="white-space: pre-line">{{ $inquiry->message }}</p>
                    </div>
                </div>

                <p class="muted">{{ __('Fragen zu dieser Anfrage? Antworten Sie einfach auf unsere E-Mail oder stellen Sie eine neue Anfrage mit Bezug.') }}
                    <a class="link" href="{{ lroute('contact', ['bezug' => __('Anfrage vom :date', ['date' => $inquiry->created_at->format('d.m.Y')])]) }}">{{ __('Neue Anfrage mit Bezug') }}</a></p>
            </div>
        </div>
    </div>
@endsection
