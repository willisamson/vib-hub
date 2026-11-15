<div class="instrument" data-isolation-demo>
    <div class="instrument__plot">
        <canvas role="img" aria-label="{{ __('Übertragungsfunktion einer elastischen Lagerung') }}"></canvas>
    </div>
    <div class="instrument__controls">
        <label class="range"><span>{{ __('Eigenfrequenz der Lagerung') }}</span><input type="range" name="f0" min="3" max="60" step="0.5" value="12"><output data-out="f0">{{ __('12 Hz') }}</output></label>
        <label class="range"><span>{{ __('Erregerfrequenz') }}</span><input type="range" name="fex" min="2" max="200" step="1" value="50"><output data-out="fex">{{ __('50 Hz') }}</output></label>
        <label class="range"><span>{{ __('Dämpfung') }}</span><input type="range" name="zeta" min="1" max="40" step="1" value="5"><output data-out="zeta">5 %</output></label>
    </div>
    <dl class="instrument__readouts" style="grid-template-columns: repeat(2, minmax(0, 1fr))">
        <div><dt>{{ __('Übertragungsfaktor T') }}</dt><dd data-out="T">–</dd></div>
        <div><dt>{{ __('Wirkung') }}</dt><dd data-out="eff">–</dd></div>
    </dl>
</div>
