// Schwingregler-Simulator: Sinus, Random und Schock mit Sollkurve,
// Warn- und Abschaltgrenzen, Regelschleife und Ereignisprotokoll.
// Vereinfachtes Lehrmodell – kein Ersatz für eine echte Regelung.
import { G, PSD_PRESETS, fmt, num, psdAt, resolveProfile } from './vibmath.js';
import { drawPlot, logspace } from './plot.js';
import { t } from './i18n.js';

const cssVar = (n, fb) => getComputedStyle(document.documentElement).getPropertyValue(n).trim() || fb;
const TWO_PI = 2 * Math.PI;
const dB = (ratio) => 20 * Math.log10(ratio);

/* -------------------------------------------------------- Prüfaufbau-Modell */

// Übertragung Antriebsspannung → Regelbeschleunigung: Shaker + Vorrichtung + Prüfling
// mit bis zu zwei Resonanzen (Frequenz fr, Güte q, Massenanteil beta).
const BETAS = [0.5, 0.35];

function plant(modes) {
    return { g0: 2.0, modes: modes.map((m, i) => ({ ...m, beta: BETAS[i] ?? 0.3 })) };
}

function gain(p, f) {
    const B = p.modes.reduce((sum, m) => sum + m.beta, 0);
    let res = 0;
    for (const m of p.modes) {
        const r = f / m.fr;
        res += m.beta / Math.hypot(1 - r * r, r / m.q);          // Einmassenschwinger je Mode
    }
    return p.g0 * Math.hypot(1 - B, res) / Math.hypot(1 - B, B);  // Normierung: H(0) = g0
}

// Alle Resonanzen verschieben (Prüfling löst sich)
function shifted(p, factor, q, g) {
    return { ...p, g0: p.g0 * g, modes: p.modes.map((m) => ({ ...m, fr: m.fr * factor, q: Math.min(m.q, q) })) };
}

// χ²-verteilte Schätzung mit ν Freiheitsgraden (Wilson-Hilferty), normiert auf 1
function chi2Factor(nu) {
    const z = gauss();
    const a = 2 / (9 * nu);
    return Math.max(0.05, Math.pow(1 - a + z * Math.sqrt(a), 3));
}

function gauss() {
    let u = 0, v = 0;
    while (u === 0) u = Math.random();
    while (v === 0) v = Math.random();
    return Math.sqrt(-2 * Math.log(u)) * Math.cos(TWO_PI * v);
}

/* ------------------------------------------------------------- Oberfläche */

