{{-- Rangliste mit Balken für die Besucherstatistik (nur Inline-Styles, da außerhalb des Filament-Themes) --}}
<x-filament-widgets::widget>
    <x-filament::section :heading="$heading">
        <div style="display:grid; grid-template-columns:minmax(0,1fr); gap:1.4rem">
            @foreach ($groups as $group)
                @php
                    $values = array_column($group['rows'], 'value');
                    $total = max(1, array_sum($values));
                    $max = $values ? max(1, max($values)) : 1;
                @endphp
                <div style="min-width:0">
                    <div style="display:flex; justify-content:space-between; font-size:.78rem; color:rgb(100 116 139); margin-bottom:.45rem">
                        <span>{{ $group['title'] }}</span><span>Aufrufe</span>
                    </div>
                    @forelse ($group['rows'] as $row)
                        <div style="position:relative; display:flex; justify-content:space-between; align-items:center; gap:.8rem; padding:.42rem .6rem; margin-bottom:.25rem; border-radius:.45rem; font-size:.875rem; overflow:hidden">
                            <span aria-hidden="true" style="position:absolute; inset:0 auto 0 0; width:{{ round($row['value'] / $max * 100, 1) }}%; background:rgba(11,127,160,.10); border-radius:.45rem"></span>
                            <span style="position:relative; flex:1 1 auto; min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap" title="{{ $row['label'] }}">
                                {{ $row['label'] }}
                                @if (! empty($row['hint']))<span style="color:rgb(100 116 139); font-size:.78rem"> · {{ $row['hint'] }}</span>@endif
                            </span>
                            <span style="position:relative; flex:none; font-variant-numeric:tabular-nums; white-space:nowrap">
                                <strong>{{ number_format($row['value'], 0, ',', '.') }}</strong>
                                <span style="color:rgb(100 116 139); font-size:.78rem; margin-left:.35rem">{{ number_format($row['value'] / $total * 100, 0, ',', '.') }} %</span>
                            </span>
                        </div>
                    @empty
                        <p style="font-size:.875rem; color:rgb(100 116 139)">{{ $group['empty'] ?? 'Keine Daten im Zeitraum.' }}</p>
                    @endforelse
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
