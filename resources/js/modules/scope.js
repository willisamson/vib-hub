// Oszilloskop-Darstellung für Sinus, Random und Schock (Hero + kleine Signalanzeigen).
import { G, fmt, randomStats, resolveProfile, PSD_PRESETS, shock, sine } from './vibmath.js';
import { sizeCanvas } from './plot.js';

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const cssVar = (name, fb) => getComputedStyle(document.documentElement).getPropertyValue(name).trim() || fb;

/* ------------------------------------------------------------- Signale */

// Pseudo-Zufall mit festem Startwert, damit das Rauschen bei jedem Laden gleich "klingt".
function rng(seed) {
    let s = seed >>> 0;
    return () => ((s = (s * 1664525 + 1013904223) >>> 0) / 4294967296);
}

const rand = rng(7);
const COMPONENTS = Array.from({ length: 56 }, (_, i) => {
    const cycles = 3 + Math.pow(rand(), 1.4) * 70;                // Schwingungen über die Bildbreite
    return { cycles, phase: rand() * Math.PI * 2, speed: (rand() - 0.5) * 7, amp: 1 };
});
const RMS_TARGET = 0.2;
const ampNorm = RMS_TARGET / Math.sqrt(COMPONENTS.length / 2);

export const MODES = {
    sine: {
        name: 'Sinus',
        color: '--sine',
        spanMs: 50,
        unitScale: 5 / 0.62,       // 5 g Spitze bei y = 0.62
        signal: (x, t) => 0.62 * Math.sin(2 * Math.PI * (4 * x) - t * 2.2),
    },
    random: {
        name: 'Random',
        color: '--random',
        spanMs: 50,
        unitScale: null,           // wird aus Grms abgeleitet
        signal: (x, t) => {
            let y = 0;
            for (const c of COMPONENTS) y += Math.sin(2 * Math.PI * c.cycles * x + c.phase + t * c.speed);
            return y * ampNorm;
        },
    },
    shock: {
        name: 'Schock',
        color: '--shock',
        spanMs: 100,
        unitScale: 50 / 0.85,      // 50 g Spitze bei y = 0.85
        signal: (x, t, o = {}) => {
            const x0 = o.pulseAt ?? 0.18, w = 0.11; // 11 ms von 100 ms
            let y = 0;
            if (x >= x0 && x <= x0 + w) y += 0.85 * Math.sin(Math.PI * (x - x0) / w);
            if (x > x0 + w) {
                const u = x - (x0 + w);
                y += -0.16 * Math.exp(-u * 9) * Math.sin(2 * Math.PI * u * 17);
            }
            return y;
        },
    },
};

// Ehrliche Kennwerte für die Anzeige (aus den Rechenfunktionen).
const navmat = randomStats(resolveProfile(PSD_PRESETS.navmat.rows));
MODES.random.unitScale = navmat.grms / RMS_TARGET;
const s80 = sine(80, 'acc', 5 * G);
const hs = shock('halfsine', 50, 11);

export const READOUTS = {
    sine: [
        ['Frequenz', '80 Hz'],
        ['Spitze', '5 g'],
        ['Effektiv', `${fmt(s80.accRms / G, 3)} g`],
        ['Weg', `±${fmt(s80.dispPeak * 1000, 3)} mm`],
    ],
    random: [
        ['Band', '20–2000 Hz'],
        ['PSD', '0,04 g²/Hz'],
        ['Effektiv', `${fmt(navmat.grms, 3)} g`],
        ['Profil', 'NAVMAT'],
    ],
    shock: [
        ['Puls', 'Halbsinus'],
        ['Spitze', '50 g'],
        ['Dauer', '11 ms'],
        ['Δv', `${fmt(hs.dv, 3)} m/s`],
    ],
};

export const NORMS = { sine: 'DIN EN 60068-2-6', random: 'DIN EN 60068-2-64', shock: 'DIN EN 60068-2-27' };

/* -------------------------------------------------------------- Scope */

