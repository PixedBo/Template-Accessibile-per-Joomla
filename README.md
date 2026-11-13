# Template Accessibile per Joomla 5.4+ e 6 (Modello Universale / Bootstrap Italia)

> **Il template supera la valutazione automatica dell'[App Valutazione Modelli](https://innovazione.gov.it/notizie/articoli/app-di-valutazione-per-i-siti-di-comuni-e-scuole-pubblicata-la-versione-2-0/) del Dipartimento per la Trasformazione Digitale** (Modello Comuni). Gli unici due errori rilevati riguardano requisiti indipendenti dal template: la mancanza di un dominio istituzionale (`comune.*.it`) e l'assenza di una dichiarazione di accessibilità, che devono essere predisposti dall'ente.
>
> Il template è ancora **in fase di sviluppo e test**: nuove funzionalità sono in lavorazione e potrebbero esserci modifiche anche strutturali tra una versione e l'altra. Chiunque può **scaricarlo, testarlo e contribuire**: segnalazioni, PR e feedback sono benvenuti.

---

## 📚 Documentazione completa

> **[→ Vai all'indice della documentazione](doc/it/indice.md)**
>
> La documentazione contiene tutto il necessario per **installare, configurare e gestire** il template: guida passo-passo all'installazione, configurazione del backend, struttura degli articoli, layout e override inclusi, conformità al Modello Comuni.

---

## 📄 Descrizione

Questo è il template pensato per integrare il **Modello Universale** e il design system di **Designers Italia** nativamente su **Joomla 5.4+ e Joomla 6**. Il template nasce per la **Pubblica Amministrazione italiana** (Comuni, Scuole, ASL, Musei), ma è adatto a qualsiasi sito che voglia seguire gli stessi standard di accessibilità.

Progettato senza framework pesanti (zero jQuery, CSS vanilla e JavaScript nativo), punta a garantire un'esperienza **altamente accessibile (WCAG 2.1 AA/AAA)**, performante e a prova di aggiornamenti. La struttura si basa su **Bootstrap Italia 2.18.1** e sfrutta i *Web Asset Manager* e i namespace nativi di Joomla.

## 🛠 Requisiti di sistema

- **Joomla!**: 5.4.0 o superiore (compatibile con Joomla 6)
- **PHP**: 8.1.0 o superiore

> **Joomla 5.4 e PHP 8.1 sono supportati fino a ottobre 2027**, quando Joomla 5 esce dal supporto. Dalla release successiva i requisiti minimi passeranno a **Joomla 6 e PHP 8.3**: chi resta su Joomla 5 continuerà a usare il template già installato, ma non riceverà più aggiornamenti.

## 🚀 Installazione

L'installazione segue la classica procedura standard di Joomla. Nessuna riga di codice richiesta.

1. Vai nella pagina [Releases](https://github.com/PixedBo/Template-Accessibile-per-Joomla/releases) di questo repository.
2. Scarica l'ultima versione del pacchetto di installazione (es. `tpl_templateaccessibileperjoomla_vX.X.X.zip`).
3. Accedi al backend del tuo sito Joomla.
4. Naviga in **Sistema** > **Installa** > **Estensioni**.
5. Trascina il file `.zip` scaricato nell'area di caricamento.
6. Vai su **Sistema** > **Stili Template (Sito)** e imposta "Template Accessibile" come predefinito (cliccando sulla stellina).

---

## ⚡ Quickstart

Per avere un sito di esempio già configurato senza partire da zero, è disponibile un pacchetto di backup Akeeba (`.jpa`) pronto all'installazione tramite Kickstart.

- **[📦 Scarica il pacchetto Quickstart](doc/quickstart/quickstart-JoomlaPA-Akeeba.jpa)**
- **[📖 Istruzioni di installazione](doc/quickstart/istruzioni.md)**

Se hai già un sito Joomla e ti serve solo la struttura per il **sito di un Comune** (categorie, campi personalizzati, menu, moduli, pagine istituzionali), installa il plugin `plg_sampledata_templateaccessibile` allegato alla release: aggiunge le sue voci al modulo "Dati di esempio" del pannello di controllo e può essere rilanciato senza creare duplicati.

- **[🧩 Guida ai dati di esempio](doc/it/dati-di-esempio.md)**

---

## ⚙️ Configurazione

Il template è "chiavi in mano": tutto si configura dal pannello **Stili Template → Template Accessibile** nel backend di Joomla, senza toccare il codice.

Le opzioni principali includono logo, nome ente, colore primario (5 temi cromatici accessibili), header, ricerca, social network, footer e blocco "Contatta", oltre alle schede per la mappatura dei campi della Scheda Servizio ("Valutazione - COMUNI") e della Scheda Evento DPCalendar ("Scheda Evento").

**→ Guida completa:** [`doc/it/configurazione-joomla.md`](doc/it/configurazione-joomla.md)  
**→ Colori e temi:** [`doc/it/colori-e-temi.md`](doc/it/colori-e-temi.md)

---

## 🔎 Ricerca

La ricerca si appoggia al **Motore di ricerca di Joomla** (Smart Search, `com_finder`) e si configura dalla scheda **Ricerca** dei parametri del template:

- l'icona nell'header apre la pagina dei risultati oppure un **modale a tutta pagina** con campo, pulsante "Cerca" e link "Ricerca avanzata" (senza JavaScript resta un link);
- la **pagina dei risultati** ha un campo di ricerca grande, risultati a card con la categoria sopra il titolo e **filtri a caselle** per tipologia e categoria, che restano attivi cambiando pagina o ordinamento;
- **suggerimenti durante la digitazione** opzionali, nel modale e nella pagina dei risultati.

Richiede Smart Search attivo, con i plugin abilitati e l'indice aggiornato. Non c'è ancora un filtro per argomenti (tag degli articoli), perché Joomla non li registra nell'indice.

**→ Guida completa:** [`doc/it/ricerca.md`](doc/it/ricerca.md)

---

## 📐 Posizioni Modulo

Il template dichiara le seguenti posizioni native:

`selezione-lingua` · `menu-principale` · `menu-secondario` · `avvisi` · `percorso` · `top` · `top-muted` · `evidenza` · `calendario` · `colonna-sinistra` · `colonna-destra` · `bottom` · `bottom2` · `footer1` · `footer2` · `cerca-servizi` · `debug`

I moduli menu dell'header (`menu-principale`, `menu-secondario`) devono usare il layout alternativo `comuni-menu`; i menu nelle posizioni `footer1` e `footer2` il layout `comuni-footer`.

**→ Guida completa con descrizione di ogni posizione:** [`doc/it/moduli.md`](doc/it/moduli.md)

---

## 💻 CSS Personalizzato

Per aggiungere stili CSS personalizzati senza modificare i file originali, crea il file `/media/templates/site/templateaccessibileperjoomla/css/custom.css` (se usi un template figlio, nella cartella `css/` del figlio sotto `/media/templates/site/`). Il sistema lo rileva automaticamente e lo carica per ultimo.

---

## 🌟 Override e Layout Alternativi inclusi

Il template include override per i principali componenti Joomla (articoli, menu, breadcrumb, categorie, ricerca) e layout alternativi specializzati per il Modello Comuni:

| Layout | Descrizione |
|--------|-------------|
| `com_content/article/default` | Articolo standard con tempo di lettura, indice di pagina, menu "Condividi" e "Vedi azioni", argomenti come chip |
| `com_content/article/note-legali` | Pagina Note Legali (C.SI.3.4) con sezione CC-BY 4.0 obbligatoria |
| `com_content/article/scheda-servizio` | Scheda Servizio comunale (C.SI.1.3) con JSON-LD e widget feedback |
| `com_content/category/notizie` | Categoria Notizie con articoli "In evidenza" in cima |
| `com_content/category/servizi` | Categoria Servizi con ricerca e sottocategorie |
| `com_content/category/amministrazione` | Categoria Amministrazione con sezione "In evidenza" |
| `com_content/category/vivere` | Pagina "Vivere il Comune" con eventi e luoghi |
| `mod_articles/evidenza-singolo` | Modulo articoli: un solo articolo, o carosello accessibile (con frecce e indicatori) se gli articoli sono più di uno |
| `mod_articles/evidenza-tre-colonne` | Modulo articoli: layout a 3 colonne card |
| `mod_menu/comuni-menu` | Layout menu obbligatorio per header Bootstrap Italia |
| `mod_menu/comuni-footer` | Layout menu per `footer1`/`footer2`: elenco di link con i `data-element` del footer (FAQ, segnalazione disservizio, accessibilità, privacy, note legali) |
| `mod_breadcrumbs/default` | Breadcrumb con microdata schema.org e `data-element` |
| `com_finder/search/default` | Pagina dei risultati di Smart Search con card e filtri per tipologia e categoria |
| `layouts/joomla/content/tags` | Tag delle viste categoria come chip nel colore primario del tema |
| `com_dpcalendar/calendar/vivere` | *(DPCalendar)* Pagina "Vivere il Comune" con eventi e luoghi da DPCalendar |
| `com_dpcalendar/list/eventi` | *(DPCalendar)* Lista eventi con carousel "In evidenza", filtro e paginazione |
| `com_dpcalendar/event/default` | *(DPCalendar)* Scheda Evento del Modello Comuni: indice di pagina, mappa, date e orari con "Aggiungi al calendario" (Google Calendar o `.ics`), prenotazione e sezioni da campi personalizzati (a chi è rivolto, galleria e video, costi, allegati, contatti, patrocinio, sponsor) |
| `mod_dpcalendar_upcoming/joomlaPA` | *(DPCalendar)* Modulo "prossimi eventi" con card Bootstrap Italia |

Articolo, Scheda Servizio e Scheda Evento condividono la stessa intestazione di pagina (menu "Condividi" e "Vedi azioni", argomenti come chip), definita una sola volta nei partial `html/layouts/accessibile/azioni-pagina.php` e `argomenti.php`.

**→ Guida all'inserimento articoli:** [`doc/it/inserimento-articoli.md`](doc/it/inserimento-articoli.md)  
**→ Layout Notizie:** [`doc/it/layout-notizie.md`](doc/it/layout-notizie.md)  
**→ Layout Servizi:** [`doc/it/layout-servizi.md`](doc/it/layout-servizi.md)  
**→ Layout Amministrazione:** [`doc/it/layout-amministrazione.md`](doc/it/layout-amministrazione.md)  
**→ Layout Vivere il Comune (articoli):** [`doc/it/layout-vivere.md`](doc/it/layout-vivere.md)  
**→ Scheda Servizio:** [`doc/it/scheda-servizio.md`](doc/it/scheda-servizio.md)  
**→ Footer e menu del footer:** [`doc/it/footer.md`](doc/it/footer.md)  
**→ Integrazione DPCalendar:** [`doc/it/integrazione-DPCalendar.md`](doc/it/integrazione-DPCalendar.md)  
**→ Scheda Evento (DPCalendar):** [`doc/it/scheda-evento.md`](doc/it/scheda-evento.md)  
**→ Ricerca:** [`doc/it/ricerca.md`](doc/it/ricerca.md)

---

## 🏛️ Conformità Modello Comuni (Designers Italia)

Il template **supera la valutazione automatica** dell'App Valutazione Modelli (Modello Comuni). Gli unici due errori segnalati dal validatore sono indipendenti dal template:

- **Dominio istituzionale** — il validatore richiede un dominio `comune.*.it`; non è un requisito del template.
- **Dichiarazione di accessibilità** — deve essere predisposta dall'ente sul proprio sito.

Il template implementa tutti i `data-element` richiesti:

- Mapping voci di menu → `data-element` funzionali (scheda "Valutazione Comuni")
- Widget "Valutazione chiarezza pagina" (C.SI.2.5/2.6) — struttura HTML completa; le risposte non vengono ancora inviate né salvate
- Layout Scheda Servizio (C.SI.1.3) con tutti i `data-element` e JSON-LD `GovernmentService`
- Pagina Note Legali (C.SI.3.4) con testo CC-BY 4.0 non modificabile

**→ Guida completa:** [`doc/it/valutazione-modelli.md`](doc/it/valutazione-modelli.md)  
**→ Percorso completo per un sito Comune:** [`doc/it/indice.md`](doc/it/indice.md)

## 🧪 Stato della conformità

- ✅ Menu principale con `data-element="main-navigation"` e login con `data-element="personal-area-login"`
- ✅ Breadcrumb con `data-element="breadcrumb"` e microdata schema.org
- ✅ Layout Servizi con `service-link` + `service-category-link`
- ✅ Pagina Note Legali (C.SI.3.4) con `data-element="legal-notes"` e licenza CC-BY 4.0
- ✅ Layout Scheda Servizio (C.SI.1.3) con tutti i `data-element` e JSON-LD
- ✅ Layout Notizie, Amministrazione, Vivere il Comune
- ✅ Widget feedback (C.SI.2.5/2.6) — struttura HTML completa con tutti i `data-element` richiesti
- ✅ Menu del footer con layout `comuni-footer` e `data-element` dedicati (FAQ, segnalazione disservizio, accessibilità, privacy, note legali)
- ✅ Integrazione DPCalendar: Scheda Evento con sezioni del Modello Comuni, mappa, prenotazione e "Aggiungi al calendario"; lista eventi con carousel e filtro
- 🚧 Layout specializzati per Ufficio, Documento, Luogo, Evento (articolo nativo), Persona, Notizia (articolo singolo) non ancora implementati

---

## 📸 Screenshot

**18 criteri superati su 20:**

![Test Modello Comuni passato](doc/img/test-comuni-passato.jpg)

**I due criteri non superati (dominio istituzionale e dichiarazione di accessibilità):**

![Criteri non superati](doc/img/criteri-non-superati.jpg)

---

## 🤝 Contribuire

Il progetto è aperto ai contributi della community:

- Apri una **issue** per segnalare bug, mancanze di conformità o proporre nuove funzionalità.
- Fai una **pull request** se hai sistemato qualcosa (fix, nuovi override, nuovi layout dedicati).
- Testa il template installandolo su un ambiente di sviluppo e lancia l'[App di valutazione per i siti di Comuni e Scuole](https://innovazione.gov.it/notizie/articoli/app-di-valutazione-per-i-siti-di-comuni-e-scuole-pubblicata-la-versione-2-0/) per verificare quali criteri non passano ancora.

## 📜 Licenza e Crediti

Questo template è rilasciato sotto licenza **GNU GPL v3**.  
Basato sulle risorse UI/UX di [Designers Italia](https://designers.italia.it/) e sul framework [Bootstrap Italia](https://italia.github.io/bootstrap-italia/).
