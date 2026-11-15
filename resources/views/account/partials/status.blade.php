@php
    $tone = ['neu' => 'is-new', 'in_bearbeitung' => 'is-progress', 'angebot' => 'is-offer', 'erledigt' => 'is-done'][$status] ?? '';
@endphp
<span class="status-pill {{ $tone }}">{{ \App\Support\Catalog::label(\App\Support\Catalog::CUSTOMER_STATUSES, $status) }}</span>