export function initSimulator(root) {
    const canvas = root.querySelector('[data-sim-canvas]');
    const statusEl = root.querySelector('[data-sim-status]');
    const statusText = root.querySelector('[data-sim-status-text]');
    const readoutsEl = root.querySelector('[data-sim-readouts]');
    const logEl = root.querySelector('[data-sim-log]');
    const btnStart = root.querySelector('[data-sim-action="start"]');
    const btnPause = root.querySelector('[data-sim-action="pause"]');
    const btnStop = root.querySelector('[data-sim-action="stop"]');
    const modeButtons = [...root.querySelectorAll('[data-sim-mode]')];
    const setups = [...root.querySelectorAll('[data-sim-setup]')];

    let mode = 'sine';
    let sim = null;          // laufende Simulation
    let state = 'idle';      // idle | running | paused | warning | aborted | done
    let raf = null;
    let last = 0;
    let warnLogged = false;

    const val = (name) => num(root.querySelector(`[data-sim-setup="${mode}"] [name="${name}"]`)?.value);
    const sel = (name) => root.querySelector(`[name="${name}"]`)?.value;
    const gnum = (name, fb, lo, hi) => {
        const v = num(root.querySelector(`[data-sim-global] [name="${name}"]`)?.value);
        return Number.isFinite(v) && v > 0 ? Math.min(hi, Math.max(lo, v)) : fb;
    };
    // Resonanzen des Prüfaufbaus aus den Eingaben (1. immer, 2. optional)
    const resonances = () => {
        const list = [{ fr: gnum('r1f', 1200, 5, 5000), q: gnum('r1q', 20, 2, 500) }];
        if (root.querySelector('[data-sim-global] [name="r2on"]')?.checked) {
            list.push({ fr: gnum('r2f', 1900, 5, 5000), q: gnum('r2q', 30, 2, 500) });
        }
        return list;
    };
    const resoLines = (fmin, fmax) => resonances()
        .filter((m) => m.fr >= fmin && m.fr <= fmax)
        .map((m, i) => ({ x: m.fr, color: cssVar('--muted', '#8da2ae'), label: `R${i + 1}`, bottom: true }));

    /* ---- Status & Protokoll ---- */
    const STATE_TEXT = {
        idle: t('Bereit', 'Ready'),
        running: t('Regelung läuft', 'Running'),
        paused: t('Angehalten', 'Paused'),
        warning: t('Warngrenze überschritten', 'Alarm limit exceeded'),
        aborted: t('Abschaltung', 'Abort'),
        done: t('Prüfung beendet', 'Test complete'),
    };

    const setState = (s, msg) => {
        state = s;
        statusEl.dataset.state = s;
        statusText.textContent = msg ? `${STATE_TEXT[s]} – ${msg}` : STATE_TEXT[s];
        btnStart.disabled = s === 'running' || s === 'warning';
        btnPause.disabled = !(s === 'running' || s === 'warning');
        btnStop.disabled = s === 'idle';
        setups.forEach((f) => f.querySelectorAll('input, select').forEach((i) => (i.disabled = s === 'running' || s === 'warning' || s === 'paused')));
        root.querySelectorAll('[data-sim-global] input, [data-sim-global] select, [data-sim-global] button').forEach((i) => (i.disabled = s === 'running' || s === 'warning' || s === 'paused'));
    };

    const log = (msg, kind = 'info') => {
        const li = document.createElement('li');
        li.dataset.kind = kind;
        const time = sim ? fmtTime(sim.t) : '00:00';
        li.innerHTML = `<time>${time}</time><span></span>`;
        li.querySelector('span').textContent = msg;
        logEl.prepend(li);
        while (logEl.children.length > 40) logEl.lastChild.remove();
    };

    const fmtTime = (s) => {
        const m = Math.floor(s / 60), sec = Math.floor(s % 60);
        return `${String(m).padStart(2, '0')}:${String(sec).padStart(2, '0')}`;
    };

    const readouts = (pairs) => {
        readoutsEl.innerHTML = pairs.map(([k, v, cls]) => `<div${cls ? ` class="${cls}"` : ''}><dt>${k}</dt><dd>${v}</dd></div>`).join('');
    };

    /* ---- Grenzen prüfen ---- */
    const checkLimits = (errDb, warnDb, abortDb, where, armed = true) => {
        const a = Math.abs(errDb);
        if (armed && a > abortDb) {
            setState('aborted', where);
            log(t(`Abschaltgrenze ±${fmt(abortDb, 2)} dB überschritten (${fmt(errDb, 2)} dB) ${where}`, `Abort limit ±${fmt(abortDb, 2)} dB exceeded (${fmt(errDb, 2)} dB) ${where}`), 'abort');
            log(t('Antrieb abgeschaltet – Signal sanft heruntergefahren', 'Drive shut down – output ramped down'), 'abort');
            return 'abort';
        }
        if (a > warnDb) {
            if (state === 'running') setState('warning', where);
            if (!warnLogged) {
                log(t(`Warngrenze ±${fmt(warnDb, 2)} dB überschritten (${fmt(errDb, 2)} dB) ${where}`, `Alarm limit ±${fmt(warnDb, 2)} dB exceeded (${fmt(errDb, 2)} dB) ${where}`), 'warn');
                warnLogged = true;
            }
            return 'warn';
        }
        if (state === 'warning') {
            setState('running');
            warnLogged = false;
        }
        return 'ok';
    };

    /* ================================================================= SINUS */

    const Sine = {
        create() {
            const f1 = val('f1'), f2 = val('f2'), rate = val('rate');
            const x = val('disp') / 1000, a = val('acc');
            return {
                t: 0, f: f1, f1, f2, rate, x, a,
                warn: val('warn'), abort: val('abort'),
                speed: num(sel('speed')) || 10,
                k: 2.5,
                plant: plant(resonances()),
                scenario: sel('scenario'),
                drive: (Math.min(x * (TWO_PI * f1) ** 2 / G, a)) / gain(plant(resonances()), f1),
                trace: [],
                errMax: 0,
                changed: false,
            };
        },
        demand(s, f) {
            return Math.min(s.x * (TWO_PI * f) ** 2 / G, s.a);
        },
        step(s, dtReal) {
            const dt = dtReal * s.speed;
            const sub = 20;
            for (let i = 0; i < sub; i++) {
                const h = dt / sub;
                s.t += h;
                s.f = s.f * Math.pow(2, (s.rate / 60) * h);
                if (s.f >= s.f2) { s.f = s.f2; return 'done'; }
                if (s.scenario === 'loose' && !s.changed && s.f > s.f1 * Math.pow(s.f2 / s.f1, 0.62)) {
                    s.changed = true;
                    // neue, lose Resonanz knapp oberhalb der aktuellen Frequenz
                    s.plant = { ...s.plant, g0: s.plant.g0 * 2.6, modes: [{ fr: s.f * 1.03, q: 30, beta: 0.5 }, ...s.plant.modes.slice(1)] };
                    log(t('Prüfling löst sich – Übertragungsverhalten ändert sich sprunghaft', 'Specimen loosens – transfer function changes abruptly'), 'warn');
                }
                const meas = gain(s.plant, s.f) * s.drive * (1 + gauss() * 0.01);
                const dem = this.demand(s, s.f);
                const corr = 1 - Math.exp(-s.k * h);
                s.drive *= Math.pow(dem / meas, corr);
                const err = dB(meas / dem);
                s.err = err;
                if (i === sub - 1 || Math.abs(err) > s.warn) s.trace.push([s.f, meas]);
                const res = checkLimits(err, s.warn, s.abort, t(`bei ${fmt(s.f, 3)} Hz`, `at ${fmt(s.f, 3)} Hz`), s.t > 0.5);
                if (res === 'abort') return 'abort';
                s.errMax = Math.max(s.errMax, Math.abs(err));
            }
            return 'run';
        },
        draw(s) {
            const freqs = logspace(s ? s.f1 : val('f1'), s ? s.f2 : val('f2'), 240);
            const x = s ? s.x : val('disp') / 1000, a = s ? s.a : val('acc');
            const warn = s ? s.warn : val('warn'), abort = s ? s.abort : val('abort');
            const dem = freqs.map((f) => [f, Math.min(x * (TWO_PI * f) ** 2 / G, a)]);
            const scale = (d) => (p) => [p[0], p[1] * Math.pow(10, d / 20)];
            const ys = dem.map((p) => p[1]);
            drawPlot(canvas, {
                x: { min: freqs[0], max: freqs[freqs.length - 1], log: true, label: t('Frequenz in Hz', 'Frequency in Hz') },
                y: { min: Math.min(...ys) / 4, max: Math.max(...ys) * 4, log: true, label: t('Beschleunigung in g (Spitze)', 'Acceleration in g (peak)') },
                series: [
                    { points: dem.map(scale(abort)), color: cssVar('--danger', '#d33a3a'), width: 1.2, dash: [6, 4] },
                    { points: dem.map(scale(-abort)), color: cssVar('--danger', '#d33a3a'), width: 1.2, dash: [6, 4] },
                    { points: dem.map(scale(warn)), color: cssVar('--shock', '#b45f00'), width: 1.2, dash: [3, 4] },
                    { points: dem.map(scale(-warn)), color: cssVar('--shock', '#b45f00'), width: 1.2, dash: [3, 4] },
                    { points: dem, color: cssVar('--strong', '#0b1a26'), width: 1.6 },
                    ...(s && s.trace.length > 1 ? [{ points: s.trace, color: cssVar('--sine', '#0b7fa0'), width: 2.2 }] : []),
                ],
                vlines: [...resoLines(freqs[0], freqs[freqs.length - 1]), ...(s ? [{ x: s.f, color: cssVar('--sine', '#0b7fa0'), label: `${fmt(s.f, 3)} Hz` }] : [])],
            });
        },
        readouts(s) {
            if (!s) {
                const fc = Math.sqrt(val('acc') * G / (val('disp') / 1000)) / TWO_PI;
                const octs = Math.log2(val('f2') / val('f1'));
                return readouts([
                    [t('Übergang Weg/Beschl.', 'Crossover'), `${fmt(fc, 3)} Hz`],
                    [t('Dauer je Sweep', 'Sweep duration'), `${fmt(octs / val('rate'), 3)} min`],
                    [t('Grenzen', 'Limits'), `±${fmt(val('warn'), 2)} / ±${fmt(val('abort'), 2)} dB`],
                ]);
            }
            const dem = this.demand(s, s.f);
            readouts([
                [t('Frequenz', 'Frequency'), `${fmt(s.f, 4)} Hz`],
                [t('Soll', 'Demand'), `${fmt(dem, 3)} g`],
                [t('Ist', 'Control'), `${fmt(dem * Math.pow(10, (s.err || 0) / 20), 3)} g`],
                [t('Abweichung', 'Error'), `${fmt(s.err || 0, 2)} dB`, Math.abs(s.err || 0) > s.warn ? 'is-warn' : ''],
                [t('Antrieb', 'Drive'), `${fmt(s.drive, 3)} V`],
                [t('Prüfzeit', 'Test time'), fmtTime(s.t)],
            ]);
        },
    };

    /* ================================================================ RANDOM */

    const Random = {
        create() {
            const profile = resolveProfile(PSD_PRESETS[sel('profile')].rows);
            const lines = logspace(profile[0].f, profile[profile.length - 1].f, 160);
            const p = plant(resonances());
            const levels = [-9, -6, -3, 0];
            return {
                t: 0, loop: 0, levelIdx: 0, levelLoops: 0, levels, profile, lines,
                plant: p, scenario: sel('scenario'),
                warn: val('warn'), abort: val('abort'), dof: val('dof'),
                duration: val('duration') * 60,
                fullTime: 0,
                // Startwert aus der Übertragungsmessung (mit Schätzfehler)
                drive: lines.map((f) => psdAt(profile, f) / (gain(p, f) ** 2 * Math.max(0.5, 1 + 0.12 * gauss()))),
                avg: null, meas: null, changed: false, loopTimer: 0,
            };
        },
        demandAt(s, f) {
            return psdAt(s.profile, f) * Math.pow(10, s.levels[s.levelIdx] / 10);
        },
        step(s, dtReal) {
            s.t += dtReal;
            s.loopTimer += dtReal;
            if (s.levelIdx === s.levels.length - 1) s.fullTime += dtReal * 20; // Zeitraffer ×20 bei Volllast
            if (s.loopTimer < 0.35) return 'run';
            s.loopTimer = 0;
            s.loop++;
            s.levelLoops++;

            if (s.scenario === 'loose' && !s.changed && s.levelIdx === s.levels.length - 1 && s.levelLoops > 6) {
                s.changed = true;
                s.plant = shifted(s.plant, 0.72, 35, 1.8);
                log(t('Prüfling löst sich – Resonanz verschiebt sich', 'Specimen loosens – resonance shifts'), 'warn');
            }

            const level = Math.pow(10, s.levels[s.levelIdx] / 10);
            const meas = s.lines.map((f, i) => {
                const h = gain(s.plant, f);
                return h * h * s.drive[i] * level * chi2Factor(s.dof);
            });
            s.meas = meas;
            s.avg = s.avg ? s.avg.map((v, i) => 0.5 * v + 0.5 * meas[i]) : meas.slice();
            // Entzerrung: Antriebsspektrum nachführen
            // Korrektur je Schleife begrenzen (±10 dB), wie bei realen Reglern
            s.drive = s.drive.map((d, i) => d * Math.min(10, Math.max(0.1, Math.pow(this.demandAt(s, s.lines[i]) / s.avg[i], 0.6))));

            // Abweichungen je Linie
            let worst = 0, worstF = 0, outWarn = 0, outAbort = 0;
            s.avg.forEach((v, i) => {
                const e = 10 * Math.log10(v / this.demandAt(s, s.lines[i]));
                if (Math.abs(e) > Math.abs(worst)) { worst = e; worstF = s.lines[i]; }
                if (Math.abs(e) > s.warn) outWarn++;
                if (Math.abs(e) > s.abort) outAbort++;
            });
            s.worst = worst; s.outWarn = outWarn; s.outAbort = outAbort;
            const armed = s.levelLoops > 4 || s.changed;
            // Abschaltung erst, wenn mehrere Linien (≥ 3) außerhalb der Abschaltgrenze liegen
            const errForCheck = outAbort >= 3 ? worst : Math.sign(worst) * Math.min(Math.abs(worst), s.abort - 0.01);
            const res = checkLimits(errForCheck, s.warn, s.abort, t(`bei ${fmt(worstF, 3)} Hz`, `at ${fmt(worstF, 3)} Hz`), armed);
            if (res === 'abort') return 'abort';

            // Pegel stufenweise erhöhen
            if (s.levelIdx < s.levels.length - 1 && s.levelLoops >= 6 && Math.abs(worst) < s.warn) {
                s.levelIdx++;
                s.levelLoops = 0;
                s.avg = null;
                log(t(`Pegel ${s.levels[s.levelIdx]} dB`, `Level ${s.levels[s.levelIdx]} dB`));
                if (s.levelIdx === s.levels.length - 1) log(t('Volllast erreicht – Prüfzeit läuft', 'Full level reached – test time running'), 'ok');
            }
            if (s.fullTime >= s.duration) return 'done';
            return 'run';
        },
        grms(s, arr) {
            let area = 0;
            for (let i = 0; i < s.lines.length - 1; i++) area += 0.5 * (arr[i] + arr[i + 1]) * (s.lines[i + 1] - s.lines[i]);
            return Math.sqrt(area);
        },
        draw(s) {
            const profile = s ? s.profile : resolveProfile(PSD_PRESETS[sel('profile')].rows);
            const lines = s ? s.lines : logspace(profile[0].f, profile[profile.length - 1].f, 160);
            const lvl = s ? Math.pow(10, s.levels[s.levelIdx] / 10) : 1;
            const warn = s ? s.warn : val('warn'), abort = s ? s.abort : val('abort');
            const dem = lines.map((f) => [f, psdAt(profile, f) * lvl]);
            const sc = (d) => (p) => [p[0], p[1] * Math.pow(10, d / 10)];
            const ys = profile.map((p) => p.psd);
            drawPlot(canvas, {
                x: { min: lines[0], max: lines[lines.length - 1], log: true, label: t('Frequenz in Hz', 'Frequency in Hz') },
                y: { min: Math.min(...ys) / 60, max: Math.max(...ys) * 12, log: true, label: 'PSD in g²/Hz' },
                series: [
                    { points: dem.map(sc(abort)), color: cssVar('--danger', '#d33a3a'), width: 1.2, dash: [6, 4] },
                    { points: dem.map(sc(-abort)), color: cssVar('--danger', '#d33a3a'), width: 1.2, dash: [6, 4] },
                    { points: dem.map(sc(warn)), color: cssVar('--shock', '#b45f00'), width: 1.2, dash: [3, 4] },
                    { points: dem.map(sc(-warn)), color: cssVar('--shock', '#b45f00'), width: 1.2, dash: [3, 4] },
                    { points: dem, color: cssVar('--strong', '#0b1a26'), width: 1.6 },
                    ...(s && s.avg ? [{ points: lines.map((f, i) => [f, s.avg[i]]), color: cssVar('--random', '#6d3fe0'), width: 1.8 }] : []),
                ],
                vlines: resoLines(lines[0], lines[lines.length - 1]),
            });
        },
        readouts(s) {
            if (!s) {
                const profile = resolveProfile(PSD_PRESETS[sel('profile')].rows);
                const lines = logspace(profile[0].f, profile[profile.length - 1].f, 400);
                let area = 0;
                for (let i = 0; i < lines.length - 1; i++) area += 0.5 * (psdAt(profile, lines[i]) + psdAt(profile, lines[i + 1])) * (lines[i + 1] - lines[i]);
                return readouts([
                    [t('Soll-Grms', 'Demand Grms'), `${fmt(Math.sqrt(area), 3)} g`],
                    [t('Pegelstufen', 'Level steps'), '−9 / −6 / −3 / 0 dB'],
                    [t('Grenzen', 'Limits'), `±${fmt(val('warn'), 2)} / ±${fmt(val('abort'), 2)} dB`],
                ]);
            }
            const demArr = s.lines.map((f) => this.demandAt(s, f));
            const ctrl = s.avg ? this.grms(s, s.avg) : 0;
            readouts([
                [t('Pegel', 'Level'), `${s.levels[s.levelIdx]} dB`],
                [t('Soll-Grms', 'Demand Grms'), `${fmt(this.grms(s, demArr), 3)} g`],
                [t('Ist-Grms', 'Control Grms'), `${fmt(ctrl, 3)} g`],
                [t('Linien > Warngrenze', 'Lines > alarm'), String(s.outWarn ?? 0), (s.outWarn ?? 0) > 0 ? 'is-warn' : ''],
                [t('Regelschleifen', 'Loops'), String(s.loop)],
                [t('Prüfzeit (Volllast)', 'Test time (full level)'), `${fmtTime(s.fullTime)} / ${fmtTime(s.duration)}`],
            ]);
        },
    };

    /* ================================================================ SCHOCK */

    const Shock = {
        create() {
            const A = val('peak'), D = val('duration') / 1000;
            const p = plant(resonances());
            return {
                t: 0, A, D, tol: val('tol') / 100, abortTol: val('abortTol') / 100,
                stroke: val('stroke') / 1000, count: val('count'),
                plant: p, scenario: sel('scenario'),
                pulses: 0, iteration: 0, anim: null, last: null, waiting: false,
                levels: [-12, -6, 0], levelIdx: 0,
            };
        },
        // Sollpuls inkl. Vor-/Nachpuls (Geschwindigkeit und Weg kehren auf null zurück)
        ref(s, tt) {
            const { A, D } = s;
            const pre = 0.2 * A, Tp = (A * D) / (2 * pre); // Halbsinus-Vor-/Nachpuls, je halbe Fläche des Hauptpulses
            if (tt >= 0 && tt <= D) return A * Math.sin(Math.PI * tt / D);
            if (tt < 0 && tt >= -Tp) return -pre * Math.sin(Math.PI * (tt + Tp) / Tp);
            if (tt > D && tt <= D + Tp) return -pre * Math.sin(Math.PI * (tt - D) / Tp);
            return 0;
        },
        window(s) {
            const Tp = s.D / 0.4;
            return [-Tp * 1.08, s.D + Tp * 1.08];
        },
        measured(s, eps) {
            const [t0, t1] = this.window(s);
            const N = 700;
            const loose = s.scenario === 'loose' && s.pulses >= 1 ? 0.55 : 1;
            // Ausschwingen je Resonanz; Anteil nach Massenanteil, Abklingen nach Güte
            const B = s.plant.modes.reduce((sum, m) => sum + m.beta, 0);
            const ring = s.plant.modes.map((m) => ({ w: TWO_PI * m.fr * loose, z: 1 / (2 * m.q), k: m.beta / B }));
            const pts = [];
            for (let i = 0; i <= N; i++) {
                const tt = t0 + (t1 - t0) * i / N;
                let a = this.ref(s, tt) * (1 + eps * 0.6);
                if (tt > 0) for (const r of ring) a += eps * r.k * s.A * Math.exp(-r.z * r.w * tt) * Math.sin(r.w * tt);
                a += gauss() * 0.006 * s.A;
                pts.push([tt, a]);
            }
            return pts;
        },
        fire(s) {
            const lvl = Math.pow(10, s.levels[s.levelIdx] / 20);
            // Schwach gedämpfte Resonanzen (hohe Güte) erschweren die Pulsregelung
            const qMax = Math.max(...s.plant.modes.map((m) => m.q));
            const eps0 = Math.min(0.8, 0.22 + 0.004 * qMax);
            let eps = eps0 * Math.pow(0.42, s.iteration) + 0.03;
            if (s.scenario === 'loose' && s.pulses >= 1 && s.levelIdx === s.levels.length - 1) {
                eps = 0.6;
                if (s.pulses === 1) log(t('Prüfling löst sich – Pulsform verändert sich', 'Specimen loosens – pulse shape changes'), 'warn');
            }
            const sScaled = { ...s, A: s.A * lvl };
            const pts = this.measured(sScaled, eps);
            // Weg aus doppelter Integration
            let v = 0, x = 0, xmax = 0;
            for (let i = 1; i < pts.length; i++) {
                const dt = pts[i][0] - pts[i - 1][0];
                v += pts[i][1] * G * dt;
                x += v * dt;
                xmax = Math.max(xmax, Math.abs(x));
            }
            s.anim = { pts, start: performance.now(), lvl, eps, xmax, scaledA: s.A * lvl };
            s.waiting = true;
        },
        evaluate(s) {
            const { pts, lvl, xmax } = s.anim;
            const A = s.A * lvl;
            let worst = 0, worstT = 0;
            pts.forEach(([tt, a]) => {
                const e = (a - this.ref({ ...s, A }, tt)) / A;
                if (Math.abs(e) > Math.abs(worst)) { worst = e; worstT = tt; }
            });
            s.last = { pts, lvl, worst, xmax, peak: Math.max(...pts.map((p) => p[1])) };
            const isFull = s.levelIdx === s.levels.length - 1;
            const where = t(`bei ${fmt(worstT * 1000, 3)} ms`, `at ${fmt(worstT * 1000, 3)} ms`);
            if (xmax * 2 > s.stroke) {
                setState('aborted', t('Hubgrenze', 'Stroke limit'));
                log(t(`Hubgrenze überschritten: ${fmt(xmax * 2000, 3)} mm Spitze-Spitze > ${fmt(s.stroke * 1000, 3)} mm`, `Stroke limit exceeded: ${fmt(xmax * 2000, 3)} mm pk-pk > ${fmt(s.stroke * 1000, 3)} mm`), 'abort');
                return 'abort';
            }
            const pctWorst = Math.abs(worst) * 100;
            if (Math.abs(worst) > s.abortTol && isFull) {
                setState('aborted', where);
                log(t(`Puls außerhalb der Abschaltgrenze (${fmt(pctWorst, 3)} % von A) ${where}`, `Pulse outside abort limit (${fmt(pctWorst, 3)} % of A) ${where}`), 'abort');
                return 'abort';
            }
            if (Math.abs(worst) > s.tol) {
                setState('warning', where);
                log(t(`Puls außerhalb Toleranzband (${fmt(pctWorst, 3)} % von A)`, `Pulse outside tolerance band (${fmt(pctWorst, 3)} % of A)`), 'warn');
            } else {
                setState('running');
                log(t(`Puls ${isFull ? s.pulses + 1 : '(Vorlauf)'} im Toleranzband – Abweichung ${fmt(pctWorst, 3)} %`, `Pulse ${isFull ? s.pulses + 1 : '(pre-test)'} within tolerance – error ${fmt(pctWorst, 3)} %`), 'ok');
            }
            s.iteration++;
            if (!isFull) {
                s.levelIdx++;
                log(t(`Pegel ${s.levels[s.levelIdx]} dB`, `Level ${s.levels[s.levelIdx]} dB`));
            } else {
                s.pulses++;
                if (s.pulses >= s.count) return 'done';
            }
            s.nextAt = performance.now() + 650;
            return 'run';
        },
        step(s, dtReal) {
            s.t += dtReal;
            if (!s.anim && s.nextAt && performance.now() >= s.nextAt) {
                s.nextAt = null;
                this.fire(s);
            }
            if (s.anim && performance.now() - s.anim.start > 900) {
                const res = this.evaluate(s);
                s.anim = null;
                s.waiting = false;
                return res;
            }
            return 'run';
        },
        draw(s) {
            const base = s || { A: val('peak'), D: val('duration') / 1000, tol: val('tol') / 100, abortTol: val('abortTol') / 100 };
            const lvl = s ? (s.anim ? s.anim.lvl : s.last ? s.last.lvl : Math.pow(10, s.levels[s.levelIdx] / 20)) : 1;
            const A = base.A * lvl;
            const sb = { ...base, A };
            const [t0, t1] = this.window(sb);
            const ts = Array.from({ length: 500 }, (_, i) => t0 + (t1 - t0) * i / 499);
            const ref = ts.map((tt) => [tt * 1000, this.ref(sb, tt)]);
            const off = (k) => ref.map(([x, y]) => [x, y + k * A]);
            let meas = [];
            if (s && s.anim) {
                const prog = Math.min(1, (performance.now() - s.anim.start) / 700);
                meas = s.anim.pts.slice(0, Math.max(2, Math.floor(s.anim.pts.length * prog))).map(([x, y]) => [x * 1000, y]);
            } else if (s && s.last) {
                meas = s.last.pts.map(([x, y]) => [x * 1000, y]);
            }
            drawPlot(canvas, {
                x: { min: t0 * 1000, max: t1 * 1000, label: t('Zeit in ms', 'Time in ms') },
                y: { min: -0.55 * A, max: 1.45 * A, label: t('Beschleunigung in g', 'Acceleration in g') },
                series: [
                    { points: off(base.abortTol), color: cssVar('--danger', '#d33a3a'), width: 1.2, dash: [6, 4] },
                    { points: off(-base.abortTol), color: cssVar('--danger', '#d33a3a'), width: 1.2, dash: [6, 4] },
                    { points: off(base.tol), color: cssVar('--shock', '#b45f00'), width: 1.2, dash: [3, 4] },
                    { points: off(-base.tol), color: cssVar('--shock', '#b45f00'), width: 1.2, dash: [3, 4] },
                    { points: ref, color: cssVar('--strong', '#0b1a26'), width: 1.6 },
                    ...(meas.length > 1 ? [{ points: meas, color: cssVar('--shock', '#b45f00'), width: 2.2 }] : []),
                ],
            });
        },
        readouts(s) {
            if (!s) {
                const A = val('peak'), D = val('duration') / 1000;
                return readouts([
                    ['Δv', `${fmt(2 * A * G * D / Math.PI, 3)} m/s`],
                    [t('Pegelstufen', 'Level steps'), '−12 / −6 / 0 dB'],
                    [t('Toleranz / Abschaltung', 'Tolerance / abort'), `±${fmt(val('tol'), 2)} / ±${fmt(val('abortTol'), 2)} %`],
                ]);
            }
            const L = s.last;
            readouts([
                [t('Pegel', 'Level'), `${s.levels[Math.min(s.levelIdx, s.levels.length - 1)]} dB`],
                [t('Pulse', 'Pulses'), `${s.pulses} / ${s.count}`],
                [t('Spitze (Ist)', 'Peak (control)'), L ? `${fmt(L.peak, 3)} g` : '–'],
                [t('Max. Abweichung', 'Max. error'), L ? `${fmt(Math.abs(L.worst) * 100, 3)} %` : '–', L && Math.abs(L.worst) > s.tol ? 'is-warn' : ''],
                [t('Hub Spitze-Spitze', 'Stroke pk-pk'), L ? `${fmt(L.xmax * 2000, 3)} mm` : '–'],
                [t('Hubgrenze', 'Stroke limit'), `${fmt(s.stroke * 1000, 3)} mm`],
            ]);
        },
    };

    const MODES = { sine: Sine, random: Random, shock: Shock };

    /* ---- Ablaufsteuerung ---- */
    const frame = (now) => {
        raf = null;
        const dt = Math.min(0.05, (now - last) / 1000);
        last = now;
        if (sim && (state === 'running' || state === 'warning')) {
            const res = MODES[mode].step(sim, dt);
            if (res === 'done') {
                setState('done');
                log(t('Prüfung ohne Abschaltung beendet', 'Test completed without abort'), 'ok');
            }
        }
        MODES[mode].draw(sim);
        MODES[mode].readouts(sim);
        if (sim && (state === 'running' || state === 'warning' || (mode === 'shock' && sim.anim))) raf = requestAnimationFrame(frame);
    };

    const kick = () => {
        if (!raf) { last = performance.now(); raf = requestAnimationFrame(frame); }
    };

    const reset = () => {
        sim = null;
        warnLogged = false;
        setState('idle');
        MODES[mode].draw(null);
        MODES[mode].readouts(null);
    };

    btnStart.addEventListener('click', () => {
        if (state === 'paused' && sim) {
            setState('running');
            log(t('Fortgesetzt', 'Resumed'));
            kick();
            return;
        }
        logEl.innerHTML = '';
        warnLogged = false;
        sim = MODES[mode].create();
        setState('running');
        log(t('Selbsttest und Übertragungsmessung bei niedrigem Pegel', 'Self-check and transfer function measurement at low level'));
        log(t('Regelung gestartet', 'Control started'), 'ok');
        if (mode === 'shock') Shock.fire(sim);
        kick();
    });
    btnPause.addEventListener('click', () => {
        setState('paused');
        log(t('Angehalten', 'Paused'));
    });
    btnStop.addEventListener('click', () => {
        if (state === 'running' || state === 'warning' || state === 'paused') log(t('Manuell gestoppt', 'Stopped manually'));
        reset();
    });

    modeButtons.forEach((b) =>
        b.addEventListener('click', () => {
            if (state === 'running' || state === 'warning') return;
            mode = b.dataset.simMode;
            root.dataset.mode = mode;
            modeButtons.forEach((x) => x.setAttribute('aria-selected', String(x === b)));
            setups.forEach((f) => (f.hidden = f.dataset.simSetup !== mode));
            root.querySelectorAll('[data-sim-only]').forEach((el) => (el.hidden = el.dataset.simOnly !== mode));
            logEl.innerHTML = '';
            reset();
        }),
    );

    // Vorlagen für die Resonanzen: "f1,q1,f2,q2" (leeres f2 = nur eine Resonanz)
    root.querySelectorAll('[data-sim-preset]').forEach((b) =>
        b.addEventListener('click', () => {
            const [f1, q1, f2, q2] = b.dataset.simPreset.split(',');
            const set = (n, v) => { const el = root.querySelector(`[data-sim-global] [name="${n}"]`); if (el && v) el.value = v; };
            set('r1f', f1); set('r1q', q1); set('r2f', f2); set('r2q', q2);
            root.querySelector('[data-sim-global] [name="r2on"]').checked = Boolean(f2);
            if (state === 'idle' || state === 'done' || state === 'aborted') reset();
        }),
    );

    root.addEventListener('input', () => {
        if (state === 'idle' || state === 'done' || state === 'aborted') reset();
    });
    new ResizeObserver(() => MODES[mode].draw(sim)).observe(canvas);
    reset();
}
