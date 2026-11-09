# Valutazione Modelli Comuni

> **Modello Comuni** — Questa guida è specifica per i siti web dei Comuni italiani.

L'App Valutazione Modelli (gestita dal Dipartimento per la Trasformazione Digitale) verifica automaticamente la conformità del sito al Modello Comuni tramite la presenza di attributi `data-element` nell'HTML delle pagine.

Il template gestisce questi attributi in due modi:
1. **Automaticamente** nei layout specializzati (scheda-servizio, notizie, servizi, note-legali, articolo) — senza bisogno di configurazione
2. **Tramite configurazione** nella scheda "Valutazione Comuni" del template — per i link istituzionali nei menu

Questa guida riguarda la parte di configurazione.

---

## Come accedere alla configurazione

1. Vai su **Estensioni → Template → Stili** e clicca sul nome del template
2. Clicca sulla scheda **Valutazione Comuni**

La scheda è divisa in due sezioni principali:
- **Mapping menu item** — collegare le voci di menu ai data-element richiesti
- **Widget feedback** — abilitare il widget di valutazione chiarezza

> *Screenshot — Scheda "Valutazione Comuni" del template con tutte le sezioni visibili*

---

## Sezione: Mapping dei menu item

Questa sezione contiene una serie di campi dropdown. Per ognuno, devi selezionare la **voce di menu** Joomla che punta alla pagina corrispondente. Il template inietta automaticamente il `data-element` corretto sul link generato.

**Prima di configurare questi campi**, assicurati di aver già creato le pagine corrispondenti come articoli Joomla e le relative voci di menu.

| Campo nel template | data-element emesso | Pagina da collegare |
|-------------------|---------------------|---------------------|
| Amministrazione | `management` | La sezione Amministrazione del comune |
| Novità | `news` | La sezione Novità/Notizie |
| Tutti i servizi | `all-services` | La sezione Servizi |
| Vivere il Comune | `live` | La sezione "Vivere il Comune" |
| FAQ | `faq` | La pagina delle domande frequenti |
| Segnala disservizio | `report-inefficiency` | La pagina per segnalare disservizi |
| Dichiarazione accessibilità | `accessibility-link` | La dichiarazione di accessibilità |
| Privacy policy | `privacy-policy-link` | L'informativa sulla privacy |
| Tutti gli argomenti | `all-topics` | La pagina con tutti i tag/argomenti |
| Note legali | `legal-notes` | La pagina Note Legali |

### Come configurare il mapping passo-passo

1. Nella scheda **Valutazione Comuni**, individua il primo campo (es. "Amministrazione")
2. Clicca sul pulsante di selezione accanto al campo — si aprirà un popup con la lista delle voci di menu disponibili
3. Seleziona la voce di menu corretta
4. Ripeti per ogni campo
5. Clicca **Salva** in cima alla pagina del template

> *Screenshot — Popup di selezione voce di menu, con la lista delle voci disponibili e la possibilità di selezionarne una*

**Cosa succede se non compili un campo?** I `data-element` per quel criterio non vengono emessi nel codice HTML del sito, e l'App Valutazione Modelli potrebbe segnalare la mancanza come non conformità.

---

## Sezione: Widget feedback chiarezza (C.SI.2.5 / C.SI.2.6)

Il widget di valutazione della chiarezza della pagina appare in fondo a tutte le pagine di contenuto: articoli, schede servizio e liste di categoria.

### Come abilitarlo

1. Nella scheda **Valutazione Comuni**, trova il campo **Mostra widget valutazione**
2. Seleziona **Sì**
3. Salva

Il widget viene caricato automaticamente su tutte le pagine del componente Articoli (`com_content`): articoli singoli con qualsiasi layout, categorie e articoli in evidenza. Non compare sulle pagine di altri componenti (per esempio gli eventi di DPCalendar).

### Come funziona il widget

Il widget presenta un flusso a 4 step:

**Step 1 — Valutazione stellare**
L'utente vede 5 stelle e la domanda "Quanto sono chiare le informazioni su questa pagina?". Clicca su una stella (1 = per niente chiaro, 5 = molto chiaro). Subito dopo scatta il passaggio al passo successivo.

**Step 2 — Domanda di follow-up**
Il sistema mostra una domanda diversa in base al voto:
- Se il voto è **4 o 5** (positivo): "Cosa ti è stato più utile?" con 5 opzioni di risposta positiva
- Se il voto è **1, 2 o 3** (negativo): "Come potremmo migliorare?" con 5 opzioni di risposta negativa

L'utente seleziona una delle 5 opzioni e clicca "Avanti".

**Step 3 — Campo testo libero (opzionale)**
L'utente può aggiungere un commento dettagliato (massimo 200 caratteri). Clicca "Avanti" per procedere anche senza compilare.

**Step 4 — Ringraziamento**
Il widget mostra "Grazie per il tuo feedback" e l'interazione termina.

