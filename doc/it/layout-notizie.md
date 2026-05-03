# Layout Categoria Notizie

Il layout `notizie` è un override del layout blog per le categorie, pensato per la sezione notizie/comunicati stampa del comune.

---

## Come attivare il layout

Il layout si seleziona sulla **voce di menu** che punta alla categoria Notizie:

1. Crea o modifica una voce di menu di tipo **Articoli → Blog categoria**
2. Seleziona la categoria Notizie
3. Nella scheda **Layout** (o **Blog Layout**) → campo **Layout alternativo**: seleziona `notizie`
4. Salva

> TODO – inserire screenshot della scheda "Layout" della voce di menu con `notizie` selezionato

---

## Struttura della pagina

### Hero

In cima viene visualizzato:
- Titolo della categoria
- Descrizione della categoria (se presente)
- Tag della categoria (se abilitati)

### Sezione "In Evidenza" (solo pagina 1)

La prima pagina del layout mostra una sezione "In Evidenza" con le notizie marcate come **In Evidenza** (`featured = 1`), ordinate dalla più recente.

Questa sezione è configurabile:

| Parametro | Descrizione | Default |
|-----------|-------------|---------|
| Mostra notizie in evidenza | Abilita/disabilita la sezione | Sì |
| Numero notizie in evidenza | Quante notizie mostrare (1–12) | 3 |

**Dove si configurano questi parametri:**

Nella voce di menu che usa il layout `notizie` → scheda **Opzioni** o **Layout** → cerca i parametri specifici del layout notizie.

> TODO – inserire screenshot della scheda con i parametri "Mostra notizie in evidenza" e "Numero notizie in evidenza"

### Sezione "Esplora tutte le novità"

Lista di tutti gli articoli della categoria con paginazione. La sezione è sempre visibile, indipendentemente dai parametri.

### Sezione "Esplora per categoria" (sottocategorie)

Se la categoria ha sottocategorie e i livelli sono configurati, appare una sezione con i link alle sottocategorie.

---

## Come marcare una notizia come "In Evidenza"

- Dalla lista articoli: clicca la stella nella colonna **In Evidenza**
- Dall'editor articolo: scheda **Pubblicazione** → campo **In Evidenza** = Sì

> TODO – inserire screenshot della lista articoli con la colonna "In Evidenza"

---

## Note

- La sezione "In Evidenza" appare solo sulla **prima pagina** del layout (non sulle pagine successive della paginazione).
- Le notizie in evidenza sono ordinate per data di pubblicazione decrescente (le più recenti prima).
- Il layout non genera `data-element` specifici per le notizie (a differenza del layout Servizi): non è richiesta dalla specifica Modello Comuni.
