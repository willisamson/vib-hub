// Rechner der Seite /berechnungen (und Mini-Rechner auf der Startseite).
import * as V from './vibmath.js';
import { drawPlot, logspace } from './plot.js';

const cssVar = (n, fb) => getComputedStyle(document.documentElement).getPropertyValue(n).trim() || fb;
const { fmt, num, G } = V;

function bind(root, render) {
    root.addEventListener('input', render);
    root.addEventListener('change', render);
    root.querySelectorAll('canvas').forEach((c) => new ResizeObserver(render).observe(c));
    render();
}

function setOut(root, map) {
    for (const [k, v] of Object.entries(map)) {
        root.querySelectorAll(`[data-out="${k}"]`).forEach((el) => (el.textContent = v));
    }
}

function val(root, name) {
    const el = root.querySelector(`[name="${name}"]`);
    return el ? num(el.value) : NaN;
}

function setError(root, message) {
    const el = root.querySelector('[data-error]');
    if (!el) return;
    el.textContent = message ?? '';
    el.hidden = !message;
}

/* ------------------------------------------------------------- Sinus */

function initSine(root) {
    const render = () => {
        const f = val(root, 'f');
        const kind = root.querySelector('[name="kind"]').value;
        const raw = val(root, 'value');
        const ok = f > 0 && raw > 0;
        setError(root, ok ? null : 'Bitte Frequenz und Wert größer 0 eingeben.');
        let point = null;
        if (ok) {
            const map = {
                acc_g: ['acc', raw * G],
                acc_ms2: ['acc', raw],
                vel: ['vel', raw / 1000],
                disp_peak: ['disp', raw / 1000],
                disp_pp: ['disp', raw / 2000],
            };
            const [k, v] = map[kind];
            point = V.sine(f, k, v);
            setOut(root, {
                'acc-g': fmt(point.accPeakG, 4),
                'acc-ms2': fmt(point.accPeak, 4),
                'acc-rms': fmt(point.accRms / G, 4),
                'vel-peak': fmt(point.velPeak * 1000, 4),
                'vel-rms': fmt(point.velRms * 1000, 4),
                'disp-peak': fmt(point.dispPeak * 1000, 4),
                'disp-pp': fmt(point.dispPkPk * 1000, 4),
            });
        }

        // Übergangsfrequenz und Prüfprofil
        const x = val(root, 'cx') / 1000;  // mm → m (Spitze)
        const aG = val(root, 'ca');
        const canvas = root.querySelector('canvas');
        if (x > 0 && aG > 0) {
            const fc = V.crossoverFrequency(x, aG * G);
            setOut(root, { fc: fmt(fc, 4) });
            if (canvas) {
                const freqs = logspace(5, 2000, 300);
                const pts = freqs.map((fr) => [fr, Math.min(x * Math.pow(2 * Math.PI * fr, 2) / G, aG)]);
                const markers = point && f >= 5 && f <= 2000
                    ? [{ x: f, y: point.accPeakG, color: cssVar('--shock', '#ffb547'), label: 'Ihr Punkt' }]
                    : [];
                const yMax = Math.max(aG * 3, markers[0]?.y * 1.5 || 0);
                drawPlot(canvas, {
                    x: { min: 5, max: 2000, log: true, label: 'Frequenz in Hz' },
                    y: { min: Math.max(0.01, Math.min(pts[0][1], aG) / 2), max: yMax, log: true, label: 'Beschleunigung in g' },
                    series: [{ points: pts, color: cssVar('--sine', '#3ee6ff'), width: 2.4, fill: 'rgba(62,230,255,.06)' }],
                    vlines: [{ x: fc, color: cssVar('--muted', '#8da2ae'), label: `Übergang ${fmt(fc, 3)} Hz` }],
                    markers,
                });
            }
        } else {
            setOut(root, { fc: '–' });
        }
    };
    bind(root, render);
}

/* ------------------------------------------------------------ Random */

