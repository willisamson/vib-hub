// Kleine Sprachhilfe für die JavaScript-Teile (Deutsch / Englisch).
export const lang = (document.documentElement.lang || 'de').toLowerCase().startsWith('en') ? 'en' : 'de';
export const locale = lang === 'en' ? 'en-US' : 'de-DE';

/** t('Deutscher Text', 'English text') */
export function t(de, en) {
    return lang === 'en' ? en : de;
}
