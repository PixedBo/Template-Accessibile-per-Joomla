# Header

L'header è composto da tre aree distinte, dall'alto verso il basso:

1. **Topbar** – banda slim con nome regione e cambio lingua (vedere [topbar.md](topbar.md))
2. **Header center** – logo, nome comune, payoff, icone social, login, ricerca
3. **Navbar** – barra di navigazione con menu principale e menu secondario

---

## Header center

> TODO – inserire screenshot dell'header center con le aree numerate

### Logo

Configura il logo tramite il campo `logotipo` (scheda Generale del template).

- Formato consigliato: **SVG** (si adatta a qualsiasi risoluzione e DPI)
- Il logo viene visualizzato affianco al nome del comune
- Se il campo è vuoto, l'area logo rimane vuota

### Nome comune, regione e payoff

| Parametro | Dove appare |
|-----------|-------------|
| `nomesito` | Nome principale in grassetto sotto il logo |
| `nomeregione` | Testo più piccolo, linea sopra il nome comune (con link se `linkregione` è valorizzato) |
| `payoff` | Sottotitolo sotto il nome comune |

> TODO – inserire screenshot del dettaglio nome/payoff con indicazioni visive su quale campo corrisponde a cosa

### Icone social

Le icone social vengono mostrate nell'header a destra. Ogni icona compare solo se il relativo parametro è valorizzato (vedere [configurazione-joomla.md](configurazione-joomla.md) → scheda Social).

Social supportati: X (Twitter), Facebook, YouTube, Telegram, WhatsApp, LinkedIn.

### Bottone Login

Visibile solo se `mostra_login = Sì` nella scheda **Opzioni Header**.

Due modalità:
- **Standard**: apre il form di login nativo di Joomla
- **Custom**: rimanda al menu item selezionato in `menuitem_login`

> TODO – inserire screenshot del bottone login nell'header, e della tendina che appare al clic in modalità standard

### Bottone Ricerca

Visibile solo se `mostra_ricerca = Sì` nella scheda **Opzioni Header**.

Due modalità:
- **Standard**: apre un campo di ricerca Smart Search integrato nell'header
- **Custom**: rimanda al menu item selezionato in `menuitem_ricerca`

> TODO – inserire screenshot del bottone ricerca e dell'input che compare al clic

---

## Navbar (barra di navigazione)

La navbar contiene il menu principale e il menu secondario. Su mobile si chiude in un menu hamburger.

I moduli menu assegnati alle posizioni `menu-principale` e `menu-secondario` **devono** usare il layout alternativo `comuni-menu`. Senza questo layout il menu non viene renderizzato correttamente.

Vedere la guida dedicata: [menu.md](menu.md).

---

## Note sull'accessibilità

Il template include automaticamente:
- **Skip links** (`visually-hidden`) per saltare al contenuto principale, al menu e al footer
- `aria-label` su tutte le aree di navigazione
- Focus visibile su tutti i controlli interattivi
