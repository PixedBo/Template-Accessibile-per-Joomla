# Scheda Servizio (C.SI.1.3)

La Scheda Servizio è il layout per gli articoli che descrivono in dettaglio un servizio del comune. È richiesta dal criterio C.SI.1.3 del Modello Comuni e verificata automaticamente dall'App Valutazione Modelli.

---

## Panoramica

Il layout `scheda-servizio` genera una pagina strutturata con:
- Una **hero** con titolo, stato e testo introduttivo
- Un **indice di pagina** laterale (scrollspy)
- **9 sezioni** in ordine fisso, ognuna con il `data-element` corretto
- Un blocco **JSON-LD** schema.org (`GovernmentService`) per i motori di ricerca
- Il **widget feedback chiarezza** in fondo (se abilitato)

---

## Setup: passi necessari

### Passo 1 — Crea i custom field

Vai su **Contenuti → Campi → Nuovo** e crea i campi necessari. Per la lista completa dei campi e dei tipi consigliati, vedere [custom-field-articoli.md](custom-field-articoli.md).

Consiglio: crea un **gruppo di campi** dedicato (es. "Scheda Servizio") per tenerli separati dagli altri.

> TODO – inserire screenshot del gruppo "Scheda Servizio" con tutti i campi elencati

### Passo 2 — Mappa i campi nel template

1. Vai su **Estensioni → Template → Stili** → modifica lo stile del template
2. Apri la scheda **Valutazione Comuni** → sezione **Scheda Servizio**
3. Per ogni parametro (`cf_stato`, `cf_a_chi_rivolto`, …) seleziona il custom field corrispondente
4. Per i **menu item** (`menuitem_contatti_area`, `menuitem_contatti`), seleziona le voci di menu appropriate
5. Salva

> TODO – inserire screenshot dell'intera sezione "Scheda Servizio" nel backend del template con tutti i dropdown compilati

### Passo 3 — Crea l'articolo e assegna il layout

1. Vai su **Contenuti → Articoli → Nuovo**
2. Compila **Titolo** (obbligatorio) e **Testo introduttivo** (`introtext`) — usato come descrizione nel JSON-LD
3. Assegna la categoria Servizi corretta
4. Nella scheda **Opzioni** → **Layout alternativo**: seleziona `scheda-servizio`
5. Nella scheda campi personalizzati: compila tutti i campi mappati
6. Nella scheda **Pubblicazione** → **In Evidenza**: decidi se mostrarlo nella sidebar dei servizi in evidenza
7. Aggiungi **Tag** (argomenti): usati per la sezione "Argomenti" nella sidebar della scheda

> TODO – inserire screenshot della scheda Opzioni con il layout `scheda-servizio` selezionato

---

## Struttura delle sezioni della Scheda Servizio

Le sezioni appaiono sempre **in questo ordine fisso**, indipendentemente dall'ordine dei custom field:

| # | Sezione | Campo | data-element |
|---|---------|-------|-------------|
| 1 | A chi è rivolto | `cf_a_chi_rivolto` | `service-addressed` |
| 2 | Come fare | `cf_come_fare` | `service-how-to` |
| 3 | Cosa serve | `cf_cosa_serve` | `service-needed` |
| 4 | Cosa si ottiene | `cf_cosa_si_ottiene` | `service-achieved` |
| 5 | Tempi e scadenze | `cf_tempi_scadenze` + `cf_tempi_scadenze_lista` | `service-calendar-text` / `service-calendar-list` |
| 6 | Quanto costa | `cf_quanto_costa` | `service-cost` |
| 7 | Accedi al servizio | `cf_accedi_online` + `cf_accedi_appuntamento` | `service-online-access` / `service-booking-access` |
| 8 | Condizioni di servizio | `cf_condizioni` + `cf_condizioni_file` | `service-file` |
| 9 | Contatti | `menuitem_contatti_area` + `menuitem_contatti` | `service-area` |

Le sezioni con campo vuoto vengono **saltate** nel rendering.

---

## Indice di pagina (scrollspy)

Il layout genera automaticamente un indice laterale (`data-element="page-index"`) con i link alle 8 voci principali (esclusa "Quanto costa"):

```
A chi è rivolto
Come fare
Cosa serve
Cosa si ottiene
Tempi e scadenze
Accedi al servizio
Condizioni di servizio
Contatti
```

Le etichette devono corrispondere **esattamente** al testo italiano atteso dal validatore. Il template le genera automaticamente dalle costanti di lingua, quindi non richiedono configurazione.

> TODO – inserire screenshot del layout con l'indice di pagina laterale visibile su desktop

---

## Hero della Scheda Servizio

La hero (parte superiore) mostra:

- **Titolo** dell'articolo (`data-element="service-title"`)
- **Breadcrumb** con il path della categoria (`data-element="breadcrumb"`) — generato dal modulo nella posizione `percorso`
- **Badge di stato** del servizio (`cf_stato`, `data-element="service-status"`)
- **Testo introduttivo** dell'articolo (`data-element="service-description"`)
- **Pulsante accesso online** (se `cf_accedi_online` è valorizzato)

> TODO – inserire screenshot della hero di una Scheda Servizio compilata

---

## Sidebar destra

La sidebar destra contiene:
- Pulsanti di condivisione social (Facebook, X, LinkedIn, WhatsApp)
- Pulsanti Stampa e Invia
- Sezione **Argomenti** con i tag dell'articolo (`data-element="service-topic"`)

I tag devono essere assegnati all'articolo dalla scheda **Contenuto** → campo **Tag**.

---

## JSON-LD (schema.org)

Il layout genera automaticamente un blocco `<script type="application/ld+json" data-element="metatag">` con:

```json
{
  "@context": "https://schema.org",
  "@type": "GovernmentService",
  "name": "[titolo articolo]",
  "description": "[introtext articolo]",
  "serviceType": "[titolo categoria]",
  "areaServed": "[nomesito dal template]",
  "url": "[URL corrente]"
}
```

Non è richiesta nessuna configurazione aggiuntiva.

---

## Widget feedback chiarezza

In fondo alla scheda appare il widget di valutazione (3 step: stelle → domanda → campo testo → ringraziamento). Viene mostrato solo se il parametro `mostra_feedback` è abilitato nella scheda **Valutazione Comuni** del template.

Vedere [valutazione-modelli.md](valutazione-modelli.md) per i dettagli.

---

## Verifica con App Valutazione Modelli

L'App Valutazione Modelli (Dipartimento per la Trasformazione Digitale) verifica automaticamente la presenza e il contenuto dei `data-element`. Affinché la verifica passi, ogni sezione deve avere contenuto di almeno 3 caratteri.

> TODO – inserire screenshot del report dell'App Valutazione Modelli con la sezione C.SI.1.3 superata
