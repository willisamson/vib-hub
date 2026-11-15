// Cookie-Einwilligung: Auswahl im Cookie "vibhub_consent" (180 Tage), Version aus
// dem Admin – eine höhere Version fragt alle Besucher erneut. Nicht notwendige
// Dienste (Statistik-Snippet, eingebettete Medien) werden erst nach Zustimmung geladen.

const NAME = 'vibhub_consent';
const MAX_AGE = 180 * 24 * 3600;
const CATS = ['stats', 'media'];

function read() {
    const raw = document.cookie.split('; ').find((c) => c.startsWith(`${NAME}=`));
    if (!raw) return null;
    try {
        return JSON.parse(decodeURIComponent(raw.slice(NAME.length + 1)));
    } catch {
        return null;
    }
}

function write(value) {
    const secure = location.protocol === 'https:' ? '; Secure' : '';
    document.cookie = `${NAME}=${encodeURIComponent(JSON.stringify(value))}; Max-Age=${MAX_AGE}; Path=/; SameSite=Lax${secure}`;
}

// Inhalt eines <template> einfügen; Skripte neu erzeugen, damit sie ausgeführt werden
function inject(tpl) {
    if (tpl.dataset.done) return;
    tpl.dataset.done = '1';
    const frag = tpl.content.cloneNode(true);
    frag.querySelectorAll('script').forEach((old) => {
        const s = document.createElement('script');
        [...old.attributes].forEach((a) => s.setAttribute(a.name, a.value));
        s.textContent = old.textContent;
        old.replaceWith(s);
    });
    document.body.appendChild(frag);
}

function loadEmbeds() {
    document.querySelectorAll('[data-consent-embed]').forEach((box) => {
        const frame = box.querySelector('iframe[data-consent-src]');
        if (frame && !frame.src) frame.src = frame.dataset.consentSrc;
        box.classList.add('is-loaded');
    });
}

export function initConsent() {
    const root = document.querySelector('[data-consent]');
    const version = Number(root?.dataset.version || 1);
    let current = read();
    if (current && current.v !== version) current = null;

    const apply = (c) => {
        if (!c) return;
        if (c.stats) document.querySelectorAll('template[data-consent-load="stats"]').forEach(inject);
        if (c.media) loadEmbeds();
        document.dispatchEvent(new CustomEvent('vibhub:consent', { detail: c }));
    };

    const save = (choice) => {
        const before = current;
        current = { v: version, necessary: true, ...Object.fromEntries(CATS.map((k) => [k, Boolean(choice[k])])), at: new Date().toISOString() };
        write(current);
        close();
        // Bereits geladene Dienste lassen sich nur durch Neuladen wieder entfernen
        if (before && CATS.some((k) => before[k] && !current[k])) location.reload();
        else apply(current);
    };

    // Platzhalter für eingebettete Medien: „Externe Medien erlauben“
    document.addEventListener('click', (e) => {
        if (!e.target.closest('[data-consent-embed-load]')) return;
        save({ ...(current || {}), media: true });
    });

    apply(current);
    if (!root) return;

    const prefs = root.querySelector('[data-consent-prefs]');
    const toggle = root.querySelector('[data-consent-toggle]');
    const btnSave = root.querySelector('[data-consent-save]');
    const boxes = [...root.querySelectorAll('[data-consent-cat]')];

    const showPrefs = (show) => {
        prefs.hidden = !show;
        btnSave.hidden = !show;
        toggle.hidden = show;
        toggle.setAttribute('aria-expanded', String(show));
    };

    const open = (withPrefs = false) => {
        boxes.forEach((b) => (b.checked = Boolean(current?.[b.dataset.consentCat])));
        showPrefs(withPrefs);
        root.hidden = false;
        requestAnimationFrame(() => root.classList.add('is-open'));
        if (withPrefs) (boxes[0] || btnSave).focus();
    };

    function close() {
        if (!root) return;
        root.classList.remove('is-open');
        root.hidden = true;
    }

    root.querySelector('[data-consent-all]').addEventListener('click', () => save({ stats: true, media: true }));
    root.querySelector('[data-consent-necessary]').addEventListener('click', () => save({}));
    toggle.addEventListener('click', () => showPrefs(true));
    btnSave.addEventListener('click', () => save(Object.fromEntries(boxes.map((b) => [b.dataset.consentCat, b.checked]))));

    document.querySelectorAll('[data-consent-open]').forEach((b) =>
        b.addEventListener('click', (e) => {
            e.preventDefault();
            open(true);
        }),
    );

    if (!current) open(false);
}
