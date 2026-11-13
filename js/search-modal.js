/**
 * Modale di ricerca (html/layouts/accessibile/ricerca-modale.php).
 *
 * Apertura, chiusura, tasto Esc e blocco del focus sono del componente modal
 * di Bootstrap Italia. Qui si porta soltanto il focus sul campo di ricerca
 * quando il modale ha finito di aprirsi.
 */
document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('search-modal');

  if (!modal) {
    return;
  }

  modal.addEventListener('shown.bs.modal', () => {
    const input = modal.querySelector('input[name="q"]');

    if (input) {
      input.focus();
    }
  });
});
