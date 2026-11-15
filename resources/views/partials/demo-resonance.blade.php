<div class="instrument" data-resonance-demo>
    <div class="instrument__bar" role="group" aria-label="{{ __('Maßnahme wählen') }}">
        <button type="button" class="seg" data-measure="original" aria-pressed="true">{{ __('Ausgangszustand') }}</button>
        <button type="button" class="seg" data-measure="stiff" aria-pressed="false">{{ __('Versteifen') }}</button>
        <button type="button" class="seg" data-measure="damp" aria-pressed="false">{{ __('Dämpfen') }}</button>
        <button type="button" class="seg" data-measure="absorber" aria-pressed="false">{{ __('Tilger') }}</button>
    </div>
    <div class="instrument__plot">
        <canvas role="img" aria-label="{{ __('Amplitudengang der Struktur über der Frequenz') }}"></canvas>
    </div>
    <div class="instrument__controls">
        <label class="range">
            <span>{{ __('Betriebsfrequenz') }}</span>
            <input type="range" min="20" max="100" step="0.5" value="50" data-op-freq>
            <output data-out="op-f">{{ __('50 Hz') }}</output>
        </label>
    </div>
    <dl class="instrument__readouts">
        <div><dt>{{ __('Resonanz bei') }}</dt><dd data-out="peak-f">{{ __('50 Hz') }}</dd></div>
        <div><dt>{{ __('Max. Überhöhung') }}</dt><dd data-out="peak-v">25 ×</dd></div>
        <div><dt>{{ __('Am Betriebspunkt') }}</dt><dd data-out="op-v">25 ×</dd></div>
        <div><dt>{{ __('Änderung') }}</dt><dd data-out="op-change">–</dd></div>
    </dl>
</div>
