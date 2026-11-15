import { initHeroScope, initMiniScopes } from './modules/scope.js';
import { initIsolationDemo, initResonanceDemo } from './modules/resonance.js';
import { initCalculators } from './modules/calculators.js';
import { initHeader, initNormFilter, initSpotlight } from './modules/ui.js';

const ready = (fn) => (document.readyState === 'loading' ? document.addEventListener('DOMContentLoaded', fn) : fn());

ready(() => {
    document.documentElement.classList.add('js');
    initHeader();
    initSpotlight();
    initNormFilter();

    const hero = document.querySelector('[data-hero-scope]');
    if (hero) initHeroScope(hero);
    initMiniScopes();

    document.querySelectorAll('[data-resonance-demo]').forEach(initResonanceDemo);
    document.querySelectorAll('[data-isolation-demo]').forEach(initIsolationDemo);
    initCalculators();
});
