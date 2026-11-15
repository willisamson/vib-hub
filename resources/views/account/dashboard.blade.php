@extends('layouts.app')

@section('title', __('Mein Konto'))

@section('content')
    @include('partials.page-head', [
        'title' => __('Mein Konto'),
        'lede' => __('Hallo :name – hier sehen Sie Ihre Anfragen und deren Bearbeitungsstand.', ['name' => $customer->name]),
        'crumbs' => [__('Kundenkonto') => null],
    ])

    <div class="section">
        <div class="container account-layout">
            @include('account.partials.nav')

            <div class="account-main">
                <div class="account-main__head">
                    <h2 class="hd-3">{{ __('Meine Anfragen') }}</h2>
                    <a class="btn btn--primary btn--small" href="{{ lroute('contact') }}"><x-glyph name="plus" /> {{ __('Neue Anfrage') }}</a>
                </div>

                @if ($inquiries->isEmpty())
                    <div class="empty">
                        <p>{{ __('Sie haben noch keine Anfragen gestellt. Ihre Kontaktdaten sind im Anfrageformular schon ausgefüllt.') }}</p>
                        <p style="margin-top:1rem"><a class="btn btn--primary" href="{{ lroute('contact') }}">{{ __('Erste Anfrage stellen') }}</a></p>
                    </div>
                @else
                    <ol class="inquiry-list">
                        @foreach ($inquiries as $inquiry)
                            <li>
                                <a href="{{ lroute('account.inquiry', $inquiry) }}">
                                    <time datetime="{{ $inquiry->created_at->toDateString() }}">{{ $inquiry->created_at->translatedFormat(app()->getLocale() === 'en' ? 'j M Y' : 'd.m.Y') }}</time>
                                    <span class="inquiry-list__main">
                                        <strong>{{ $inquiry->topicLabel() }}</strong>
                                        <span>{{ $inquiry->reference ? ltrim(str_replace('[EN]', '', $inquiry->reference)) : \Illuminate\Support\Str::limit($inquiry->message, 90) }}</span>
                                    </span>
                                    @if ($inquiry->reply)
                                        <span class="inquiry-list__reply">{{ __('Antwort') }}</span>
                                    @endif
                                    @include('account.partials.status', ['status' => $inquiry->status])
                                </a>
                            </li>
                        @endforeach
                    </ol>
                @endif
            </div>
        </div>
    </div>
@endsection
