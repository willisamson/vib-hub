// Schlanker Canvas-Plotter für Diagramme (linear/logarithmisch) – ohne externe Bibliothek.

const css = (name, fallback) =>
    getComputedStyle(document.documentElement).getPropertyValue(name).trim() || fallback;

function niceStep(range, target) {
    const raw = range / target;
    const pow = Math.pow(10, Math.floor(Math.log10(raw)));
    const n = raw / pow;
    return (n < 1.5 ? 1 : n < 3 ? 2 : n < 7 ? 5 : 10) * pow;
}

function ticks(axis, px) {
    const out = [];
    if (axis.log) {
        const a = Math.floor(Math.log10(axis.min));
        const b = Math.ceil(Math.log10(axis.max));
        const decades = b - a;
        for (let e = a; e <= b; e++) {
            for (const m of [1, 2, 5]) {
                const v = m * Math.pow(10, e);
                if (v < axis.min * 0.999 || v > axis.max * 1.001) continue;
                const major = m === 1;
                out.push({ v, major, label: major || (decades <= 3 && px > 360) });
            }
        }
        return out;
    }
    const step = niceStep(axis.max - axis.min, Math.max(3, Math.round(px / 90)));
    for (let v = Math.ceil(axis.min / step) * step; v <= axis.max + step * 1e-6; v += step) {
        out.push({ v: Math.abs(v) < step * 1e-9 ? 0 : v, major: true, label: true });
    }
    return out;
}

function label(v) {
    const abs = Math.abs(v);
    if (abs === 0) return '0';
    if (abs >= 10000 || abs < 0.001) {
        const e = Math.floor(Math.log10(abs));
        const m = v / Math.pow(10, e);
        return (Math.abs(m - 1) < 1e-9 ? '' : `${+m.toFixed(1)}·`) + `10${superscript(e)}`;
    }
    return (+v.toPrecision(3)).toLocaleString(document.documentElement.lang === 'en' ? 'en-US' : 'de-DE');
}

function superscript(n) {
    const map = { '-': '⁻', 0: '⁰', 1: '¹', 2: '²', 3: '³', 4: '⁴', 5: '⁵', 6: '⁶', 7: '⁷', 8: '⁸', 9: '⁹' };
    return String(n).split('').map((c) => map[c] ?? c).join('');
}

export function sizeCanvas(canvas) {
    const dpr = Math.min(window.devicePixelRatio || 1, 2);
    const w = canvas.clientWidth, h = canvas.clientHeight;
    if (canvas.width !== Math.round(w * dpr) || canvas.height !== Math.round(h * dpr)) {
        canvas.width = Math.round(w * dpr);
        canvas.height = Math.round(h * dpr);
    }
    const ctx = canvas.getContext('2d');
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    return { ctx, w, h };
}

/**
 * opts: { x: {min,max,log,label}, y: {min,max,log,label}, series: [{points, color, width, fill, dash}],
 *         bands: [{x0,x1,y0,y1,color}], vlines: [{x,color,label,bottom}], markers: [{x,y,color,label}] }
 */