function initRandom(root) {
    const tbody = root.querySelector('tbody');
    const preset = root.querySelector('[name="preset"]');
    const tpl = root.querySelector('template');

    const addRow = (r = {}) => {
        const tr = tpl.content.firstElementChild.cloneNode(true);
        tr.querySelector('[data-f]').value = r.f ?? '';
        tr.querySelector('[data-psd]').value = r.psd !== undefined ? String(r.psd).replace('.', ',') : '';
        tr.querySelector('[data-slope]').value = r.slope !== undefined ? String(r.slope).replace('.', ',') : '';
        tbody.appendChild(tr);
    };

    const load = (key) => {
        tbody.innerHTML = '';
        V.PSD_PRESETS[key].rows.forEach(addRow);
    };

    root.querySelector('[data-add-row]').addEventListener('click', () => {
        addRow();
        render();
    });
    tbody.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-remove-row]');
        if (!btn) return;
        btn.closest('tr').remove();
        render();
    });
    preset.addEventListener('change', () => {
        if (preset.value) load(preset.value);
        render();
    });

    const render = () => {
        const rows = [...tbody.querySelectorAll('tr')].map((tr) => ({
            f: num(tr.querySelector('[data-f]').value),
            psd: num(tr.querySelector('[data-psd]').value),
            slope: num(tr.querySelector('[data-slope]').value),
        }));
        let profile;
        try {
            profile = V.resolveProfile(rows.map((r) => ({
                f: r.f,
                psd: Number.isFinite(r.psd) ? r.psd : '',
                slope: Number.isFinite(r.slope) ? r.slope : '',
            })));
            setError(root, null);
        } catch (e) {
            setError(root, e.message);
            setOut(root, { grms: '–', vrms: '–', drms: '–', dpp: '–' });
            return;
        }
        const s = V.randomStats(profile);
        setOut(root, {
            grms: fmt(s.grms, 4),
            vrms: fmt(s.vrms * 1000, 4),
            drms: fmt(s.drms * 1000, 4),
            dpp: fmt(s.dpp3s * 1000, 4),
        });
        // berechnete PSD-Werte zurück in die Tabelle (als Platzhalter)
        [...tbody.querySelectorAll('tr')].forEach((tr, i) => {
            const p = profile[i];
            const psdIn = tr.querySelector('[data-psd]');
            if (p && !psdIn.value) psdIn.placeholder = `= ${fmt(p.psd, 3)}`;
            const sl = tr.querySelector('[data-slope-info]');
            if (sl) {
                const db = i > 0 && p ? V.slopeDbPerOct(profile[i - 1], p) : null;
                sl.textContent = db === null ? '' : `${db > 0.05 ? '+' : ''}${(Math.abs(db) < 0.05 ? 0 : db).toLocaleString('de-DE', { maximumFractionDigits: 1, minimumFractionDigits: 1 })} dB/Okt.`;
            }
        });

        const canvas = root.querySelector('canvas');
        const fMin = profile[0].f, fMax = profile[profile.length - 1].f;
        const psds = profile.map((p) => p.psd);
        const pMin = Math.min(...psds), pMax = Math.max(...psds);
        drawPlot(canvas, {
            x: { min: Math.max(1, fMin / 1.5), max: fMax * 1.5, log: true, label: 'Frequenz in Hz' },
            y: { min: pMin / 4, max: pMax * 4, log: true, label: 'PSD in g²/Hz' },
            series: [{ points: profile.map((p) => [p.f, p.psd]), color: cssVar('--random', '#9c7bff'), width: 2.4, fill: 'rgba(156,123,255,.10)' }],
            markers: profile.map((p) => ({ x: p.f, y: p.psd, color: cssVar('--random', '#9c7bff') })),
        });
    };

    load('navmat');
    bind(root, render);
}

/* ------------------------------------------------------------- Schock */

