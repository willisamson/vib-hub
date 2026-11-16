// Shaker-Auslegung: Kraft, Hub und Geschwindigkeit aus Prüfprofilen und Vergleich mit Anlagen.
import { G, PSD_PRESETS, fmt, num, randomStats, resolveProfile } from './vibmath.js';
import { lang, t } from './i18n.js';

/** Zahl für Eingabefelder: ohne Tausendertrennzeichen, deutsches Komma. */
const inputNum = (v) => { const str = String(Number(v.toPrecision(4))); return lang === 'en' ? str : str.replace('.', ','); };

const TWO_PI = 2 * Math.PI;
const COMP = 0.2;     // Vor-/Nachpuls-Kompensation beim Schock (Anteil vom Spitzenwert)
const TARGET = 0.8;   // empfohlene Auslastung

export function initPlanner(root) {
    const form = root.querySelector('form');
    const models = JSON.parse(root.dataset.models || '[]');
    const rowsBody = root.querySelector('[data-psd-rows]');
    const out = (k) => root.querySelector(`[data-out="${k}"]`);
    const errorEl = root.querySelector('[data-error]');

    const addRow = (f = '', psd = '') => {
        const tr = document.createElement('tr');
        tr.innerHTML = `<td><input class="input input--mono" inputmode="decimal" aria-label="${t('Frequenz in Hz', 'Frequency in Hz')}"></td>`
            + `<td><input class="input input--mono" inputmode="decimal" aria-label="${t('PSD in g²/Hz', 'PSD in g²/Hz')}"></td>`;
        const [a, b] = tr.querySelectorAll('input');
        a.value = f === '' ? '' : inputNum(f);
        b.value = psd === '' ? '' : inputNum(psd);
        rowsBody.appendChild(tr);
    };

    const loadPreset = (key) => {
        const preset = PSD_PRESETS[key];
        if (!preset) return;
        rowsBody.innerHTML = '';
        resolveProfile(preset.rows).forEach((p) => addRow(p.f, p.psd));
    };

    const value = (name) => num(form.elements[name]?.value);
    const checked = (name) => !!form.elements[name]?.checked;

    function compute() {
        errorEl.hidden = true;
        const axis = form.elements.axis.value;
        root.querySelectorAll('[data-when-axis]').forEach((el) => { el.hidden = el.dataset.whenAxis !== axis; });
        ['sine', 'random', 'shock'].forEach((p) => root.querySelector(`[data-part="${p}"]`).classList.toggle('is-off', !checked(`use_${p}`)));

        const mDut = value('m_dut'), mFix = value('m_fix') || 0;
        const mSlip = axis === 'h' ? (value('m_slip') || 0) : 0;
        if (!(mDut > 0)) return fail(t('Bitte die Masse des Prüflings angeben.', 'Please enter the specimen mass.'));
        const payload = mDut + mFix;

        const need = { sine: null, random: null, shock: null, fMin: Infinity, fMax: 0 };
        const notes = [];

        if (checked('use_sine')) {
            const f1 = value('s_f1'), f2 = value('s_f2'), a = value('s_acc') * G, dpp = value('s_disp');
            if (!(f1 > 0 && f2 > f1 && a > 0)) return fail(t('Sinus: Frequenzbereich und Beschleunigung prüfen.', 'Sine: check frequency range and acceleration.'));
            let disp, vel;
            if (dpp > 0) {
                const x = dpp / 2000;                       // Spitze in m
                const fc = Math.sqrt(a / x) / TWO_PI;       // Übergangsfrequenz
                if (fc <= f1) { disp = 2 * a / (TWO_PI * f1) ** 2; vel = a / (TWO_PI * f1); }
                else { disp = 2 * x; vel = TWO_PI * Math.min(fc, f2) * x; }
                notes.push(t(`Übergangsfrequenz ${fmt(fc)} Hz.`, `Crossover frequency ${fmt(fc)} Hz.`));
            } else {
                disp = 2 * a / (TWO_PI * f1) ** 2; vel = a / (TWO_PI * f1);
            }
            need.sine = { acc: a, disp: disp * 1000, vel };
            need.fMin = Math.min(need.fMin, f1); need.fMax = Math.max(need.fMax, f2);
        }

        if (checked('use_random')) {
            const rows = [...rowsBody.querySelectorAll('tr')].map((tr) => {
                const [f, p] = tr.querySelectorAll('input');
                return { f: num(f.value), psd: num(p.value) };
            }).filter((r) => Number.isFinite(r.f) || Number.isFinite(r.psd));
            let stats;
            try { stats = randomStats(resolveProfile(rows)); } catch (e) {
                return fail(t('Random: ', 'Random: ') + t(e.message, 'check frequency and PSD values (ascending frequencies, at least two rows).'));
            }
            need.random = { grms: stats.grms, disp: stats.dpp3s * 1000, vel: 3 * stats.vrms, f1: rows[0].f, f2: rows[rows.length - 1].f };
            need.fMin = Math.min(need.fMin, rows[0].f); need.fMax = Math.max(need.fMax, rows[rows.length - 1].f);
            notes.push(t(`Random ${fmt(stats.grms)} grms.`, `Random ${fmt(stats.grms)} grms.`));
        }

        if (checked('use_shock')) {
            const A = value('h_acc') * G, D = value('h_dur') / 1000;
            if (!(A > 0 && D > 0)) return fail(t('Schock: Spitzenwert und Dauer prüfen.', 'Shock: check peak value and duration.'));
            const dv = 2 * A * D / Math.PI;
            const vel = dv / 2;
            // Vor- und Nachpuls je Δv²/(8·p·A), dazu die Bewegung während der ersten Pulshälfte
            const disp = (dv * dv) / (8 * COMP * A) + dv * D / (2 * Math.PI);
            need.shock = { acc: A, dv, disp: disp * 1000, vel };
            notes.push(t(`Schock Δv ${fmt(dv)} m/s.`, `Shock Δv ${fmt(dv)} m/s.`));
        }

        if (!need.sine && !need.random && !need.shock) return fail(t('Mindestens eine Prüfart auswählen.', 'Select at least one test type.'));

        // Bedarf ohne Anker (Prüfling + Vorrichtung + ggf. Gleittischplatte)
        const m0 = payload + mSlip;
        out('mass').textContent = fmt(m0);
        out('f-sine').textContent = need.sine ? fmt(m0 * need.sine.acc / 1000) : '–';
        out('f-random').textContent = need.random ? fmt(m0 * need.random.grms * G / 1000) : '–';
        out('f-shock').textContent = need.shock ? fmt(m0 * need.shock.acc / 1000) : '–';
        const dispMax = Math.max(need.sine?.disp ?? 0, need.random?.disp ?? 0, need.shock?.disp ?? 0);
        const velMax = Math.max(need.sine?.vel ?? 0, need.random?.vel ?? 0, need.shock?.vel ?? 0);
        out('disp').textContent = fmt(dispMax);
        out('vel').textContent = fmt(velMax);
        out('detail').textContent = notes.join(' ');

        // Anlagen bewerten
        const rated = models.map((m) => rate(m, need, m0, payload, axis));
        renderModels(rated);
        const pick = rated.find((r) => r.worst <= TARGET) || rated.find((r) => r.worst <= 1);
        renderPick(pick, need, { mDut, mFix, mSlip, axis, dispMax, velMax });
    }

    function rate(m, need, m0, payload, axis) {
        const M = m0 + m.moving;
        const u = {};
        if (need.sine) u.sine = (M * need.sine.acc / 1000) / m.sine;
        if (need.random) u.random = (M * need.random.grms * G / 1000) / m.random;
        if (need.shock) u.shock = (M * need.shock.acc / 1000) / m.shock;
        const dispCont = Math.max(need.sine?.disp ?? 0, need.random?.disp ?? 0);
        u.disp = Math.max(dispCont / m.disp, need.shock ? need.shock.disp / m.dispShock : 0);
        const velCont = Math.max(need.sine?.vel ?? 0, need.random?.vel ?? 0);
        u.vel = Math.max(velCont / m.vel, need.shock ? need.shock.vel / m.velShock : 0);
        u.load = m.load ? payload / m.load : 0;
        const freqOk = need.fMax <= m.fMax && (need.fMin === Infinity || need.fMin >= m.fMin);
        const worst = Math.max(...Object.values(u)) * (freqOk ? 1 : 10);
        return { m, u, worst, freqOk, mass: M };
    }

    const cell = (x) => {
        if (x === undefined) return '<td class="is-na">–</td>';
        const cls = x <= TARGET ? 'is-ok' : (x <= 1 ? 'is-tight' : 'is-bad');
        return `<td class="${cls}">${fmt(x * 100, 2)} %</td>`;
    };

    function renderModels(rated) {
        const body = root.querySelector('[data-model-rows]');
        body.innerHTML = rated.map((r) => {
            const verdict = !r.freqOk ? t('Frequenz', 'Frequency')
                : r.worst <= TARGET ? t('geeignet', 'suitable')
                : r.worst <= 1 ? t('knapp', 'tight') : t('zu klein', 'too small');
            const cls = !r.freqOk || r.worst > 1 ? 'is-bad' : (r.worst <= TARGET ? 'is-ok' : 'is-tight');
            return `<tr><th scope="row">${r.m.name}<small>${fmt(r.m.sine)} kN · ${t('Anker', 'armature')} ${fmt(r.m.moving)} kg</small></th>`
                + cell(r.u.sine) + cell(r.u.random) + cell(r.u.shock) + cell(r.u.disp) + cell(r.u.vel) + cell(r.u.load || undefined)
                + `<td class="planner__verdict ${cls}">${verdict}</td></tr>`;
        }).join('');
    }

    function renderPick(pick, need, info) {
        const box = root.querySelector('[data-pick]');
        if (!models.length) { box.hidden = true; return; }
        box.hidden = false;
        const link = box.querySelector('[data-inquiry]');
        const base = new URL(link.href, location.href);
        base.search = '';
        let name, text;
        if (pick) {
            name = pick.m.name;
            text = pick.worst <= TARGET
                ? t(`Höchste Auslastung ${fmt(pick.worst * 100, 2)} % – genug Reserve für Vorrichtungen und spätere Prüflinge.`, `Highest utilisation ${fmt(pick.worst * 100, 2)} % – enough reserve for fixtures and future specimens.`)
                : t(`Auslastung ${fmt(pick.worst * 100, 2)} % – machbar, aber ohne Reserve. Wir prüfen mit Ihnen Alternativen.`, `Utilisation ${fmt(pick.worst * 100, 2)} % – feasible but without reserve. We will check alternatives with you.`);
        } else {
            name = t('Sonderlösung', 'Special solution');
            text = t('Keine Anlage der Liste schafft alle Profile. Mehrere Anlagen, ein größeres System oder angepasste Profile – wir beraten Sie.', 'No system in the list covers all profiles. Several systems, a larger system or adapted profiles – we will advise you.');
        }
        box.querySelector('[data-pick-name]').textContent = name;
        box.querySelector('[data-pick-text]').textContent = text;

        const lines = [
            t('Shaker-Auslegung von vib-hub.com', 'Shaker sizing from vib-hub.com'),
            `${t('Prüfling', 'Specimen')}: ${fmt(info.mDut)} kg, ${t('Vorrichtung', 'fixture')}: ${fmt(info.mFix)} kg, ${info.axis === 'h' ? t(`vertikal + horizontal (Gleittischplatte ${fmt(info.mSlip)} kg)`, `vertical + horizontal (slip plate ${fmt(info.mSlip)} kg)`) : t('nur vertikal', 'vertical only')}`,
            need.sine ? `${t('Sinus', 'Sine')}: ${form.elements.s_f1.value}–${form.elements.s_f2.value} Hz, ${form.elements.s_disp.value || '–'} mm ${t('Sp-Sp', 'pk-pk')}, ${form.elements.s_acc.value} g` : null,
            need.random ? `Random: ${fmt(need.random.grms)} grms, ${inputNum(need.random.f1)}–${inputNum(need.random.f2)} Hz` : null,
            need.shock ? `${t('Schock', 'Shock')}: ${form.elements.h_acc.value} g / ${form.elements.h_dur.value} ms ${t('Halbsinus', 'half-sine')}` : null,
            `${t('Bedarf', 'Demand')}: ${t('Weg', 'stroke')} ${fmt(info.dispMax)} mm, ${t('Geschwindigkeit', 'velocity')} ${fmt(info.velMax)} m/s`,
            `${t('Beispielauslegung', 'Example sizing')}: ${name}`,
            '',
            t('Bitte ergänzen: Aufstellort, Gleittisch/Klimakammer, Zeitrahmen.', 'Please add: installation site, slip table/climatic chamber, time frame.'),
        ].filter((l) => l !== null);
        base.searchParams.set('thema', 'pruefstand');
        base.searchParams.set('bezug', `${t('Shaker-Auslegung', 'Shaker sizing')}: ${name}`);
        base.searchParams.set('text', lines.join('\n'));
        link.href = base.toString();
    }

    function fail(msg) {
        errorEl.textContent = msg;
        errorEl.hidden = false;
        root.querySelector('[data-pick]').hidden = true;
    }

    loadPreset('gevs');
    form.elements.r_preset.addEventListener('change', (e) => { if (e.target.value !== 'custom') loadPreset(e.target.value); compute(); });
    root.querySelector('[data-psd-add]').addEventListener('click', () => { addRow(); form.elements.r_preset.value = 'custom'; });
    root.querySelector('[data-psd-remove]').addEventListener('click', () => {
        if (rowsBody.children.length > 2) rowsBody.lastElementChild.remove();
        form.elements.r_preset.value = 'custom';
        compute();
    });
    rowsBody.addEventListener('input', () => { form.elements.r_preset.value = 'custom'; });
    form.addEventListener('input', compute);
    form.addEventListener('change', compute);
    compute();
}