export class Scope {
    constructor(canvas, opts = {}) {
        this.canvas = canvas;
        this.opts = { grid: true, interactive: false, trail: true, ...opts };
        this.mode = opts.mode ?? 'sine';
        this.prevMode = this.mode;
        this.blend = 1;
        this.blendStart = 0;
        this.t = 0;
        this.last = 0;
        this.visible = true;
        this.pointer = null;
        this.sweepStart = performance.now();
        this.frame = this.frame.bind(this);

        if (this.opts.interactive) {
            canvas.addEventListener('pointermove', (e) => {
                const r = canvas.getBoundingClientRect();
                this.pointer = { x: (e.clientX - r.left) / r.width, y: (e.clientY - r.top) / r.height };
                if (reduceMotion) this.draw(performance.now());
            });
            canvas.addEventListener('pointerleave', () => {
                this.pointer = null;
                if (reduceMotion) this.draw(performance.now());
            });
        }

        new IntersectionObserver(([entry]) => {
            this.visible = entry.isIntersecting;
            if (this.visible && !reduceMotion) this.start();
        }).observe(canvas);

        new ResizeObserver(() => this.draw(performance.now(), true)).observe(canvas);

        if (reduceMotion) this.draw(performance.now(), true);
        else this.start();
    }

    start() {
        if (this.raf) return;
        this.last = performance.now();
        this.raf = requestAnimationFrame(this.frame);
    }

    setMode(mode) {
        if (!MODES[mode] || mode === this.mode) return;
        this.prevMode = this.mode;
        this.mode = mode;
        this.blend = 0;
        this.blendStart = performance.now();
        this.sweepStart = performance.now();
        if (reduceMotion) { this.blend = 1; this.draw(performance.now(), true); }
    }

    frame(now) {
        this.raf = null;
        if (!this.visible || document.hidden) return;
        const dt = Math.min(0.05, (now - this.last) / 1000);
        this.last = now;
        this.t += dt;
        if (this.blend < 1) this.blend = Math.min(1, (now - this.blendStart) / 650);
        this.draw(now);
        this.raf = requestAnimationFrame(this.frame);
    }

    value(x) {
        const k = this.blend < 1 ? 0.5 - 0.5 * Math.cos(Math.PI * this.blend) : 1;
        const a = MODES[this.mode].signal(x, this.t, this.opts);
        if (k >= 1) return a;
        return k * a + (1 - k) * MODES[this.prevMode].signal(x, this.t, this.opts);
    }

    draw(now, hard = false) {
        const { ctx, w, h } = sizeCanvas(this.canvas);
        if (!w || !h) return;
        const bg = cssVar('--scope-bg', '#06121c');
        const color = cssVar(MODES[this.mode].color, '#3ee6ff');
        const gridColor = cssVar('--scope-grid', 'rgba(140,190,210,.13)');

        // Nachleuchten: vorheriges Bild nur abdunkeln statt löschen
        ctx.globalCompositeOperation = 'source-over';
        ctx.fillStyle = bg;
        ctx.globalAlpha = hard || reduceMotion || !this.opts.trail ? 1 : 0.42;
        ctx.fillRect(0, 0, w, h);
        ctx.globalAlpha = 1;

        const mid = h / 2;
        const amp = h * 0.46;

        if (this.opts.grid) this.drawGrid(ctx, w, h, gridColor);

        // Schock: Strahl läuft von links nach rechts (getriggerte Darstellung)
        let xEnd = 1;
        if (this.mode === 'shock' && this.opts.grid && !reduceMotion && this.blend >= 1) {
            const cycle = 3200, sweep = 1100;
            const p = ((now - this.sweepStart) % cycle);
            xEnd = Math.min(1, p / sweep);
        }

        const steps = Math.min(900, Math.round(w / 1.2));
        const path = new Path2D();
        for (let i = 0; i <= steps; i++) {
            const x = (i / steps) * xEnd;
            const y = mid - this.value(x) * amp;
            i ? path.lineTo(x * w, y) : path.moveTo(x * w, y);
        }

        ctx.lineJoin = 'round';
        ctx.lineCap = 'round';
        ctx.globalCompositeOperation = 'lighter';
        ctx.strokeStyle = color;
        ctx.globalAlpha = 0.14;
        ctx.lineWidth = this.opts.grid ? 9 : 6;
        ctx.stroke(path);
        ctx.globalAlpha = 0.9;
        ctx.lineWidth = this.opts.grid ? 2 : 1.6;
        ctx.stroke(path);
        ctx.globalAlpha = 1;
        ctx.globalCompositeOperation = 'source-over';

        // Strahlpunkt
        if (xEnd < 1) {
            const y = mid - this.value(xEnd) * amp;
            ctx.fillStyle = '#fff';
            ctx.beginPath(); ctx.arc(xEnd * w, y, 2.6, 0, Math.PI * 2); ctx.fill();
        }

        if (this.opts.interactive && this.pointer) this.drawCursor(ctx, w, h, mid, amp, color);
    }

