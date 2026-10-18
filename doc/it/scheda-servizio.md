# Scheda Servizio (C.SI.1.3)

> **Modello Comuni** — Questa guida è specifica per i siti web dei Comuni italiani.

La Scheda Servizio è il layout per gli articoli che descrivono in dettaglio un servizio del comune. È richiesta dal criterio C.SI.1.3 del Modello Comuni e verificata automaticamente dall'App Valutazione Modelli.

---

## Cosa genera questo layout

Quando assegni il layout `scheda-servizio` a un articolo, il template genera automaticamente:

- Una **hero** con titolo, badge di stato e testo introduttivo del servizio
- Un **breadcrumb** con il percorso di categoria, che include la voce "Servizi" e la sottocategoria del servizio
- Un **indice di pagina laterale** (scrollspy) con i link alle 8 sezioni principali
- **9 sezioni** in ordine fisso, ognuna con il `data-element` corretto per la validazione
- Un blocco **JSON-LD** schema.org (`GovernmentService`) per i motori di ricerca
- Il **widget feedback chiarezza** in fondo (se abilitato nelle impostazioni del template)

---

## Setup: 3 passi necessari

Per usare la Scheda Servizio, devi completare questi 3 passi — **nell'ordine indicato**:

1. Creare i custom field in Joomla (vedi [custom-field-articoli.md](custom-field-articoli.md))
2. Mappare i campi nelle impostazioni del template
3. Creare l'articolo con il layout `scheda-servizio` e compilare i campi

---

## Passo 1 — Crea i custom field

Se non l'hai ancora fatto, vai su [custom-field-articoli.md](custom-field-articoli.md) e segui le istruzioni per creare tutti i campi personalizzati del gruppo "Servizi - Modello Comuni".

Ricorda anche di creare il **Gruppo di Campi** "Servizi - Modello Comuni" prima di creare i singoli campi.

Una volta creati, i campi saranno visibili nella lista **Contenuti → Campi**.

> *Screenshot — Lista "Contenuti → Campi" con tutti i campi del gruppo "Servizi - Modello Comuni" visibili*

---

## Passo 2 — Mappa i campi nel template

Dopo aver creato i campi, devi collegare ciascun campo alla sezione corrispondente della Scheda Servizio nelle impostazioni del template.

1. Vai su **Estensioni → Template → Stili** e clicca sul nome del template
2. Clicca sulla scheda **Valutazione Comuni**
3. Scorri fino alla sezione **Scheda Servizio**
4. Per ogni riga, seleziona il campo corrispondente dal menu a tendina:

| Riga nel template | Campo da selezionare |
|-------------------|---------------------|
| Campo per lo Stato | Stato |
| Campo A chi è rivolto | A chi è rivolto |
| Campo Come fare | Come fare |
| Campo Cosa serve | Cosa serve |
| Campo Cosa si ottiene | Cosa si ottiene |
| Campo Tempi e scadenze (testo) | Tempi e scadenze |
| Campo Subform per le tempistiche | Lista Tempistiche |
| Campo Titolo (nel subform) | Titolo |
| Campo Data (nel subform) | Data |
| Campo Numero giorni/mesi (nel subform) | Numero giorni/mesi |
| Campo Unità di misura (nel subform) | Unità di misura |
| Campo Descrizione (nel subform) | Descrizione |
| Campo Quanto costa | Quanto costa |
| Campo URL accesso online | Accesso online |
| Campo testo bottone accesso online | Testo - Accesso Online |
| Campo URL prenotazione appuntamento | Prenota un appuntamento |
| Campo condizioni di servizio (testo) | Condizioni di servizio - Introduzione |
| Campo file condizioni di servizio | Condizioni di servizio - FILE |

5. Scorri oltre nella stessa scheda per trovare anche i campi dei **Menu Item Contatti**:

| Riga nel template | Cosa selezionare |
|-------------------|-----------------|
| Voce di menu ufficio/area | Voce di menu che porta all'ufficio o all'area responsabile del servizio |
| Voce di menu contatti | Voce di menu che porta alla pagina Contatti generali dell'ente |

Questi menu item devono esistere già in Joomla. Se non li hai ancora creati, crea prima le pagine e le voci di menu corrispondenti, poi torna qui a completare il mapping.

6. Clicca **Salva**

> *Screenshot — Sezione "Scheda Servizio" nella scheda "Valutazione Comuni" del template, con tutti i dropdown compilati e visibili*

---

## Passo 3 — Crea l'articolo e assegna il layout

### 3a. Crea un nuovo articolo

1. Vai su **Contenuti → Articoli → Nuovo**
2. Nel campo **Titolo**, inserisci il nome del servizio (es. `Iscrizione all'asilo nido`). Questo sarà il titolo mostrato nella hero con `data-element="service-title"`.
3. Nel campo **Alias**, lascia quello generato automaticamente o personalizzalo
4. Nel campo **Categoria**, seleziona la sottocategoria di Servizi corretta (es. `Educazione e formazione`). Il nome della categoria comparirà nel blocco JSON-LD come `serviceType`.

### 3b. Compila il testo introduttivo

Nell'editor, scrivi il testo introduttivo del servizio nel campo **Testo introduttivo** (la parte prima del tag "Leggi di più", se usi la suddivisione). Questo testo viene mostrato come `data-element="service-description"` nella hero e come `description` nel blocco JSON-LD.

Se hai anche del testo nel corpo dell'articolo (la parte dopo "Leggi di più"), questo viene mostrato come sezione **Descrizione** aggiuntiva nella Scheda Servizio, prima delle sezioni A chi è rivolto, Come fare, ecc.

> **Importante:** il **testo introduttivo** è la descrizione principale del servizio — compilalo sempre con un testo chiaro e sintetico (almeno 3 caratteri, preferibilmente alcune frasi). Il **corpo** è opzionale e serve per dettagli aggiuntivi.

