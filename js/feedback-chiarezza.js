/**
 * Widget "Valutazione chiarezza pagina" — flusso step 0 → 1 → 2 → 3.
 * Requisito: C.SI.2.5 / C.SI.2.6 (Modello Comuni — Designers Italia).
 *
 * Nessun invio HTTP: il widget è DOM-only per la verifica dell'App Valutazione.
 * Eventuale persistenza (fetch verso endpoint) è TODO e va aggiunta qui.
 */
(() => {
    'use strict';

    const initWidget = (root) => {
        const cardFirst  = root.querySelector('.cmp-rating__card-first');
        const cardFinal  = root.querySelector('.cmp-rating__card-second');
        const formRating = root.querySelector('.form-rating');
        const step1      = root.querySelector('[data-step="1"].rating-shadow');
        const step2      = root.querySelector('[data-step="2"]:not(.rating-shadow)');
        const fieldPos   = root.querySelector('.fieldset-rating-one');
        const fieldNeg   = root.querySelector('.fieldset-rating-two');
        const btnNext    = root.querySelector('.btn-next');
        const btnBack    = root.querySelector('.btn-back');
        const stars      = root.querySelectorAll('input.rating-star');

        if (!cardFirst || !formRating || !step1 || !step2 || !btnNext || !btnBack) {
            return;
        }

        let currentStep = 0;
        let selectedRating = 0;

        const show = (el) => el && el.classList.remove('d-none');
        const hide = (el) => el && el.classList.add('d-none');

        const goToStep = (step) => {
            currentStep = step;

            if (step === 1) {
                hide(cardFinal);
                show(formRating);
                show(step1);
                hide(step2);
                if (selectedRating >= 4) {
                    show(fieldPos);
                    hide(fieldNeg);
                } else {
                    show(fieldNeg);
                    hide(fieldPos);
                }
                btnBack.disabled = true;
            } else if (step === 2) {
                hide(step1);
                show(step2);
                btnBack.disabled = false;
            } else if (step === 3) {
                hide(cardFirst);
                hide(formRating);
                show(cardFinal);
                cardFinal.setAttribute('tabindex', '-1');
                cardFinal.focus({ preventScroll: true });
            }
        };

        const getActiveFieldset = () => (selectedRating >= 4 ? fieldPos : fieldNeg);

        const isStep1Valid = () => {
            const fs = getActiveFieldset();
            if (!fs) return false;
            return !!fs.querySelector('input[type="radio"]:checked');
        };

        stars.forEach((input) => {
            input.addEventListener('change', () => {
                const val = parseInt(input.value, 10);
                if (Number.isNaN(val)) return;
                selectedRating = val;
                goToStep(1);
            });
        });

        btnNext.addEventListener('click', () => {
            if (currentStep === 1) {
                if (!isStep1Valid()) {
                    const fs = getActiveFieldset();
                    const firstRadio = fs && fs.querySelector('input[type="radio"]');
                    if (firstRadio) firstRadio.focus();
                    return;
                }
                goToStep(2);
            } else if (currentStep === 2) {
                goToStep(3);
            }
        });

        btnBack.addEventListener('click', () => {
            if (currentStep === 2) {
                goToStep(1);
            }
        });
    };

    const init = () => {
        document.querySelectorAll('[data-feedback-widget]').forEach(initWidget);
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
