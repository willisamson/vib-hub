// Reine Rechenfunktionen der Schwingungstechnik (ohne DOM) – auch in Node testbar.

export const G = 9.80665; // Normfallbeschleunigung in m/s²
const TWO_PI = 2 * Math.PI;

/* ------------------------------------------------------------------ Sinus */

/**
 * Harmonische Schwingung: aus einer Größe (Spitzenwerte) alle anderen.
 * kind: 'acc' (m/s²), 'vel' (m/s), 'disp' (m, Spitze)
 */
export function sine(f, kind, value) {
    const w = TWO_PI * f;
    let a, v, x;
    if (kind === 'acc') { a = value; v = a / w; x = a / (w * w); }
    else if (kind === 'vel') { v = value; a = v * w; x = v / w; }
    else { x = value; v = x * w; a = x * w * w; }
    return {
        accPeak: a,                 // m/s²
        accPeakG: a / G,
        accRms: a / Math.SQRT2,
        velPeak: v,                 // m/s
        velRms: v / Math.SQRT2,
        dispPeak: x,                // m
        dispPkPk: 2 * x,
    };
}

/** Übergangsfrequenz zwischen konstantem Weg x (Spitze, m) und konstanter Beschleunigung a (m/s²). */
export function crossoverFrequency(dispPeak, accPeak) {
    return Math.sqrt(accPeak / dispPeak) / TWO_PI;
}

/* ----------------------------------------------------------------- Random */

const LOG2_DB = 10 * Math.log10(2); // ≈ 3.0103 dB je Oktave bei Faktor 2

/**
 * PSD-Profil aus Stützstellen. Jede Zeile: { f, psd } oder { f, slope } (dB/Oktave
 * bezogen auf die vorherige Stützstelle). Erste Zeile braucht psd.
 * Liefert vollständige Stützstellen [{ f, psd }] oder wirft einen Fehler.
 */
export function resolveProfile(rows) {
    const out = [];
    rows.forEach((r, i) => {
        const f = Number(r.f);
        if (!(f > 0)) throw new Error(`Zeile ${i + 1}: Frequenz fehlt oder ist ungültig.`);
        if (out.length && f <= out[out.length - 1].f) throw new Error(`Zeile ${i + 1}: Frequenzen müssen aufsteigend sein.`);
        let psd = r.psd === '' || r.psd === null || r.psd === undefined ? NaN : Number(r.psd);
        if (!(psd > 0)) {
            const slope = r.slope === '' || r.slope === null || r.slope === undefined ? NaN : Number(r.slope);
            if (!out.length || !Number.isFinite(slope)) throw new Error(`Zeile ${i + 1}: PSD-Wert oder Steigung (dB/Okt.) angeben.`);
            const prev = out[out.length - 1];
            const n = slope / LOG2_DB;
            psd = prev.psd * Math.pow(f / prev.f, n);
        }
        out.push({ f, psd });
    });
    if (out.length < 2) throw new Error('Mindestens zwei Stützstellen angeben.');
    return out;
}

/** Steigung zwischen zwei Stützstellen in dB/Oktave. */
export function slopeDbPerOct(p1, p2) {
    return 10 * Math.log10(p2.psd / p1.psd) / Math.log2(p2.f / p1.f);
}

/** Fläche unter einem log-log-linearen PSD-Segment (exakt). */
function segmentArea(p1, p2) {
    const n = Math.log(p2.psd / p1.psd) / Math.log(p2.f / p1.f);
    if (Math.abs(n + 1) < 1e-9) return p1.psd * p1.f * Math.log(p2.f / p1.f);
    return (p1.psd / Math.pow(p1.f, n)) * (Math.pow(p2.f, n + 1) - Math.pow(p1.f, n + 1)) / (n + 1);
}

/** PSD-Wert bei Frequenz f (log-log interpoliert), 0 außerhalb. */
export function psdAt(profile, f) {
    for (let i = 0; i < profile.length - 1; i++) {
        const a = profile[i], b = profile[i + 1];
        if (f >= a.f && f <= b.f) {
            const n = Math.log(b.psd / a.psd) / Math.log(b.f / a.f);
            return a.psd * Math.pow(f / a.f, n);
        }
    }
    return 0;
}

/**
 * Kennwerte eines Beschleunigungs-PSD in g²/Hz.
 * grms in g; vrms in m/s; drms in m (Effektivwerte); dpp3s = 3σ Spitze-Spitze-Weg.
 */
