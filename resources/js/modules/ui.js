// Allgemeine Oberflächen-Logik: Kopfzeile, Mobilmenü, Normenfilter, Lichtkegel.

export function initHeader() {
    const header = document.querySelector('[data-header]');
    if (!header) return;
    const onScroll = () => header.classList.toggle('is-scrolled', window.scrollY > 12);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });

    const toggle = header.querySelector('[data-nav-toggle]');
    const nav = document.querySelector('[data-nav]');
    if (!toggle || !nav) return;

    // Passen die (im Admin pflegbaren) Menüpunkte nicht in eine Zeile, wird auch auf
    // breiten Bildschirmen das aufklappbare Menü verwendet.
    const inner = header.querySelector('.site-header__inner');
    const brand = header.querySelector('.brand');
    let measuring = false;
    const fit = () => {
        if (measuring) return;
        measuring = true;
        delete header.dataset.compact;
        if (window.innerWidth > 1140) {
            const needed = brand.offsetWidth + 32 + nav.scrollWidth;
            if (needed > inner.clientWidth) header.dataset.compact = '';
        }
        measuring = false;
    };
    fit();
    window.addEventListener('resize', () => requestAnimationFrame(fit));
    document.fonts?.ready.then(fit);
    const set = (open) => {
        toggle.setAttribute('aria-expanded', String(open));
        nav.dataset.open = String(open);
        document.documentElement.classList.toggle('nav-open', open);
    };
    toggle.addEventListener('click', () => set(toggle.getAttribute('aria-expanded') !== 'true'));
    nav.addEventListener('click', (e) => e.target.closest('a') && set(false));
    document.addEventListener('keydown', (e) => e.key === 'Escape' && set(false));
}

export function initSpotlight() {
    document.querySelectorAll('[data-spotlight]').forEach((el) => {
        el.addEventListener('pointermove', (e) => {
            const r = el.getBoundingClientRect();
            el.style.setProperty('--mx', `${e.clientX - r.left}px`);
            el.style.setProperty('--my', `${e.clientY - r.top}px`);
        });
    });
}

export function initNormFilter() {
    const root = document.querySelector('[data-norm-filter]');
    if (!root) return;
    const buttons = [...root.querySelectorAll('[data-filter]')];
    const search = root.querySelector('[data-norm-search]');
    const items = [...document.querySelectorAll('[data-norm]')];
    const empty = document.querySelector('[data-norm-empty]');
    const count = document.querySelector('[data-norm-count]');
    let cat = new URLSearchParams(location.search).get('kategorie') || 'all';

    const apply = () => {
        const q = (search?.value || '').trim().toLowerCase();
        let shown = 0;
        items.forEach((el) => {
            const match = (cat === 'all' || el.dataset.category === cat) && (!q || el.dataset.text.includes(q));
            el.hidden = !match;
            if (match) shown++;
        });
        buttons.forEach((b) => b.setAttribute('aria-pressed', String(b.dataset.filter === cat)));
        if (empty) empty.hidden = shown > 0;
        if (count) count.textContent = String(shown);
    };

    buttons.forEach((b) =>
        b.addEventListener('click', () => {
            cat = b.dataset.filter;
            const url = new URL(location.href);
            cat === 'all' ? url.searchParams.delete('kategorie') : url.searchParams.set('kategorie', cat);
            history.replaceState(null, '', url);
            apply();
        }),
    );
    search?.addEventListener('input', apply);
    apply();
}
