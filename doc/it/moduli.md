# Posizioni modulo

Il template definisce le seguenti posizioni modulo. Per assegnare un modulo a una posizione, usare il campo **Posizione** nella scheda **Avanzate** del modulo.

> TODO – inserire screenshot del Gestore Moduli con la lista delle posizioni visibile nel filtro

---

## Mappa delle posizioni

### Nella topbar
| Posizione | Descrizione |
|-----------|-------------|
| `selezione-lingua` | Area destra della banda slim superiore. Usata tipicamente per `mod_languages` su siti multilingua. |

### Nell'header (navbar)
| Posizione | Descrizione |
|-----------|-------------|
| `menu-principale` | Menu di navigazione principale. **Obbligatorio il layout `comuni-menu`**. |
| `menu-secondario` | Menu di navigazione secondario. **Obbligatorio il layout `comuni-menu`**. |

### Subito dopo la navbar
| Posizione | Descrizione |
|-----------|-------------|
| `percorso` | Breadcrumb (percorso di navigazione). Usare `mod_breadcrumbs` con il layout override incluso nel template. |

### Area contenuto principale
| Posizione | Descrizione |
|-----------|-------------|
| `top` | Sezione sopra il contenuto principale, sfondo bianco. |
| `top-muted` | Sezione sopra il contenuto principale, sfondo grigio chiaro. |
| `evidenza` | Sezione hero/evidenza con sfondo dinamico (immagine che cambia in base al colore primario). |
| `calendario` | Area per eventi o calendari, posizionata dopo `evidenza`. |
| `colonna-sinistra` | Sidebar sinistra del contenuto principale (affiancata all'area `<main>`). |
| `colonna-destra` | Sidebar destra del contenuto principale (affiancata all'area `<main>`). |
| `bottom` | Sezione dopo il contenuto principale. |
| `bottom2` | Seconda sezione dopo il contenuto principale. |

### Nel footer
| Posizione | Descrizione |
|-----------|-------------|
| `footer1` | Prima colonna del footer. |
| `footer2` | Seconda colonna del footer. |

### Nei layout di categoria Servizi
| Posizione | Descrizione |
|-----------|-------------|
| `cerca-servizi` | Area ricerca integrata nel layout categoria Servizi, posizionata sopra la lista degli articoli. |

---

## Layout alternativi per i moduli articoli (`mod_articles`)

Il template include due layout alternativi per il modulo **Articoli Correlati** (`mod_articles`):

### `evidenza-singolo`

Mostra un singolo articolo in evidenza. Se il modulo ha più articoli configurati, genera automaticamente un **carosello accessibile** con navigazione a frecce e indicatori.

**Uso tipico:** assegnato alla posizione `evidenza` come slideshow di notizie o servizi in primo piano.

> TODO – inserire screenshot del layout `evidenza-singolo` con un singolo articolo e con il carosello attivo su più articoli

### `evidenza-tre-colonne`

Mostra fino a 3 articoli affiancati in una griglia a 3 colonne (si adatta su mobile).

**Uso tipico:** assegnato a `top`, `bottom` o `evidenza` per presentare le sezioni principali del sito.

> TODO – inserire screenshot del layout `evidenza-tre-colonne` con 3 articoli

### Come assegnare un layout alternativo a un modulo

1. Modifica il modulo `mod_articles`
2. Nella scheda **Avanzate** → **Layout alternativo**: seleziona `evidenza-singolo` oppure `evidenza-tre-colonne`

> TODO – inserire screenshot della scheda Avanzate del modulo con il campo Layout alternativo evidenziato

---

## Layout alternativo per il breadcrumb (`mod_breadcrumbs`)

Il template include un override del modulo breadcrumb che applica la struttura Bootstrap Italia. Non è necessario selezionare manualmente un layout alternativo: l'override è applicato automaticamente.

Assegna il modulo `mod_breadcrumbs` alla posizione `percorso` e verrà usato l'override incluso nel template.
