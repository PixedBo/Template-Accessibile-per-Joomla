/**
 * Scroll to top — focus management accessibile.
 * Il componente BackToTop di Bootstrap Italia gestisce show/hide e animazione.
 * Questo script sposta il focus sull'ancora #top al termine dello scroll (WCAG).
 */
(() => {
    'use strict';
    const btn = document.querySelector('[data-bs-toggle="backtotop"]');
    const top = document.getElementById('top');
    if (!btn || !top) return;
    btn.addEventListener('click', () => setTimeout(() => top.focus(), 800));
})();
