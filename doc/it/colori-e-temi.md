# Colori e temi

Il template include 5 palette colore preimpostate, pensate per diversi tipi di enti della PA italiana. Ogni palette è stata verificata per il contrasto cromatico richiesto dall'accessibilità (WCAG 2.1 AA).

---

## Palette disponibili

| Colore HEX | Nome | Pensato per |
|------------|------|-------------|
| `#0066CC` | Blu istituzionale | Generico |
| `#007a52` | Verde Comuni | Comuni (colore preselezionato a una nuova installazione) |
| `#d1344c` | Rosso Scuole | Istituti scolastici |
| `#07768d` | Verde Acqua ASL | Aziende sanitarie |
| `#7d2670` | Viola Musei | Musei e beni culturali |

---

## Come cambiare il colore

1. Vai su **Estensioni → Template → Stili** e clicca sul nome del template
2. Nella scheda **Generale**, scorri fino al campo **Colore primario del sito**
3. Clicca sul menu a tendina e seleziona il colore desiderato
4. Clicca **Salva** (in alto a sinistra)

La modifica ha effetto immediato su tutto il sito: non è necessario fare nient'altro.

> *Screenshot — Scheda "Generale" del template con il menu a tendina "Colore primario del sito" aperto, che mostra le 5 opzioni con i colori*

---

## Cosa cambia scegliendo un colore

Quando selezioni un colore, il template agisce su tre livelli:

### 1. Variabili CSS

Il template inietta automaticamente nella `<head>` di ogni pagina delle variabili CSS che sovrascrivono le variabili di Bootstrap Italia:

- `--bs-primary` e `--bs-link-color` → usati per i pulsanti, i link e gli accenti
- `--bs-primary-rgb` → componenti RGB del colore primario (usate per le trasparenze)
- `--bs-success` e `--bs-info` → impostate sullo stesso colore per coerenza
- L'`header slim` (la banda sottile in cima) usa una versione leggermente scurita del colore tramite `color-mix()`

Questo significa che tutti gli elementi Bootstrap Italia che usano la classe `btn-primary`, `text-primary`, `bg-primary`, ecc. cambiano colore automaticamente.

Dalla versione 1.2.1 seguono il colore scelto anche gli elementi che prima restavano sempre verdi: il blocco "Contatta il Comune", il logo e i social del menu mobile, il calendario "Tempi e scadenze" della Scheda Servizio e gli sfondi `.has-bg-grey`.

### 2. Immagine di sfondo della sezione "Evidenza"

La sezione `evidenza` (posizione modulo) ha uno sfondo diverso per ogni colore, ottimizzato cromaticamente:

| Colore | Immagine di sfondo |
|--------|-------------------|
| `#0066CC` — Blu | `images/blu-default.jpg` |
| `#007a52` — Verde | `images/verde-comuni.png` |
| `#d1344c` — Rosso | `images/rosso-scuola.jpg` |
| `#07768d` — Verde Acqua | `images/verde-acqua-asl.jpg` |
| `#7d2670` — Viola | `images/viola-musei.jpg` |

### 3. Hover e focus degli elementi interattivi

I pulsanti, i link e gli elementi con focus cambiano colore in modo coerente con la palette selezionata.

---

## Non puoi usare un colore personalizzato?

No. Il template accetta solo i 5 colori preimpostati. Questa scelta è intenzionale: ogni colore è stato verificato per garantire un contrasto sufficiente con il testo bianco (WCAG 2.1 AA). Usare un colore arbitrario potrebbe rendere il sito non conforme all'accessibilità.

Se il tuo ente ha un colore istituzionale diverso, scegli la palette più vicina tra quelle disponibili.
