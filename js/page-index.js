/**
 * Indice di pagina (data-element="page-index") dentro un NavScroll di Bootstrap Italia.
 *
 * NavScroll calcola la destinazione del click con target.offsetTop, che è relativo al primo
 * antenato posizionato e non al documento: nel template il componente sta dentro un wrapper
 * position-relative, quindi lo scroll si ferma nel punto sbagliato.
 * Qui si gestisce solo il click sulle voci; evidenziazione della voce attiva e barra di
 * avanzamento restano a NavScroll.
 */
(() => {
    'use strict';

    const lists = document.querySelectorAll(
        '.it-navscroll-wrapper[data-bs-navscroll] [data-element="page-index"]'
    );

    if (!lists.length) return;

    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

    const onClick = (e) => {
        const link = e.target.closest('a[href^="#"]');
        if (!link) return;

        const hash = link.getAttribute('href');
        if (hash.length < 2) return;

        const target = document.getElementById(decodeURIComponent(hash.substring(1)));
        if (!target) return;

        // In fase di capture sull'antenato: il click non arriva all'handler di NavScroll.
        e.preventDefault();
        e.stopPropagation();

        target.scrollIntoView({
            behavior: reducedMotion.matches ? 'auto' : 'smooth',
            block: 'start'
        });

        history.pushState(null, '', hash);

        // Sposta il focus sulla sezione, per tastiera e screen reader.
        if (!target.hasAttribute('tabindex')) {
            target.setAttribute('tabindex', '-1');
            target.addEventListener('blur', () => target.removeAttribute('tabindex'), { once: true });
        }
        target.focus({ preventScroll: true });
    };

    lists.forEach(list => list.addEventListener('click', onClick, true));
})();
