// Einblenden beim Scrollen: Blöcke unterhalb des sichtbaren Bereichs blenden
// sanft ein, sobald sie ins Bild kommen. Was beim Laden schon sichtbar ist,
// bleibt unverändert; bei "Bewegung reduzieren" passiert nichts.

const SELECTOR = [
    '.section-head', '.test-row', '.svc-group', '.tool-list > li', '.cards > *', '.steps > *',
    '.panel', '.instrument', '.date-row', '.norm-index', '.cta', '.app-promo', '.app-callout',
    '.sim-teaser', '.reso', '.spec', '.account-grid > *', '.prose > *', '[data-reveal]',
].join(',');

export function initReveal() {
    if (!('IntersectionObserver' in window) || matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const found = [...document.querySelectorAll(SELECTOR)].filter((el) => !el.closest('[data-sim], [data-consent], .site-header, .site-footer'));
    // Verschachtelte Treffer nur einmal animieren (das äußere Element)
    const set = new Set(found);
    const els = found.filter((el) => {
        for (let p = el.parentElement; p; p = p.parentElement) if (set.has(p)) return false;
        return true;
    });

    const io = new IntersectionObserver(
        (entries) => {
            let n = 0;
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                const el = entry.target;
                io.unobserve(el);
                el.style.transitionDelay = `${Math.min(n++, 5) * 70}ms`;
                el.classList.add('is-in');
                const done = (e) => {
                    if (e.target !== el || e.propertyName !== 'opacity') return;
                    el.classList.remove('reveal', 'is-in');
                    el.style.transitionDelay = '';
                    el.removeEventListener('transitionend', done);
                };
                el.addEventListener('transitionend', done);
            });
        },
        { rootMargin: '0px 0px -6% 0px', threshold: 0.06 },
    );

    const limit = window.innerHeight * 0.94;
    els.forEach((el) => {
        const r = el.getBoundingClientRect();
        // schon sichtbar, unsichtbar (display: none) oder sehr hoch → nicht animieren
        if (r.top < limit || r.height === 0 || r.height > window.innerHeight * 3) return;
        el.classList.add('reveal');
        io.observe(el);
    });
}
