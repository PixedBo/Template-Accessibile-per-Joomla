# Colori e temi

Il template include 5 palette colore preimpostate, pensate per diversi tipi di enti della PA italiana.

## Palette disponibili

| Colore HEX | Nome | Pensato per |
|------------|------|-------------|
| `#0066CC` | Blu istituzionale | Generico (default) |
| `#007a52` | Verde Comuni | Comuni |
| `#d1344c` | Rosso Scuole | Istituti scolastici |
| `#07768d` | Verde Acqua ASL | Aziende sanitarie |
| `#7d2670` | Viola Musei | Musei e beni culturali |

## Come si seleziona

Nel backend: **Estensioni → Template → Stili** → modifica lo stile → scheda **Generale** → campo `coloreprimario`.

> TODO – inserire screenshot del campo `coloreprimario` con il menu a tendina aperto

## Cosa cambia scegliendo un colore

Il template inietta dinamicamente variabili CSS nella `<head>` della pagina:

- `--bs-primary` e `--bs-link-color` → colore principale di Bootstrap Italia
- `--bs-primary-rgb` → componenti RGB per le trasparenze
- `--bs-success` e `--bs-info` → derivati automatici
- Hover e focus dei pulsanti via `color-mix()`

Cambia anche l'**immagine di sfondo** della sezione `evidenza`:

| Colore | Immagine |
|--------|----------|
| `#0066CC` | `images/blu-default.jpg` |
| `#007a52` | `images/verde-comuni.png` |
| `#d1344c` | `images/rosso-scuola.jpg` |
| `#07768d` | `images/verde-acqua-asl.jpg` |
| `#7d2670` | `images/viola-musei.jpg` |