> *Screenshot — Widget feedback allo Step 1 con le 5 stelle visibili*
> *Screenshot — Widget feedback allo Step 2 con le opzioni di risposta (es. versione negativa)*
> *Screenshot — Widget feedback allo Step 3 con il campo testo*

**Nota tecnica importante:** nella versione attuale del template, le risposte del widget **non vengono inviate a nessun server**. Il widget è conforme alla specifica dell'App Valutazione Modelli (che verifica solo la presenza e la struttura degli elementi HTML), ma non salva i dati da nessuna parte. L'invio dei dati a un backend è una funzionalità che potrà essere aggiunta in versioni future.

### Data-element emessi dal widget

Questi attributi vengono verificati dall'App Valutazione Modelli:

| Elemento | data-element |
|----------|-------------|
| Contenitore del widget | `feedback` |
| Titolo della domanda principale | `feedback-title` |
| Stella 1 | `feedback-rate-1` |
| Stella 2 | `feedback-rate-2` |
| Stella 3 | `feedback-rate-3` |
| Stella 4 | `feedback-rate-4` |
| Stella 5 | `feedback-rate-5` |
| Gruppo risposte positive | `feedback-rating-positive` |
| Gruppo risposte negative | `feedback-rating-negative` |
| Testo della domanda di follow-up | `feedback-rating-question` |
| Singola opzione di risposta | `feedback-rating-answer` |
| Campo testo libero | `feedback-input-text` |

---

## Data-element automatici (non richiedono configurazione)

Questi `data-element` vengono emessi dai layout del template senza nessuna configurazione aggiuntiva:

| data-element | Dove è emesso | Criterio Modello Comuni |
|-------------|--------------|------------------------|
| `service-title` | Titolo nella Scheda Servizio | C.SI.1.3 |
| `service-status` | Badge stato nella Scheda Servizio | C.SI.1.3 |
| `service-description` | Testo introduttivo Scheda Servizio | C.SI.1.3 |
| `service-addressed` | Sezione "A chi è rivolto" | C.SI.1.3 |
| `service-how-to` | Sezione "Come fare" | C.SI.1.3 |
| `service-needed` | Sezione "Cosa serve" | C.SI.1.3 |
| `service-achieved` | Sezione "Cosa si ottiene" | C.SI.1.3 |
| `service-calendar-text` | Testo tempi e scadenze | C.SI.1.3 |
| `service-calendar-list` | Lista scadenze (subform) | C.SI.1.3 |
| `service-cost` | Sezione "Quanto costa" | C.SI.1.3 |
| `service-online-access` | Pulsante accesso online | C.SI.1.3 |
| `service-booking-access` | Pulsante prenotazione | C.SI.1.3 |
| `service-file` | Link al file condizioni | C.SI.1.3 |
| `service-area` | Card ufficio responsabile | C.SI.1.3 |
| `service-topic` | Tag/argomenti del servizio | C.SI.1.3 |
| `service-link` | Link agli articoli servizio | C.SI.1.3 |
| `service-category-link` | Link alle sottocategorie servizi | C.SI.1.3 |
| `page-index` | Indice di pagina (articolo e scheda servizio) | Navigazione |
| `legal-notes` | Sezione Note Legali | C.SI.3.4 |
| `breadcrumb` | Breadcrumb di navigazione | Navigazione |
| `metatag` | Blocco JSON-LD | SEO / C.SI.1.3 |
| `main-navigation` | Navbar principale | Navigazione |
| `personal-area-login` | Pulsante login nell'header | Area personale |
| `management-category-link` | Link alle sottocategorie Amministrazione | C.SI.1.7 |
| `live-category-link` | Link agli articoli di Vivere il Comune | Navigazione |
| `live-button-events` | Pulsante "Tutti gli eventi" | Navigazione |
| `live-button-locations` | Pulsante "Tutti i luoghi" | Navigazione |
| `contacts` | Link alla pagina Contatti (blocco Contatta) | Contatti |
| `appointment-booking` | Link prenotazione appuntamento (blocco Contatta) | Contatti |
| `feedback-*` | Tutti gli elementi del widget feedback | C.SI.2.5 / C.SI.2.6 |

---

## Criteri fuori scope del template

Alcuni criteri del Modello Comuni non possono essere gestiti dal template e richiedono interventi esterni:

| Criterio | Perché non è nel template |
|---------|--------------------------|
| C.SI.5.1 — HTTPS | Configurazione del server web e del certificato SSL |
| C.SI.5.2 — Dominio `comune.*` | Configurazione DNS del dominio |
| C.SI.2.1 — Prenotazione appuntamenti | Richiede un componente Joomla dedicato |
| C.SI.3.1 / C.SE.3.1 — Cookie banner | Plugin di gestione consenso cookie esterno |
| C.SE.* — SPID, CIE, PagoPA, AppIO | Richiedono integrazioni con sistemi esterni della PA |
