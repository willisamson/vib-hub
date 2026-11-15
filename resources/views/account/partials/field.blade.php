{{-- Erwartet: name, label; optional type, value, autocomplete, required, hint, bag, full --}}
@php
    $type ??= 'text';
    $bag ??= 'default';
    $id = 'f-'.($bag !== 'default' ? $bag.'-' : '').$name;
    $error = $errors->getBag($bag)->first($name);
@endphp
<div @class(['field', 'full' => $full ?? false])>
    <label for="{{ $id }}">{{ $label }}@if ($required ?? false) <span aria-hidden="true">*</span>@endif</label>
    <input class="input" id="{{ $id }}" name="{{ $name }}" type="{{ $type }}"
        @if ($type !== 'password') value="{{ $value ?? old($name) }}" @endif
        @if (! empty($autocomplete)) autocomplete="{{ $autocomplete }}" @endif
        @if ($required ?? false) required @endif
        @if ($error) aria-invalid="true" aria-describedby="{{ $id }}-error" @elseif (! empty($hint)) aria-describedby="{{ $id }}-hint" @endif>
    @if ($error)
        <span class="error-text" id="{{ $id }}-error">{{ $error }}</span>
    @elseif (! empty($hint))
        <span class="hint" id="{{ $id }}-hint">{{ $hint }}</span>
    @endif
</div>