export function randomStats(profile) {
    let area = 0;
    for (let i = 0; i < profile.length - 1; i++) area += segmentArea(profile[i], profile[i + 1]);
    const grms = Math.sqrt(area);

    // Geschwindigkeit/Weg: numerisch über logarithmisch verteilte Stützstellen (Trapez in ln f).
    const f0 = profile[0].f, f1 = profile[profile.length - 1].f;
    const N = 20000;
    const lnA = Math.log(f0), lnB = Math.log(f1), h = (lnB - lnA) / N;
    let vArea = 0, dArea = 0;
    for (let i = 0; i <= N; i++) {
        const f = Math.exp(lnA + i * h);
        const p = psdAt(profile, f) * G * G; // (m/s²)²/Hz
        const w = TWO_PI * f;
        const wgt = (i === 0 || i === N) ? 0.5 : 1;
        vArea += wgt * (p / (w * w)) * f * h;      // df = f · d(ln f)
        dArea += wgt * (p / (w * w * w * w)) * f * h;
    }
    const vrms = Math.sqrt(vArea);
    const drms = Math.sqrt(dArea);
    return { grms, vrms, drms, dpp3s: 6 * drms, area };
}

export const PSD_PRESETS = {
    navmat: {
        label: 'NAVMAT P-9492 (Screening)',
        rows: [{ f: 20, psd: 0.01 }, { f: 80, slope: 3 }, { f: 350, psd: 0.04 }, { f: 2000, slope: -3 }],
    },
    gevs: {
        label: 'NASA GEVS – Qualifikation (Bauteile ≤ 22,7 kg)',
        rows: [{ f: 20, psd: 0.026 }, { f: 50, slope: 6 }, { f: 800, psd: 0.16 }, { f: 2000, slope: -6 }],
    },
    flat: {
        label: 'Flaches Profil 20–2000 Hz, 0,01 g²/Hz',
        rows: [{ f: 20, psd: 0.01 }, { f: 2000, psd: 0.01 }],
    },
};

/* ------------------------------------------------------------------ Schock */

export const SHOCK_SHAPES = {
    halfsine: 'Halbsinus',
    sawtooth: 'Sägezahn (Endspitze)',
    trapezoid: 'Trapez (10 % Flanken)',
};

/** Normierte Pulsform a(t)/A für t in [0, 1]. */
export function shockShape(shape, u) {
    if (u < 0 || u > 1) return 0;
    if (shape === 'halfsine') return Math.sin(Math.PI * u);
    if (shape === 'sawtooth') return u;
    // Trapez: 10 % Anstieg, 80 % Plateau, 10 % Abfall
    if (u < 0.1) return u / 0.1;
    if (u > 0.9) return (1 - u) / 0.1;
    return 1;
}

const SHAPE_AREA = { halfsine: 2 / Math.PI, sawtooth: 0.5, trapezoid: 0.9 };

/**
 * Schockkennwerte. peakG in g, durationMs in ms.
 * dv in m/s, dropHeight in m (ohne Rückprall), disp in m (Weg während des Pulses aus der Ruhe).
 */
export function shock(shape, peakG, durationMs) {
    const A = peakG * G;
    const D = durationMs / 1000;
    const dv = A * D * SHAPE_AREA[shape];
    // Weg während des Pulses: doppelte Integration numerisch
    const N = 2000;
    let v = 0, x = 0;
    const dt = D / N;
    for (let i = 0; i < N; i++) {
        const aMid = A * shockShape(shape, (i + 0.5) / N);
        x += v * dt + 0.5 * aMid * dt * dt;
        v += aMid * dt;
    }
    return { dv, dropHeight: (dv * dv) / (2 * G), disp: x };
}

/** Benötigter Halbsinus-Spitzenwert (g) für Fallhöhe h (m), Stoßdauer (ms), Rückprallfaktor e (0…1). */
export function halfSineFromDrop(h, durationMs, e = 0) {
    const dv = (1 + e) * Math.sqrt(2 * G * h);
    const D = durationMs / 1000;
    return { dv, peakG: (Math.PI * dv) / (2 * D) / G };
}

/* ------------------------------------------------------- Einmassenschwinger */

/** Eigenfrequenz aus Masse (kg) und Steifigkeit (N/m). */
export function naturalFrequency(m, k) {
    return Math.sqrt(k / m) / TWO_PI;
}

/** Eigenfrequenz aus statischer Einfederung δ (m). */
export function naturalFrequencyFromDeflection(delta) {
    return Math.sqrt(G / delta) / TWO_PI;
}

/** Steifigkeit (N/m) für Ziel-Eigenfrequenz f0 bei Masse m. */
export function stiffnessFor(m, f0) {
    return m * Math.pow(TWO_PI * f0, 2);
}

/* ---------------------------------------------------------- Isolierung */

/** Übertragungsfaktor (Fußpunkt- bzw. Kraftanregung) eines gedämpften Einmassenschwingers. */
export function transmissibility(r, zeta) {
    const num = 1 + Math.pow(2 * zeta * r, 2);
    const den = Math.pow(1 - r * r, 2) + Math.pow(2 * zeta * r, 2);
    return Math.sqrt(num / den);
}