    drawGrid(ctx, w, h, gridColor) {
        const divY = 8;
        const div = h / divY;
        const divX = Math.max(6, Math.round(w / div));
        const dx = w / divX;
        ctx.strokeStyle = gridColor;
        ctx.lineWidth = 1;
        ctx.beginPath();
        for (let i = 1; i < divX; i++) { const x = Math.round(i * dx) + 0.5; ctx.moveTo(x, 0); ctx.lineTo(x, h); }
        for (let j = 1; j < divY; j++) { const y = Math.round(j * div) + 0.5; ctx.moveTo(0, y); ctx.lineTo(w, y); }
        ctx.stroke();
        // Feinteilung auf der Mittelachse
        ctx.beginPath();
        const my = Math.round(h / 2) + 0.5;
        for (let x = 0; x < w; x += dx / 5) { ctx.moveTo(x, my - 3); ctx.lineTo(x, my + 3); }
        ctx.stroke();
    }

    drawCursor(ctx, w, h, mid, amp, color) {
        const m = MODES[this.mode];
        const x = this.pointer.x;
        const v = this.value(x);
        const px = x * w, py = mid - v * amp;
        ctx.strokeStyle = 'rgba(231,238,242,.35)';
        ctx.setLineDash([3, 4]);
        ctx.beginPath(); ctx.moveTo(px + 0.5, 0); ctx.lineTo(px + 0.5, h); ctx.stroke();
        ctx.beginPath(); ctx.moveTo(0, py + 0.5); ctx.lineTo(w, py + 0.5); ctx.stroke();
        ctx.setLineDash([]);
        ctx.fillStyle = color;
        ctx.beginPath(); ctx.arc(px, py, 4, 0, Math.PI * 2); ctx.fill();

        const text = `t ${fmt(x * m.spanMs, 3)} ms   a ${fmt(v * m.unitScale, 3)} g`;
        ctx.font = `13px ${cssVar('--font-mono', 'monospace')}`;
        const tw = ctx.measureText(text).width + 18;
        let bx = px + 14, by = py - 38;
        if (bx + tw > w - 8) bx = px - tw - 14;
        if (by < 8) by = py + 14;
        ctx.fillStyle = 'rgba(6,18,28,.88)';
        ctx.strokeStyle = 'rgba(231,238,242,.18)';
        ctx.beginPath();
        ctx.roundRect(bx, by, tw, 26, 6);
        ctx.fill(); ctx.stroke();
        ctx.fillStyle = '#e7eef2';
        ctx.textBaseline = 'middle';
        ctx.fillText(text, bx + 9, by + 13.5);
    }
}

/* ---------------------------------------------------------- Hero-Steuerung */

export function initHeroScope(root) {
    const canvas = root.querySelector('[data-scope-canvas]');
    if (!canvas) return;
    const scope = new Scope(canvas, { interactive: true, mode: 'sine', pulseAt: 0.52 });
    const buttons = [...root.querySelectorAll('[data-scope-mode]')];
    const readouts = root.querySelector('[data-scope-readouts]');
    const normEl = root.querySelector('[data-scope-norm]');
    let auto = !reduceMotion;
    let timer;

    const render = (mode) => {
        scope.setMode(mode);
        root.dataset.mode = mode;
        buttons.forEach((b) => b.setAttribute('aria-pressed', String(b.dataset.scopeMode === mode)));
        if (readouts) {
            readouts.innerHTML = READOUTS[mode]
                .map(([k, v]) => `<div><dt>${k}</dt><dd>${v}</dd></div>`)
                .join('');
        }
        if (normEl) normEl.textContent = NORMS[mode];
    };

    buttons.forEach((b) =>
        b.addEventListener('click', () => {
            auto = false;
            clearInterval(timer);
            render(b.dataset.scopeMode);
        }),
    );

    render('sine');
    const order = ['sine', 'random', 'shock'];
    if (auto) {
        timer = setInterval(() => {
            if (!auto || document.hidden) return;
            const next = order[(order.indexOf(scope.mode) + 1) % order.length];
            render(next);
        }, 6500);
    }
}

export function initMiniScopes() {
    document.querySelectorAll('[data-mini-scope]').forEach((canvas) => {
        new Scope(canvas, { mode: canvas.dataset.miniScope, grid: false, trail: false });
    });
}
