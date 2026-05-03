# Layout Categoria Servizi

Il layout `servizi` è un override del layout blog per le categorie, pensato per le categorie che raccolgono i servizi del comune (criterio C.SI.1.3 del Modello Comuni).

---

## Come attivare il layout

Il layout si seleziona sulla **voce di menu** che punta alla categoria Servizi:

1. Crea o modifica una voce di menu di tipo **Articoli → Blog categoria**
2. Seleziona la categoria Servizi come categoria da visualizzare
3. Nella scheda **Layout** (o **Blog Layout**) → campo **Layout alternativo**: seleziona `servizi`
4. Salva

> TODO – inserire screenshot della scheda "Layout" della voce di menu con `servizi` selezionato

---

## Struttura della pagina

### Hero

In cima alla pagina viene visualizzato:
- Titolo della categoria
- Descrizione della categoria (se presente)
- Tag della categoria (se abilitati)

> TODO – inserire screenshot della hero della pagina Servizi

### Colonna principale (sinistra)

Contiene:
- **Posizione modulo `cerca-servizi`** – per un eventuale modulo di ricerca interna
- **Contatore** degli articoli totali nella categoria
- **Lista articoli** (servizi), ognuno con link `data-element="service-link"`
- **Paginazione** accessibile

> TODO – inserire screenshot della colonna principale con lista servizi e paginazione

### Colonna destra — Servizi in Evidenza

La sidebar destra mostra automaticamente i servizi marcati come **In Evidenza** (`featured = 1`) nella categoria corrente **e in tutte le sue sottocategorie**. I link hanno `data-element="service-link"`.

Per mettere un servizio in evidenza nella sidebar: apri l'articolo e abilita il flag **In Evidenza** (vedere [inserimento-articoli.md](inserimento-articoli.md)).

> TODO – inserire screenshot della sidebar con i servizi in evidenza

### Sezione sottocategorie — Esplora per categoria

Se la categoria ha sottocategorie, appare una sezione "Esplora per categoria" con i link alle sottocategorie. Ogni link ha `data-element="service-category-link"`.

---

## Dati tecnici (data-element)

| Elemento | data-element |
|----------|-------------|
| Link agli articoli/servizi | `service-link` |
| Link alle sottocategorie | `service-category-link` |

Questi attributi sono richiesti dall'App Valutazione Modelli per la verifica automatica.

---

## Note

- Il layout `servizi` può essere applicato sia alla categoria radice che alle sottocategorie.
- I servizi in evidenza nella sidebar vengono cercati nella categoria corrente **e in tutta la discendenza** (ricerca ricorsiva nel path della categoria).
- Il modulo nella posizione `cerca-servizi` è opzionale: se nessun modulo è pubblicato in quella posizione, lo spazio non viene mostrato.