export function isolation(fExc, f0, zeta) {
    const r = fExc / f0;
    const T = transmissibility(r, zeta);
    return {
        r,
        T,
        efficiency: (1 - T) * 100,     // % (negativ = Verstärkung)
        isolating: r > Math.SQRT2,
        q: 1 / (2 * zeta),
        dB: 20 * Math.log10(T),
    };
}

/* ---------------------------------------------------- Schwingstärke */

// Orientierungswerte (mm/s, Effektivwert) nach ISO 10816-1, Anhang B (informativ).
export const SEVERITY_CLASSES = {
    I: { label: 'Klasse I – kleine Maschinen bis 15 kW', limits: [0.71, 1.8, 4.5] },
    II: { label: 'Klasse II – mittlere Maschinen 15–75 kW', limits: [1.12, 2.8, 7.1] },
    III: { label: 'Klasse III – große Maschinen, starres Fundament', limits: [1.8, 4.5, 11.2] },
    IV: { label: 'Klasse IV – große Maschinen, weiches Fundament', limits: [2.8, 7.1, 18] },
};

export const SEVERITY_ZONES = {
    A: 'Neu in Betrieb genommene Maschinen',
    B: 'Uneingeschränkter Langzeitbetrieb zulässig',
    C: 'Nicht für Dauerbetrieb geeignet – Abhilfe planen',
    D: 'Schwingungen können Schäden verursachen',
};

export function severityZone(vrmsMmS, cls) {
    const [ab, bc, cd] = SEVERITY_CLASSES[cls].limits;
    if (vrmsMmS <= ab) return 'A';
    if (vrmsMmS <= bc) return 'B';
    if (vrmsMmS <= cd) return 'C';
    return 'D';
}

/* ------------------------------------------------------ Resonanz-Demo */

/**
 * Normierter Amplitudengang |X1/(F/k1)| eines Hauptsystems (Eigenfrequenz f1, Dämpfung z1)
 * mit optionalem Tilger (Massenverhältnis mu, Abstimmung fa, Dämpfung za).
 */
export function frf(f, f1, z1, absorber = null) {
    const w = TWO_PI * f, w1 = TWO_PI * f1;
    // m1 = 1, k1 = w1², c1 = 2 z1 w1
    const k1 = w1 * w1, c1 = 2 * z1 * w1;
    if (!absorber) {
        const re = k1 - w * w, im = c1 * w;
        return k1 / Math.hypot(re, im);
    }
    const { mu, fa, za } = absorber;
    const m2 = mu, wa = TWO_PI * fa;
    const k2 = m2 * wa * wa, c2 = 2 * za * m2 * wa;
    // Komplexe Rechnung ohne Bibliothek
    const aRe = k1 + k2 - w * w, aIm = (c1 + c2) * w;     // Hauptdiagonale 1
    const bRe = k2 - m2 * w * w, bIm = c2 * w;            // Hauptdiagonale 2
    const cRe = k2, cIm = c2 * w;                          // Kopplung
    // det = a·b − c²
    const detRe = aRe * bRe - aIm * bIm - (cRe * cRe - cIm * cIm);
    const detIm = aRe * bIm + aIm * bRe - 2 * cRe * cIm;
    // X1 = b / det · F ;  |X1| · k1
    return k1 * Math.hypot(bRe, bIm) / Math.hypot(detRe, detIm);
}

/** Optimale Tilgerabstimmung nach Den Hartog. */
export function denHartog(f1, mu) {
    return {
        fa: f1 / (1 + mu),
        za: Math.sqrt((3 * mu) / (8 * Math.pow(1 + mu, 3))),
    };
}

/* ------------------------------------------------------ Formatierung */

export function fmt(value, digits = 3) {
    if (!Number.isFinite(value)) return '–';
    const abs = Math.abs(value);
    if (abs > 0 && abs < 1) {
        return value.toLocaleString('de-DE', { maximumSignificantDigits: digits });
    }
    let d = digits;
    if (abs >= 1000) d = 0;
    else if (abs >= 100) d = Math.max(0, digits - 2);
    else if (abs >= 10) d = Math.max(1, digits - 1);
    return value.toLocaleString('de-DE', { minimumFractionDigits: 0, maximumFractionDigits: d });
}

/** Zahl aus deutscher oder englischer Schreibweise lesen ("1,5" oder "1.5"). */
export function num(input) {
    if (typeof input === 'number') return input;
    const s = String(input ?? '').trim().replace(/\s/g, '').replace(',', '.');
    if (s === '') return NaN;
    return Number(s);
}