function initShock(root) {
    const render = () => {
        const shape = root.querySelector('[name="shape"]').value;
        const A = val(root, 'peak'), D = val(root, 'duration');
        const ok = A > 0 && D > 0;
        setError(root, ok ? null : 'Bitte Spitzenwert und Dauer größer 0 eingeben.');
        if (ok) {
            const s = V.shock(shape, A, D);
            setOut(root, {
                dv: fmt(s.dv, 4),
                drop: fmt(s.dropHeight * 1000, 4),
                disp: fmt(s.disp * 1000, 4),
            });
            const canvas = root.querySelector('canvas');
            const pts = [];
            for (let i = -40; i <= 240; i++) {
                const u = i / 200;
                pts.push([u * D, A * V.shockShape(shape, u)]);
            }
            drawPlot(canvas, {
                x: { min: -0.2 * D, max: 1.2 * D, label: 'Zeit in ms' },
                y: { min: -0.1 * A, max: 1.2 * A, label: 'Beschleunigung in g' },
                series: [{ points: pts, color: cssVar('--shock', '#ffb547'), width: 2.6, fill: 'rgba(255,181,71,.10)' }],
            });
        }
        const h = val(root, 'h'), d2 = val(root, 'd2'), e = val(root, 'e');
        if (h > 0 && d2 > 0 && e >= 0 && e <= 1) {
            const r = V.halfSineFromDrop(h, d2, e);
            setOut(root, { 'req-g': fmt(r.peakG, 4), 'req-dv': fmt(r.dv, 4) });
        } else {
            setOut(root, { 'req-g': '–', 'req-dv': '–' });
        }
    };
    bind(root, render);
}

/* ------------------------------------------------------ Eigenfrequenz */

function initNatural(root) {
    const render = () => {
        const m = val(root, 'm'), k = val(root, 'k') * 1000; // N/mm → N/m
        if (m > 0 && k > 0) {
            setOut(root, {
                f0: fmt(V.naturalFrequency(m, k), 4),
                delta: fmt((m * G / k) * 1000, 4),
            });
        } else setOut(root, { f0: '–', delta: '–' });

        const d = val(root, 'delta') / 1000;
        setOut(root, { 'f0-delta': d > 0 ? fmt(V.naturalFrequencyFromDeflection(d), 4) : '–' });

        const m2 = val(root, 'm2'), ft = val(root, 'ft');
        setOut(root, { 'k-req': m2 > 0 && ft > 0 ? fmt(V.stiffnessFor(m2, ft) / 1000, 4) : '–' });
    };
    bind(root, render);
}

/* ------------------------------------------------------ Schwingstärke */

function initSeverity(root) {
    const bar = root.querySelector('[data-severity-bar]');
    const render = () => {
        const v = val(root, 'v');
        const cls = root.querySelector('[name="cls"]').value;
        const limits = V.SEVERITY_CLASSES[cls].limits;
        const max = limits[2] * 1.6;
        const pos = (x) => `${Math.min(100, (Math.log(1 + x) / Math.log(1 + max)) * 100)}%`;
        const scale = bar.parentElement;
        scale.style.setProperty('--ab', pos(limits[0]));
        scale.style.setProperty('--bc', pos(limits[1]));
        scale.style.setProperty('--cd', pos(limits[2]));
        root.querySelectorAll('[data-limit]').forEach((el) => (el.textContent = fmt(limits[Number(el.dataset.limit)], 3)));
        if (v >= 0) {
            const zone = V.severityZone(v, cls);
            scale.style.setProperty('--v', pos(v));
            bar.dataset.zone = zone;
            setOut(root, { zone, 'zone-text': V.SEVERITY_ZONES[zone] });
            root.dataset.zone = zone;
        } else {
            setOut(root, { zone: '–', 'zone-text': 'Bitte einen Messwert eingeben.' });
        }
    };
    bind(root, render);
}

const REGISTRY = { sine: initSine, random: initRandom, shock: initShock, natural: initNatural, severity: initSeverity };

export function initCalculators() {
    document.querySelectorAll('[data-calc]').forEach((root) => {
        const fn = REGISTRY[root.dataset.calc];
        if (fn) fn(root);
    });
}
