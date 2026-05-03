# Configurazione del template

Questa guida spiega come accedere alle impostazioni del template e cosa fa ogni parametro.

## Come accedere

Dal backend di Joomla: **Estensioni → Template → Stili**, quindi clicca sul nome dello stile del template.

> TODO – inserire screenshot del pannello "Stili template" con evidenziato lo stile da modificare

Le impostazioni sono organizzate in schede (tab):

- **Generale** – branding e colore
- **Opzioni Header** – login e ricerca
- **Footer** – logo UE
- **Social** – link ai canali social
- **Valutazione Comuni** – mapping data-element e widget feedback

---

## Scheda: Generale

### Logo

| Campo | Tipo | Descrizione |
|-------|------|-------------|
| `logotipo` | Media | Logo del comune. Formato consigliato: SVG. Viene visualizzato nell'header. |
| `favicon_svg` | Media | Favicon in formato SVG (prioritario nei browser moderni). |
| `favicon_png` | Media | Favicon in formato PNG, usato come fallback per Apple devices (`apple-touch-icon`). |

### Identità

| Campo | Tipo | Descrizione |
|-------|------|-------------|
| `nomesito` | Testo | Nome del comune o dell'ente (es. `Comune di Milano`). Appare nell'header e nel JSON-LD delle schede servizio. |
| `nomeregione` | Testo | Nome della regione o dell'ente sovraordinato (es. `Regione Lombardia`). Appare nella banda slim superiore. |
| `linkregione` | URL | Link al sito della regione. La banda slim superiore diventa cliccabile. |
| `payoff` | Testo | Sottotitolo visualizzato sotto il nome del comune nell'header (es. `Città Metropolitana`). |

### Colore primario

| Campo | Tipo | Descrizione |
|-------|------|-------------|
| `coloreprimario` | Lista | Seleziona la palette colore. Vedere [colori-e-temi.md](colori-e-temi.md) per i dettagli. |

Opzioni disponibili:
- `#0066CC` — Blu istituzionale (default)
- `#007a52` — Verde Comuni
- `#d1344c` — Rosso Scuole
- `#07768d` — Verde Acqua ASL
- `#7d2670` — Viola Musei

---

## Scheda: Opzioni Header

### Login

| Campo | Tipo | Descrizione |
|-------|------|-------------|
| `mostra_login` | Radio (Sì/No) | Mostra o nasconde il bottone login nell'header. |
| `tipo_login` | Lista | `standard` = usa il login nativo di Joomla; `custom` = usa un menu item personalizzato. Visibile solo se `mostra_login = Sì`. |
| `menuitem_login` | Menu item | Menu item a cui punta il bottone login (solo se `tipo_login = custom`). |

### Ricerca

| Campo | Tipo | Descrizione |
|-------|------|-------------|
| `mostra_ricerca` | Radio (Sì/No) | Mostra o nasconde il bottone ricerca nell'header. |
| `tipo_ricerca` | Lista | `standard` = usa Smart Search integrato; `custom` = usa un menu item personalizzato. Visibile solo se `mostra_ricerca = Sì`. |
| `menuitem_ricerca` | Menu item | Menu item per la ricerca (solo se `tipo_ricerca = custom`). |

---

## Scheda: Footer

| Campo | Tipo | Descrizione |
|-------|------|-------------|
| `mostra_logo_ue` | Radio (Sì/No) | Mostra il logo dell'Unione Europea nel footer. Default: Sì. |

---

## Scheda: Social

Ogni campo accetta un URL completo. I campi vuoti non generano alcun link nel template.

| Campo | Tipo | Esempio |
|-------|------|---------|
| `socialx` | URL | `https://x.com/comune_milano` |
| `facebook` | URL | `https://www.facebook.com/comunemilano` |
| `youtube` | URL | `https://www.youtube.com/...` |
| `telegram` | URL | `https://t.me/comunemilano` |
| `whatsapp` | Testo | Solo il numero di telefono, es. `+39 02 12345678`. Il template genera automaticamente il link `wa.me/`. |
| `linkedin` | URL | `https://www.linkedin.com/company/...` |

---

## Scheda: Valutazione Comuni

Contiene il mapping dei menu item verso i `data-element` richiesti dall'App Valutazione Modelli e il toggle per il widget feedback. Vedere la guida dedicata: [valutazione-modelli.md](valutazione-modelli.md).