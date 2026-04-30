# Template Accessibile per Joomla 5+ (Modello Universale / Bootstrap Italia)

> ## ⚠️ IL TEMPLATE NON È PRONTO PER SITI DI PRODUZIONE
>
> Questo è un progetto **WORK IN PROGRESS**. Al momento il template **non supera ancora** i controlli dei validatori del Dipartimento per la Trasformazione Digitale — vedi [App di valutazione per i siti di Comuni e Scuole, pubblicata la versione 2.0](https://innovazione.gov.it/notizie/articoli/app-di-valutazione-per-i-siti-di-comuni-e-scuole-pubblicata-la-versione-2-0/).
>
> Chiunque può **scaricarlo, testarlo, provarlo e contribuire**: segnalazioni, PR e feedback sono benvenuti. Non utilizzarlo però come base di un sito istituzionale reale finché la conformità ai validatori ufficiali non sarà completa.

## 📄 Descrizione
Questo è il template pensato per integrare il **Modello Universale** e il design system di **Designers Italia** nativamente su **Joomla 5+**.
Progettato senza l'ausilio di framework esterni pesanti (zero jQuery, puro CSS vanilla e Javascript nativo), punta a garantire un'esperienza utente **altamente accessibile (WCAG 2.1 AA/AAA)**, performante e a prova di futuri aggiornamenti.

La struttura si basa su **Bootstrap Italia 2.9.0** e sfrutta i moderni *Web Asset Manager* e i namespace nativi di Joomla 5.

## 🛠 Requisiti di sistema
- **Joomla!**: 5.0.0 o superiore
- **PHP**: 8.2.0 o superiore

## 🚀 Installazione
L'installazione è la classica procedura standard di Joomla. Nessuna riga di codice richiesta.

1. Vai nella pagina [Releases](https://github.com/PixedBo/Template-Accessibile-per-Joomla/releases) di questo repository.
2. Scarica l'ultima versione del pacchetto di installazione (es. `tpl_accessibile_vX.X.X.zip`).
3. Accedi al backend del tuo sito Joomla.
4. Naviga in **Sistema** > **Installa** > **Estensioni**.
5. Trascina il file `.zip` scaricato nell'area di caricamento.
6. Vai su **Sistema** > **Stili Template (Sito)** e imposta "Template Accessibile" come predefinito (cliccando sulla stellina).

---

## ⚙️ Configurazione dal Backend (Opzioni del Template)
Il template è progettato per essere "chiavi in mano". Cliccando sul nome del template in *Stili Template*, avrai accesso a un pannello di controllo dove poter personalizzare il sito senza toccare il codice.

### 🎨 Generale / Branding
- **Logo del Comune:** Carica il logotipo istituzionale in formato SVG o PNG.
- **Nome del Comune & Sottotitolo:** Gestisci i testi della testata principale (es. "Comune di Bugliano" - "Un comune da vivere").
- **Ente Superiore:** Inserisci il nome e il link della Regione o dell'ente di appartenenza (appare in cima alla pagina).
- **Colore Primario:** Scegli tra 5 temi cromatici accessibili e validati da AgID. Il sito adatterà automaticamente bottoni, sfondi, hover e icone:
  - Blu istituzionale (Default)
  - Verde (Modello Comuni)
  - Rosso (Modello Scuole)
  - Verde Acqua (Modello ASL / Sanità)
  - Viola (Modello Musei)

### 🧩 Opzioni Header (Testata)
- **Area Personale (Login):** Attiva/disattiva il pulsante di accesso. Puoi collegarlo al login nativo di Joomla o a una specifica voce di menu custom.
- **Motore di Ricerca:** Attiva/disattiva l'icona della lente di ingrandimento. Integrato nativamente con *Smart Search* di Joomla, oppure indirizzabile a una pagina specifica.

### 📱 Social Network
Inserisci i link ai canali social dell'ente. Le icone (X, Facebook, YouTube, Telegram, WhatsApp) appariranno automaticamente nell'intestazione e nel footer solo se il relativo campo è compilato.

---

## 📐 Posizioni Modulo disponibili
Il template dichiara le seguenti posizioni modulo native, studiate per rispecchiare la griglia di Bootstrap Italia:

- `selezione-lingua`: Menu a tendina per i siti multilingua (Header alto).
- `menu-principale`: Il menu di navigazione principale. **(N.B. Usare un modulo di tipo "Menu" e impostare il layout alternativo su `comuni-menu`)**.
- `menu-secondario`: I link di servizio (es. argomenti) posti a destra del menu principale. **(N.B. Usare un modulo di tipo "Menu" e impostare il layout alternativo su `comuni-menu`)**.
- `top`: Area a piena larghezza sotto l'header.
- `top-muted`: Area con sfondo grigio chiaro per link in evidenza o avvisi.
- `evidenza`: Sezione con sfondo dinamico (basato sul colore primario) per lo slider notizie/servizi.
- `calendario`: Area dedicata ai moduli eventi.
- `colonna-sinistra`: Sidebar per la navigazione secondaria e indici di pagina (Scrollspy).
- `colonna-destra`: Sidebar per moduli aggiuntivi o azioni rapide.
- `bottom`: Area a piena larghezza sopra il footer.
- `footer1` e `footer2`: Colonne per l'organizzazione dei link nel pié di pagina istituzionale.

---

## 💻 Personalizzazione Avanzata (CSS Custom)
Se hai bisogno di aggiungere regole CSS personalizzate per sovrascrivere lo stile nativo di Bootstrap Italia o del template, **non modificare i file originali**.

Ti basterà creare un file chiamato `custom.css` all'interno della cartella `css/` del template (il percorso sarà `/templates/NOME_TEMPLATE/css/custom.css`). 
Il sistema lo rileverà in automatico e lo caricherà per ultimo, garantendo che le tue regole abbiano la priorità assoluta e non vengano cancellate durante i futuri aggiornamenti del template.

---

## 🌟 Override e Layout Alternativi inclusi
Il template è dotato di override nativi per far sì che i componenti standard di Joomla generino codice HTML orientato alle linee guida di Designers Italia:

- **Moduli Articoli (`mod_articles`):** Sono presenti due layout specifici: un **layout a 3 colonne** e un **layout singolo**. Il layout singolo è dinamico: se all'interno del modulo è presente più di un articolo, il sistema crea automaticamente uno slideshow accessibile. I tag correlati vengono emessi come lista semantica (`<ul>/<li>`) di chip Bootstrap Italia, l'icona calendario usa lo sprite SVG interno e il testo introduttivo mantiene l'HTML originale (strip + troncamento con "…" solo oltre la soglia di 1000 caratteri plain-text).
- **Modulo Menu (`mod_menu`):** È incluso il layout `comuni-menu`, essenziale e obbligatorio per impaginare in modo corretto e accessibile sia il Menu Principale che il Menu Secondario all'interno della testata.
- **Modulo Breadcrumb (`mod_breadcrumbs`):** Override che emette il percorso con la struttura `cmp-breadcrumbs` / `breadcrumb-container` di Bootstrap Italia, microdata `schema.org/BreadcrumbList`, `aria-current="page"` sull'elemento attivo e `data-element="breadcrumb"` richiesto dall'App Valutazione Modelli.
- **Articolo Singolo (`com_content > article`):** Layout completo per servizi/notizie con calcolo automatico del tempo di lettura, impaginazione accessibile, tag a "chip" e pulsanti di condivisione social nativi. La barra di avanzamento lettura espone un `aria-label` dedicato per gli screen reader.
- **Layout alternativo "Note Legali" (`com_content > article > note-legali`):** Layout dedicato alla pagina Note Legali richiesta dal criterio C.SI.3.4 del Modello Comuni. Emette `data-element="legal-notes"` e append la sezione obbligatoria "Licenza dei contenuti" con il testo CC-BY 4.0 verbatim (non modificabile dal backend), preservando comunque il contenuto libero inserito dall'admin come introtext/fulltext dell'articolo.
- **Categoria Blog / Lista (`com_content > category`):** Override di `blog` e `default` con card in stile Bootstrap Italia.
- **Layout alternativo "Servizi" (`com_content > category > servizi`):** Layout dedicato alla categoria "Servizi" del Comune, conforme al Modello Comuni. Hero, elenco card servizi con `data-element="service-link"`, blocco "Esplora per argomento" con sottocategorie e `data-element="service-category-link"`. Selezionabile come *Alternative Layout* da una voce di menu di tipo Blog o Lista Categoria. Funziona indistintamente con entrambe le viste.

## 🏛️ Integrazione Modello PA (Designers Italia)
Gli attributi `data-element` richiesti dall'App Valutazione Modelli sono applicati **nei layout dedicati**, dove il contesto è quello corretto per il validator:

- **Layout "Servizi"** (`com_content > category > servizi`): ogni card servizio porta `data-element="service-link"` hardcoded.
- Futuri layout per Notizie, Eventi e Documenti seguiranno lo stesso pattern.

Il fieldset **Criteri valutazione – Comune** permette di associare **voci di menu** ai `data-element` "funzionali" che puntano a destinazioni uniche: `management`, `all-services`, `all-topics`, `live`, `faq`, `report-inefficiency`, `accessibility-link`, `privacy-policy-link`, `news`.

Nello stesso fieldset è presente il flag **Abilita sistema di feedback** (default: NO), che attiva o disattiva il widget "Valutazione chiarezza pagina" descritto qui sotto.

## 📝 Widget "Valutazione chiarezza pagina" (C.SI.2.5)
Il template include il blocco di valutazione chiarezza richiesto dal Modello Comuni: stelline 1-5, domanda di follow-up condizionale (aspetti preferiti se voto ≥ 4, difficoltà incontrate se voto ≤ 3), campo testuale di dettaglio e messaggio finale di ringraziamento. Sono emessi tutti i `data-element` richiesti dall'App Valutazione Modelli: `feedback`, `feedback-title`, `feedback-rate-1..5`, `feedback-rating-positive`/`negative`, `feedback-rating-question` (×2), `feedback-rating-answer` (×10), `feedback-input-text`.

Il widget viene renderizzato **solo sulle pagine del singolo articolo** (`com_content` view `article`), a piena larghezza sotto il `<main>`, con lo sfondo del colore primario del template. Lo script JS (`js/feedback-chiarezza.js`, vanilla ES6) è caricato condizionalmente solo sulle stesse pagine e solo quando il flag è attivo.

> ⚠️ **Attualmente il widget è puramente dimostrativo:** l'interfaccia è conforme al Modello Comuni, ma le risposte **non vengono ancora salvate da nessuna parte**. Sopra al widget compare un banner di avviso visibile all'utente. Abilita il flag solo se ti serve la struttura HTML per superare i controlli dell'App Valutazione Modelli, in attesa dell'integrazione con un sistema di raccolta feedback. L'attivazione si fa da *Stili Template → Template Accessibile → Criteri valutazione - Comune → Abilita sistema di feedback*.

## 🧪 Stato della conformità
- ✅ Menu principale con `data-element="main-navigation"` e voci mappabili.
- ✅ Pulsante login con `data-element="personal-area-login"`.
- ✅ Attributi `service-link`, `news-link`, `event-link`, `document-link` applicati automaticamente via mappa categorie.
- ✅ Layout "Servizi" con `service-link` + `service-category-link`.
- ✅ Indice articolo con `data-element="page-index"`, tag argomento con `data-element="topic-element"`.
- ✅ Breadcrumb con `data-element="breadcrumb"`, microdata `schema.org/BreadcrumbList` e `aria-current="page"`.
- ✅ Pagina Note Legali (C.SI.3.4) con `data-element="legal-notes"` e licenza CC-BY 4.0 verbatim.
- ⚠️ Widget "Valutazione chiarezza pagina" (C.SI.2.5): struttura HTML completa con tutti i `data-element` richiesti, **ma attualmente è un segnaposto dimostrativo** — le risposte non vengono ancora salvate. Attivabile dal flag "Abilita sistema di feedback" nel fieldset "Criteri valutazione – Comune".
- 🚧 Alcuni `data-element` richiesti dall'App Valutazione Modelli (es. strutture dettagliate per pagine Amministrazione/Uffici, Evento, Documento, scheda Servizio) **non sono ancora completi**. Per questo motivo il template **non passa al 100%** i controlli del Dipartimento per la Trasformazione Digitale — vedi [avviso in cima a questa pagina](#-il-template-non-è-pronto-per-siti-di-produzione).

## 🤝 Contribuire
Il progetto è aperto ai contributi della community:

- Apri una **issue** per segnalare bug, mancanze di conformità o proporre nuove funzionalità.
- Fai una **pull request** se hai sistemato qualcosa (benvenuti fix, nuovi override, nuovi layout dedicati per Amministrazione, Evento, Documento, ecc.).
- Testa il template installandolo su un ambiente di sviluppo e lancia l'[App di valutazione per i siti di Comuni e Scuole](https://innovazione.gov.it/notizie/articoli/app-di-valutazione-per-i-siti-di-comuni-e-scuole-pubblicata-la-versione-2-0/) per verificare quali criteri non passano ancora.

## 📌 Changelog

### 1.0.13 (2026-04-30)
- **Nuovo:** aggiunta la pagina LinkedIn.
- **Nuovo:** aggiunta la possibilità di settare i parametri delle voci di menu Servizi e Notizie.
- **Fix:** corretto il background del pulsante LOGIN.

### 1.0.12 (2026-04-30)
- **Nuovo:** prima release con aggiornamento automatico.

### 1.0.11 (2026-04-30)
- **Nuovo:** creazione del layout NOTIZIE per categoria blog.
- **Fix:** corretto il `data-element` sulla paginazione.
- **Rimosso:** rimosso `ModelloPAHelper`, non piu necessario.

### 1.0.10 (2026-04-21)
- **Nuovo:** override `mod_breadcrumbs` con microdata `schema.org/BreadcrumbList`, `aria-current="page"` e `data-element="breadcrumb"`; rimosse le ridondanze sul wrapper del breadcrumb in `index.php`.
- **Nuovo:** layout alternativo `com_content > article > note-legali` per la pagina Note Legali (C.SI.3.4) con `data-element="legal-notes"` e licenza CC-BY 4.0 verbatim.
- **Modulo evidenza singolo (`mod_articles`):** tag correlati emessi come lista semantica `<ul>/<li>` di chip Bootstrap Italia con adattamento al contenuto e testo centrato; icona calendario migrata allo sprite SVG interno; aggiunto `<h2 class="visually-hidden">` di sezione come landmark per screen reader; il testo introduttivo ora mantiene l'HTML originale e viene strippato/troncato solo oltre i 1000 caratteri plain-text; fix del namespace `TagsHelper` (era `\Joomla\CMS\Tag\TagsHelper`, inesistente in Joomla 5, ora `\Joomla\CMS\Helper\TagsHelper`) che impediva di recuperare i tag.
- **Articolo singolo:** aggiunto `aria-label` alla barra di avanzamento lettura per risolvere il warning Lighthouse "progressbar elements do not have accessible names".

## 📜 Licenza e Crediti
Questo template è rilasciato sotto licenza **GNU GPL v3**.  
Basato sulle risorse UI/UX di [Designers Italia](https://designers.italia.it/) e sul framework [Bootstrap Italia](https://italia.github.io/bootstrap-italia/).
