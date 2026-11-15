// Interaktive Demos: Resonanzbeseitigung und Schwingungsisolierung.
import { denHartog, fmt, frf, num, transmissibility } from './vibmath.js';
import { drawPlot, logspace } from './plot.js';
import { t } from './i18n.js';

const cssVar = (n, fb) => getComputedStyle(document.documentElement).getPropertyValue(n).trim() || fb;

const F1 = 50;      // Eigenfrequenz des Originals in Hz
const Z1 = 0.02;    // 2 % Dämpfung – typisch für verschraubte Stahlstrukturen

export const MEASURES = {
    original: { label: 'Ausgangszustand', f1: F1, z1: Z1 },
    stiff: { label: 'Versteifung (+80 % Steifigkeit)', f1: F1 * Math.sqrt(1.8), z1: Z1 },
    damp: { label: 'Zusatzdämpfung (12 %)', f1: F1, z1: 0.12 },
    absorber: { label: 'Schwingungstilger (5 % Masse)', f1: F1, z1: Z1, absorber: { mu: 0.05, ...denHartog(F1, 0.05) } },
};

function curve(m, freqs) {
    return freqs.map((f) => [f, frf(f, m.f1, m.z1, m.absorber ?? null)]);
}

function peak(points) {
    return points.reduce((best, p) => (p[1] > best[1] ? p : best), [0, 0]);
}

export function initResonanceDemo(root) {
    const canvas = root.querySelector('canvas');
    const buttons = [...root.querySelectorAll('[data-measure]')];
    const slider = root.querySelector('[data-op-freq]');
    const out = (k) => root.querySelector(`[data-out="${k}"]`);
    const freqs = Array.from({ length: 900 }, (_, i) => 20 + (i * 80) / 899);
    const base = curve(MEASURES.original, freqs);
    let measure = 'original';

    const render = () => {
        const m = MEASURES[measure];
        const pts = measure === 'original' ? base : curve(m, freqs);
        const fop = num(slider.value);
        const yOp = frf(fop, m.f1, m.z1, m.absorber ?? null);
        const yOpBase = frf(fop, F1, Z1);
        const [pf, pv] = peak(pts);
        const accent = cssVar('--sine', '#3ee6ff');

        drawPlot(canvas, {
            x: { min: 20, max: 100, label: t('Frequenz in Hz', 'Frequency in Hz') },
            y: { min: 0.1, max: 40, log: true, label: t('Überhöhung (×)', 'Amplification (×)') },
            series: [
                ...(measure === 'original' ? [] : [{ points: base, color: cssVar('--muted', '#8da2ae'), width: 1.4, dash: [5, 5] }]),
                { points: pts, color: accent, width: 2.4, fill: 'rgba(62,230,255,.07)' },
            ],
            vlines: [{ x: fop, color: cssVar('--shock', '#ffb547'), label: `${t('Betrieb', 'Operation')} ${fmt(fop, 3)} Hz` }],
            markers: [{ x: fop, y: yOp, color: cssVar('--shock', '#ffb547') }],
        });

        out('peak-f').textContent = `${fmt(pf, 3)} Hz`;
        out('peak-v').textContent = `${fmt(pv, 3)} ×`;
        out('op-v').textContent = `${fmt(yOp, 3)} ×`;
        const change = (yOp / yOpBase - 1) * 100;
        const el = out('op-change');
        el.textContent = measure === 'original' ? '–' : `${change > 0 ? '+' : ''}${fmt(change, 3)} %`;
        el.dataset.trend = measure === 'original' ? '' : change < 0 ? 'down' : 'up';
        out('op-f').textContent = `${fmt(fop, 3)} Hz`;
    };

    buttons.forEach((b) =>
        b.addEventListener('click', () => {
            measure = b.dataset.measure;
            buttons.forEach((x) => x.setAttribute('aria-pressed', String(x === b)));
            render();
        }),
    );
    slider.addEventListener('input', render);
    new ResizeObserver(render).observe(canvas);
    render();
}

export function initIsolationDemo(root) {
    const canvas = root.querySelector('canvas');
    const f0In = root.querySelector('[name="f0"]');
    const fexIn = root.querySelector('[name="fex"]');
    const zIn = root.querySelector('[name="zeta"]');
    const out = (k) => root.querySelector(`[data-out="${k}"]`);
    const ratios = logspace(0.1, 10, 500);

    const render = () => {
        const f0 = num(f0In.value), fex = num(fexIn.value), z = num(zIn.value) / 100;
        out('f0').textContent = `${fmt(f0, 3)} Hz`;
        out('fex').textContent = `${fmt(fex, 3)} Hz`;
        out('zeta').textContent = `${fmt(z * 100, 3)} %`;
        const r = fex / f0;
        const T = transmissibility(r, z);
        drawPlot(canvas, {
            x: { min: 0.1, max: 10, log: true, label: t('Frequenzverhältnis f / f₀', 'Frequency ratio f / f₀') },
            y: { min: 0.01, max: 50, log: true, label: t('Übertragung T', 'Transmissibility T') },
            bands: [{ x0: Math.SQRT2, x1: 10, color: 'rgba(91,228,155,.07)' }],
            series: [{ points: ratios.map((x) => [x, transmissibility(x, z)]), color: cssVar('--sine', '#3ee6ff'), width: 2.2 }],
            vlines: [{ x: Math.SQRT2, color: cssVar('--ok', '#5be49b'), label: t('Isolierbereich ab √2', 'Isolation above √2') }],
            markers: [{ x: r, y: T, color: cssVar('--shock', '#ffb547') }],
        });
        out('T').textContent = fmt(T, 3);
        const eff = (1 - T) * 100;
        out('eff').textContent = r > Math.SQRT2 ? `${fmt(eff, 3)} % ${t('isoliert', 'isolated')}` : `${t('Verstärkung', 'Amplification')} ×${fmt(T, 3)}`;
        out('eff').dataset.trend = r > Math.SQRT2 ? 'down' : 'up';
    };

    [f0In, fexIn, zIn].forEach((i) => i.addEventListener('input', render));
    new ResizeObserver(render).observe(canvas);
    render();
}