### 3c. Assegna il layout alternativo

1. Clicca sulla scheda **Opzioni** (in basso sotto l'editor)
2. Nel campo **Layout alternativo**, seleziona `scheda-servizio`

> *Screenshot — Scheda "Opzioni" dell'articolo con il campo "Layout alternativo" impostato su "scheda-servizio"*

### 3d. Compila i custom field

1. Clicca sulla scheda **Servizi - Modello Comuni** (o il nome del gruppo di campi che hai creato)
2. Compila tutti i campi rilevanti per questo servizio. Non è obbligatorio compilare tutti i campi: le sezioni con campo vuoto non vengono renderizzate.

Per ogni campo di tipo Editor, usa l'editor visuale per formattare il testo (elenchi puntati, grassetti, link, ecc.).

Per il campo **Lista Tempistiche** (Subform):
- Clicca **Aggiungi riga** per ogni scadenza
- Per ogni riga, compila Titolo e poi: o la **Data** (per una data specifica) o **Numero giorni/mesi + Unità di misura** (es. `30` giorni)
- La **Descrizione** è opzionale per ogni riga

Per il campo **Condizioni di servizio - FILE**: su Joomla 6.1 o superiore è un campo di tipo **Documento**, quindi clicca sul campo, scegli o carica il PDF nella cartella Documenti e, se vuoi, compila il **Testo del link**. Su Joomla 5.x / 6.0 è un campo di tipo **URL**: carica prima il PDF da **Contenuti → Media** (cartella Documenti), copia il suo indirizzo e incollalo nel campo. I dettagli sono in [custom-field-articoli.md](custom-field-articoli.md).

> *Screenshot — Scheda "Servizi - Modello Comuni" nell'editor articolo con i campi visibili e alcuni compilati*

### 3e. Imposta la visibilità nei Servizi in evidenza

Se questo servizio deve apparire nella sidebar "Servizi in evidenza" nella pagina della categoria:
- Nella scheda **Pubblicazione** → campo **In Evidenza**: seleziona **Sì**

### 3f. Aggiungi i tag (argomenti)

I tag dell'articolo vengono mostrati nella sidebar destra della Scheda Servizio come "Argomenti", con `data-element="service-topic"`. È richiesto dall'App Valutazione Modelli che almeno un tag/argomento sia presente.

- Nel campo **Tag** in cima all'editor, digita i tag del servizio (es. `anagrafe`, `certificati`, `documenti`) e premi Invio per ognuno

### 3g. Pubblica e salva

1. Imposta lo **Stato** su *Pubblicato*
2. Clicca **Salva e chiudi**

---

## Struttura delle 9 sezioni della Scheda Servizio

Le sezioni della scheda appaiono sempre in questo ordine fisso, indipendentemente dall'ordine dei campi. Le sezioni con campo vuoto vengono saltate — non viene mostrato nemmeno il titolo della sezione.

| # | Titolo sezione | Campo corrispondente | data-element |
|---|---------------|---------------------|-------------|
| 1 | A chi è rivolto | A chi è rivolto | `service-addressed` |
| 2 | Come fare | Come fare | `service-how-to` |
| 3 | Cosa serve | Cosa serve | `service-needed` |
| 4 | Cosa si ottiene | Cosa si ottiene | `service-achieved` |
| 5 | Tempi e scadenze | Tempi e scadenze + Lista Tempistiche | `service-calendar-text` / `service-calendar-list` |
| 6 | Quanto costa | Quanto costa | `service-cost` |
| 7 | Accedi al servizio | Accesso online + Prenota un appuntamento | `service-online-access` / `service-booking-access` |
| 8 | Condizioni di servizio | Condizioni (testo) + Condizioni (file) | `service-file` |
| 9 | Contatti | Voce di menu ufficio + Voce di menu contatti | `service-area` / `contacts` |

---

## Indice di pagina (scrollspy)

Il layout genera automaticamente un indice laterale (`data-element="page-index"`) con i link alle sezioni principali. L'indice evidenzia la sezione corrente durante lo scroll della pagina.

Le etichette dell'indice sono fisse e definite dal template:

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

**Nota:** la sezione "Quanto costa" non appare nell'indice (è considerata sezione secondaria dal Modello Comuni), ma viene comunque mostrata nel corpo della pagina.

> *Screenshot — Indice laterale scrollspy della Scheda Servizio su desktop, con una sezione evidenziata*

---

## Blocco JSON-LD (GovernmentService)

Il layout genera automaticamente un blocco `<script type="application/ld+json" data-element="metatag">` con il seguente schema:

```json
{
  "@context": "https://schema.org",
  "@type": "GovernmentService",
  "name": "[titolo dell'articolo]",
  "description": "[testo introduttivo dell'articolo]",
  "serviceType": "[nome della categoria dell'articolo]",
  "areaServed": "[valore del campo nomesito del template]",
  "url": "[URL corrente della pagina]"
}
```

Non è richiesta nessuna configurazione aggiuntiva per questo blocco.

---

## Come verificare la conformità

L'App Valutazione Modelli (Dipartimento per la Trasformazione Digitale) verifica automaticamente la presenza e il contenuto dei `data-element`. Per superare la verifica:

- Ogni sezione deve avere contenuto di **almeno 3 caratteri**
- Il breadcrumb deve contenere la voce "Servizi" e il nome della sottocategoria
- L'indice di pagina deve avere le 8 voci con le etichette esatte in italiano
- Almeno un tag/argomento deve essere presente (`data-element="service-topic"`)
- Il blocco JSON-LD deve essere presente

> *Screenshot — Report dell'App Valutazione Modelli con la sezione C.SI.1.3 superata*
