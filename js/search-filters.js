/**
 * Filtri della pagina risultati (html/com_finder/search/default_filtri.php).
 *
 * - La casella di una voce con sottovoci seleziona o deseleziona tutte le sottovoci.
 * - Quando cambiano le sottovoci, la casella della voce che le contiene si aggiorna:
 *   una voce senza contenuti propri è selezionata se lo sono tutte le sottovoci;
 *   se lo sono solo alcune, la casella è nello stato "misto" (proprietà indeterminate,
 *   che i lettori di schermo annunciano, più la classe semi-checked di Bootstrap Italia).
 * - Il pulsante accanto alla voce mostra o nasconde le sottovoci. All'avvio restano
 *   aperti solo i gruppi che contengono una casella selezionata.
 *
 * Senza questo script le sottovoci restano tutte visibili e ogni casella vale per sé.
 */
document.addEventListener('DOMContentLoaded', () => {
  const root = document.querySelector('.search-filters');

  if (!root) {
    return;
  }

  // Le sottovoci di una voce: le caselle dentro il gruppo che la segue
  const groupOf = (parent) => parent.closest('.search-filters-item').querySelector(':scope > .search-filters-children');
  const boxesIn = (group) => Array.from(group.querySelectorAll('input[type="checkbox"]'));

  // Una voce senza contenuti propri non ha "name": la sua casella riassume le sottovoci
  const isSummary = (box) => !box.name;

  const setMixed = (box, mixed) => {
    box.indeterminate = mixed;
    box.classList.toggle('semi-checked', mixed);
  };

  const refresh = (parent) => {
    const boxes = boxesIn(groupOf(parent)).filter((box) => !isSummary(box));
    const count = boxes.filter((box) => box.checked).length;

    if (isSummary(parent)) {
      parent.checked = boxes.length > 0 && count === boxes.length;
      setMixed(parent, count > 0 && count < boxes.length);
    } else {
      // La voce ha un valore proprio: resta com'è, lo stato misto segnala le sottovoci parziali
      setMixed(parent, !parent.checked && count > 0);
    }
  };

  // Dal gruppo più interno a quello più esterno
  const parents = Array.from(root.querySelectorAll('input[data-filter-parent]')).reverse();
  const refreshAll = () => parents.forEach(refresh);

  // Le caselle "seleziona tutte le sottovoci" prendono il posto del nome scritto come testo
  root.querySelectorAll('[data-filter-nojs]').forEach((el) => el.remove());
  root.querySelectorAll('[data-filter-js]').forEach((el) => {
    el.hidden = false;
    // Il gruppo delle sottovoci prende ora il nome dall'etichetta della casella
    groupOf(el).setAttribute('aria-labelledby', el.querySelector('label').id);
  });

  refreshAll();

  root.querySelectorAll('[data-filter-toggle]').forEach((button) => {
    const group = document.getElementById(button.getAttribute('aria-controls'));
    const open = boxesIn(group).some((box) => box.checked);

    button.hidden = false;
    button.setAttribute('aria-expanded', open ? 'true' : 'false');
    group.hidden = !open;

    button.addEventListener('click', () => {
      const expanded = button.getAttribute('aria-expanded') === 'true';

      button.setAttribute('aria-expanded', expanded ? 'false' : 'true');
      group.hidden = expanded;
    });
  });

  root.addEventListener('change', (event) => {
    const box = event.target;

    if (!box.matches('input[type="checkbox"]')) {
      return;
    }

    if (box.hasAttribute('data-filter-parent')) {
      boxesIn(groupOf(box)).forEach((child) => {
        child.checked = box.checked;
        setMixed(child, false);
      });
    }

    refreshAll();
  });
});
