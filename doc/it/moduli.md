# Posizioni modulo

Il template definisce una serie di posizioni fisse dove puoi inserire i moduli Joomla. Ogni posizione corrisponde a un'area specifica della pagina.

Per assegnare un modulo a una posizione, apri il modulo in modifica e vai nella scheda **Avanzate** → campo **Posizione modulo**.

> *Screenshot — Backend di un modulo, scheda "Avanzate", con il campo "Posizione modulo" evidenziato e il valore "top" selezionato*

---

## Mappa delle posizioni

### Topbar (banda slim superiore)

| Posizione | Area nella pagina | Uso tipico |
|-----------|-------------------|------------|
| `selezione-lingua` | Destra della banda slim | Modulo cambio lingua (`mod_languages`) per siti multilingua |

### Header — Navbar

| Posizione | Area nella pagina | Uso tipico |
|-----------|-------------------|------------|
| `menu-principale` | Navbar a sinistra | Menu di navigazione principale — **obbligatorio il layout `comuni-menu`** |
| `menu-secondario` | Navbar a destra | Menu di navigazione secondario — **obbligatorio il layout `comuni-menu`** |

### Avvisi

| Posizione | Area nella pagina | Uso tipico |
|-----------|-------------------|------------|
| `avvisi` | Subito sotto l'intestazione, a tutta larghezza, prima del breadcrumb | Banner di comunicazione: manutenzioni, disservizi, allerte |

Colori e uso di più banner sono descritti in [avvisi.md](avvisi.md).

### Breadcrumb (percorso di navigazione)

| Posizione | Area nella pagina | Uso tipico |
|-----------|-------------------|------------|
| `percorso` | Sotto la navbar, sopra il contenuto | Modulo breadcrumb (`mod_breadcrumbs`) |

**Nota sul breadcrumb:** il template include un override del modulo `mod_breadcrumbs` che applica automaticamente la struttura Bootstrap Italia. Basta assegnare il modulo alla posizione `percorso` — non è necessario selezionare un layout alternativo.

### Area contenuto principale

Queste posizioni si trovano attorno all'area principale della pagina (la parte che cambia in base alla voce di menu).

| Posizione | Area nella pagina | Sfondo | Uso tipico |
|-----------|-------------------|--------|------------|
| `evidenza` | Sopra il contenuto, sfondo colorato | Immagine + colore primario | Hero/carosello con articoli in evidenza |
| `top` | Sotto `evidenza`, sopra il contenuto | Bianco | Contenuto in evidenza, link rapidi |
| `top-muted` | Sotto `top`, sopra il contenuto | Grigio chiaro | Sezioni di supporto |
| `calendario` | Sotto `evidenza` | Bianco | Moduli calendario o eventi |
| `colonna-sinistra` | Sidebar sinistra del contenuto | — | Indice della pagina, filtri |
| `colonna-destra` | Sidebar destra del contenuto | — | Widget correlati, link rapidi |
| `bottom` | Sotto il contenuto principale | Blu scuro | Call to action, link istituzionali |
| `bottom2` | Sotto `bottom` | Grigio chiaro | Contenuto supplementare |

### Footer

| Posizione | Area nella pagina | Uso tipico |
|-----------|-------------------|------------|
| `footer1` | Prima colonna del footer (sinistra) | Logo e testo istituzionale, menu |
| `footer2` | Seconda colonna del footer (destra) | Link rapidi, contatti, link istituzionali |

### Layout categoria Servizi (solo Modello Comuni)

| Posizione | Area nella pagina | Uso tipico |
|-----------|-------------------|------------|
| `cerca-servizi` | Sopra la lista servizi | Modulo Smart Search filtrato alla categoria Servizi |

Questa posizione appare solo all'interno del layout categoria `servizi`. Se nessun modulo è pubblicato in `cerca-servizi`, l'area non viene mostrata.

### Debug

| Posizione | Area nella pagina | Uso tipico |
|-----------|-------------------|------------|
| `debug` | In fondo alla pagina, dopo il footer | Riservata all'output di debug di Joomla; non usarla per i contenuti |

---

## Layout alternativi per il modulo Articoli (`mod_articles`)

Il template include due layout grafici alternativi per il modulo **mod_articles** (che si chiama "Articoli" nella lista moduli di Joomla). Questi layout permettono di mostrare articoli in evidenza con formati diversi.

### Come assegnare un layout alternativo

1. Apri il modulo `mod_articles` in modifica
2. Vai nella scheda **Avanzate**
3. Nel campo **Layout alternativo**, seleziona il layout desiderato

> *Screenshot — Scheda "Avanzate" del modulo Articoli, con il campo "Layout alternativo" espanso e i layout del template visibili nell'elenco*

### Layout: `evidenza-singolo`

**Cosa fa:** mostra un singolo articolo in evidenza, in formato "hero" con immagine grande, titolo e testo introduttivo.

**Comportamento speciale:** se il modulo ha configurato più di un articolo, genera automaticamente un **carosello accessibile** con frecce di navigazione e indicatori circolari. Il carosello scorre automaticamente ogni pochi secondi ed è navigabile da tastiera.

**Uso tipico:** assegnato alla posizione `evidenza` come slideshow degli articoli principali del sito (notizie in primo piano, iniziative speciali, ecc.).

> *Screenshot — Posizione "evidenza" con il layout `evidenza-singolo` attivo: un singolo articolo grande, poi lo stesso con carosello su più articoli*

### Layout: `evidenza-tre-colonne`

**Cosa fa:** mostra fino a 3 articoli affiancati in una griglia a 3 colonne. Su tablet si riduce a 2 colonne, su mobile a 1 colonna.

**Uso tipico:** assegnato a `top`, `bottom` o `evidenza` per presentare le sezioni principali del sito o le ultime notizie in una griglia ordinata.

> *Screenshot — Layout `evidenza-tre-colonne` con 3 card articolo affiancate*

---

## Layout alternativo per il breadcrumb (`mod_breadcrumbs`)

Il template include un override automatico per il modulo `mod_breadcrumbs`. Questo override applica la struttura HTML e le classi CSS di Bootstrap Italia al breadcrumb.

**Non è necessario** selezionare manualmente nessun layout alternativo. Basta:
1. Creare un modulo di tipo **Percorso di navigazione** (`mod_breadcrumbs`)
2. Assegnarlo alla posizione `percorso`
3. Pubblicarlo

L'override verrà applicato automaticamente.
