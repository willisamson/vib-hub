import { initHeroScope, initMiniScopes } from './modules/scope.js';
import { initIsolationDemo, initResonanceDemo } from './modules/resonance.js';
import { initCalculators } from './modules/calculators.js';
import { initHeader, initNormFilter, initSpotlight } from './modules/ui.js';
import { initSimulator } from './modules/sim.js';
import { initConsent } from './modules/consent.js';
import { initReveal } from './modules/reveal.js';
import { setNumberLocale } from './modules/vibmath.js';
import { locale } from './modules/i18n.js';

setNumberLocale(locale);

const ready = (fn) => (document.readyState === 'loading' ? document.addEventListener('DOMContentLoaded', fn) : fn());

ready(() => {
    document.documentElement.classList.add('js');
    initConsent();
    initHeader();
    initSpotlight();
    initNormFilter();

    const hero = document.querySelector('[data-hero-scope]');
    if (hero) initHeroScope(hero);
    initMiniScopes();

    document.querySelectorAll('[data-resonance-demo]').forEach(initResonanceDemo);
    document.querySelectorAll('[data-isolation-demo]').forEach(initIsolationDemo);
    initCalculators();
    document.querySelectorAll('[data-sim]').forEach(initSimulator);
    initReveal();
});