export function drawPlot(canvas, opts) {
    const { ctx, w, h } = sizeCanvas(canvas);
    const muted = css('--muted', '#8da2ae');
    const grid = css('--line', '#1f3442');
    const mono = css('--font-mono', 'monospace');
    const pad = { l: 58, r: 16, t: 14, b: 40 };
    const pw = w - pad.l - pad.r, ph = h - pad.t - pad.b;
    const { x, y } = opts;

    const mapX = (v) => pad.l + (x.log ? Math.log(v / x.min) / Math.log(x.max / x.min) : (v - x.min) / (x.max - x.min)) * pw;
    const mapY = (v) => pad.t + ph - (y.log ? Math.log(v / y.min) / Math.log(y.max / y.min) : (v - y.min) / (y.max - y.min)) * ph;

    ctx.clearRect(0, 0, w, h);
    ctx.font = `11px ${mono}`;
    ctx.lineWidth = 1;

    // Bänder (z. B. Zonen)
    for (const b of opts.bands ?? []) {
        const x0 = b.x0 !== undefined ? mapX(Math.max(b.x0, x.min)) : pad.l;
        const x1 = b.x1 !== undefined ? mapX(Math.min(b.x1, x.max)) : pad.l + pw;
        const y0 = b.y0 !== undefined ? mapY(Math.max(b.y0, y.min)) : pad.t + ph;
        const y1 = b.y1 !== undefined ? mapY(Math.min(b.y1, y.max)) : pad.t;
        ctx.fillStyle = b.color;
        ctx.fillRect(Math.min(x0, x1), Math.min(y0, y1), Math.abs(x1 - x0), Math.abs(y1 - y0));
    }

    // Gitter + Beschriftung
    ctx.textAlign = 'center';
    ctx.textBaseline = 'top';
    for (const t of ticks(x, pw)) {
        const px = Math.round(mapX(t.v)) + 0.5;
        ctx.strokeStyle = grid;
        ctx.globalAlpha = t.major ? 1 : 0.5;
        ctx.beginPath(); ctx.moveTo(px, pad.t); ctx.lineTo(px, pad.t + ph); ctx.stroke();
        ctx.globalAlpha = 1;
        if (t.label) { ctx.fillStyle = muted; ctx.fillText(label(t.v), px, pad.t + ph + 6); }
    }
    ctx.textAlign = 'right';
    ctx.textBaseline = 'middle';
    for (const t of ticks(y, ph)) {
        const py = Math.round(mapY(t.v)) + 0.5;
        ctx.strokeStyle = grid;
        ctx.globalAlpha = t.major ? 1 : 0.5;
        ctx.beginPath(); ctx.moveTo(pad.l, py); ctx.lineTo(pad.l + pw, py); ctx.stroke();
        ctx.globalAlpha = 1;
        if (t.label) { ctx.fillStyle = muted; ctx.fillText(label(t.v), pad.l - 8, py); }
    }

    // Achsentitel
    ctx.fillStyle = muted;
    ctx.textAlign = 'right';
    ctx.textBaseline = 'bottom';
    if (x.label) ctx.fillText(x.label, pad.l + pw, h - 2);
    ctx.save();
    ctx.translate(12, pad.t);
    ctx.rotate(-Math.PI / 2);
    ctx.textAlign = 'right';
    ctx.textBaseline = 'top';
    if (y.label) ctx.fillText(y.label, 0, 0);
    ctx.restore();

    // Clip auf Plotfläche
    ctx.save();
    ctx.beginPath();
    ctx.rect(pad.l, pad.t, pw, ph);
    ctx.clip();

    for (const l of opts.vlines ?? []) {
        const px = mapX(l.x);
        ctx.strokeStyle = l.color;
        ctx.setLineDash([4, 4]);
        ctx.beginPath(); ctx.moveTo(px, pad.t); ctx.lineTo(px, pad.t + ph); ctx.stroke();
        ctx.setLineDash([]);
        if (l.label) {
            ctx.fillStyle = l.color;
            ctx.textAlign = px > pad.l + pw * 0.7 ? 'right' : 'left';
            ctx.textBaseline = l.bottom ? 'bottom' : 'top';
            ctx.fillText(l.label, px + (ctx.textAlign === 'left' ? 6 : -6), l.bottom ? pad.t + ph - 6 : pad.t + 6);
        }
    }

    for (const s of opts.series ?? []) {
        const pts = s.points.filter(([px, py]) => Number.isFinite(py) && (!y.log || py > 0) && (!x.log || px > 0));
        if (pts.length < 2) continue;
        ctx.beginPath();
        pts.forEach(([px, py], i) => {
            const X = mapX(px), Y = mapY(Math.min(Math.max(py, y.log ? y.min * 0.5 : y.min - (y.max - y.min)), y.max * 2));
            i ? ctx.lineTo(X, Y) : ctx.moveTo(X, Y);
        });
        if (s.fill) {
            ctx.save();
            ctx.lineTo(mapX(pts[pts.length - 1][0]), pad.t + ph);
            ctx.lineTo(mapX(pts[0][0]), pad.t + ph);
            ctx.closePath();
            ctx.fillStyle = s.fill;
            ctx.fill();
            ctx.restore();
            ctx.beginPath();
            pts.forEach(([px, py], i) => (i ? ctx.lineTo(mapX(px), mapY(py)) : ctx.moveTo(mapX(px), mapY(py))));
        }
        ctx.strokeStyle = s.color;
        ctx.lineWidth = s.width ?? 2;
        ctx.lineJoin = 'round';
        ctx.setLineDash(s.dash ?? []);
        ctx.stroke();
        ctx.setLineDash([]);
        ctx.lineWidth = 1;
    }

    for (const m of opts.markers ?? []) {
        const X = mapX(m.x), Y = mapY(m.y);
        ctx.fillStyle = m.color;
        ctx.beginPath(); ctx.arc(X, Y, 4.5, 0, Math.PI * 2); ctx.fill();
        ctx.strokeStyle = m.color;
        ctx.globalAlpha = 0.35;
        ctx.beginPath(); ctx.arc(X, Y, 9, 0, Math.PI * 2); ctx.stroke();
        ctx.globalAlpha = 1;
        if (m.label) {
            ctx.font = `12px ${mono}`;
            ctx.textAlign = X > pad.l + pw * 0.7 ? 'right' : 'left';
            ctx.textBaseline = 'bottom';
            ctx.fillText(m.label, X + (ctx.textAlign === 'left' ? 12 : -12), Y - 8);
        }
    }
    ctx.restore();

    // Rahmen
    ctx.strokeStyle = grid;
    ctx.strokeRect(pad.l + 0.5, pad.t + 0.5, pw, ph);

    return { mapX, mapY, pad, pw, ph };
}

/** Logarithmisch verteilte Frequenzpunkte. */
export function logspace(a, b, n) {
    const out = [];
    const la = Math.log(a), lb = Math.log(b);
    for (let i = 0; i < n; i++) out.push(Math.exp(la + ((lb - la) * i) / (n - 1)));
    out[0] = a;
    out[n - 1] = b; // exakte Endpunkte (Rundungsfehler würden aus dem Profil fallen)
    return out;
}
