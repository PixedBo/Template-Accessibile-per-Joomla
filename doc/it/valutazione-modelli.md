# Valutazione Modelli Comuni

L'App Valutazione Modelli (gestita dal Dipartimento per la Trasformazione Digitale) verifica automaticamente la conformità del sito al Modello Comuni tramite la presenza di attributi `data-element` nell'HTML.

Il template gestisce questi attributi in due modi:
1. **Automaticamente** nei layout specializzati (scheda-servizio, notizie, servizi, note-legali, articolo)
2. **Tramite configurazione** per i link istituzionali nei menu

---

## Mapping dei menu item (scheda "Valutazione Comuni")

Nella scheda **Valutazione Comuni** delle impostazioni del template trovi una lista di campi dove devi selezionare il **menu item** Joomla che punta alla pagina corrispondente.

Il template inietta automaticamente il `data-element` corretto sul link generato da quel menu item.

| Campo nel template | data-element | Contenuto atteso |
|-------------------|-------------|-----------------|
| `de_management` | `management` | Voce di menu che punta alla sezione Amministrazione/Organigramma |
| `de_news` | `news` | Voce di menu che punta alla sezione Notizie |
| `de_all_services` | `all-services` | Voce di menu che punta alla sezione Servizi |
| `de_live` | `live` | Voce di menu che punta alla sezione Novità/Live |
| `de_faq` | `faq` | Voce di menu che punta alla pagina FAQ |
| `de_report_inefficiency` | `report-inefficiency` | Voce di menu che punta alla pagina di segnalazione disservizi |
| `de_accessibility_link` | `accessibility-link` | Voce di menu che punta alla Dichiarazione di Accessibilità |
| `de_privacy_policy_link` | `privacy-policy-link` | Voce di menu che punta alla Privacy Policy |
| `de_all_topics` | `all-topics` | Voce di menu che punta alla lista Argomenti/Tag |

> TODO – inserire screenshot della sezione "Valutazione Comuni" nel backend del template con i dropdown compilati

### Come configurare il mapping

1. Prima crea tutte le pagine necessarie come articoli Joomla e creane le voci di menu
2. Poi vai in **Estensioni → Template → Stili** → scheda **Valutazione Comuni**
3. Per ogni campo, seleziona la voce di menu corrispondente dal dropdown
4. Salva

> TODO – inserire screenshot di un singolo dropdown con la lista delle voci di menu disponibili

---

## Widget feedback chiarezza (C.SI.2.5 / C.SI.2.6)

Il widget di valutazione della chiarezza della pagina appare in fondo agli articoli (layout `default` e `scheda-servizio`).

### Come abilitarlo

Nel backend del template → scheda **Valutazione Comuni** → campo **Mostra widget valutazione**: imposta su **Sì**.

### Come funziona

Il widget si attiva solo sulle pagine articolo (`option=com_content&view=article`). Presenta un flusso a 3 step:

1. **Valutazione stellare** (1-5 stelle)
2. **Domanda di follow-up**:
   - Se il voto è 4-5: "Quali sono stati gli aspetti più utili di questa pagina?" (risposta positiva)
   - Se il voto è 1-3: "Come potremmo migliorare questa pagina?" (risposta negativa)
3. **Campo testo libero** per dettagli aggiuntivi (max 200 caratteri)
4. **Schermata di ringraziamento**

Il widget è accessibile da tastiera e non invia dati a nessun server (demo/DOM-only nella versione attuale).

> TODO – inserire screenshot del widget in ognuno dei 3 step

### data-element emessi dal widget

| Elemento | data-element |
|----------|-------------|
| Contenitore feedback | `feedback` |
| Titolo | `feedback-title` |
| Stella 1 | `feedback-rate-1` |
| Stella 2 | `feedback-rate-2` |
| Stella 3 | `feedback-rate-3` |
| Stella 4 | `feedback-rate-4` |
| Stella 5 | `feedback-rate-5` |
| Risposta positiva | `feedback-rating-positive` |
| Risposta negativa | `feedback-rating-negative` |
| Domanda di follow-up | `feedback-rating-question` |
| Risposta selezionata | `feedback-rating-answer` |
| Campo testo libero | `feedback-input-text` |

---

## Criteri automatici (non richiedono configurazione)

Questi `data-element` sono già gestiti dai layout del template e non richiedono azioni aggiuntive:

| Criterio | data-element | Dove è emesso |
|---------|-------------|--------------|
| C.SI.1.3 — Scheda Servizio | tutti i `service-*` | Layout `scheda-servizio` |
| C.SI.2.5/2.6 — Feedback | tutti i `feedback-*` | Widget `feedback-chiarezza` |
| C.SI.3.4 — Note Legali | `legal-notes` | Layout `note-legali` |
| Navigazione principale | `main-navigation` | Header, navbar |
| Area personale login | `personal-area-login` | Header, bottone login |
| Indice pagina articolo | `page-index` | Layout articolo `default` |
| Link servizi | `service-link` | Layout categoria `servizi` |
| Link categorie servizi | `service-category-link` | Layout categoria `servizi` |
| Breadcrumb | `breadcrumb` | Modulo `mod_breadcrumbs` + layout `scheda-servizio` |
| Metatag JSON-LD | `metatag` | Layout `scheda-servizio` |

---

## Criteri fuori scope del template

Alcuni criteri del Modello Comuni non possono essere gestiti dal template:

| Criterio | Motivo |
|---------|--------|
| C.SI.5.1 HTTPS | Configurazione hosting/server |
| C.SI.5.2 dominio `comune.*` | Configurazione DNS |
| C.SI.2.1 prenotazione appuntamenti | Richiede un componente Joomla dedicato |
| C.SI.3.1 / C.SE.3.1 cookie banner | Plugin di consenso esterno |
| C.SE.* (SPID, CIE, PagoPA, AppIO) | Richiedono integrazioni esterne |
