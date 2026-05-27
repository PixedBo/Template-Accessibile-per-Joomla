# Indice della documentazione

Questo file è la mappa di tutta la documentazione disponibile per il **Template Accessibile per Joomla**.

---

## Come usare questa documentazione

Il template nasce per la **Pubblica Amministrazione italiana** (Comuni, Scuole, ASL, Musei, ecc.) ed è costruito attorno a [Bootstrap Italia](https://italia.github.io/bootstrap-italia/) e alle linee guida WCAG 2.1 AA. Grazie al forte focus sull'accessibilità e sulla qualità del codice, è però adatto a qualsiasi tipo di sito che voglia seguire gli stessi standard — anche al di fuori della PA.

Alcune funzionalità sono specifiche per il **Modello Comuni** (le linee guida Designers Italia per i siti dei Comuni italiani): la documentazione le tiene separate per chiarezza.

La documentazione è divisa in due sezioni:

1. **Template Universale** — configurazioni e funzionalità valide per qualsiasi tipo di sito
2. **Modello Comuni** — configurazioni aggiuntive specifiche per i siti dei Comuni italiani

> Se stai configurando un sito per un Comune, leggi entrambe le sezioni nell'ordine indicato. Per qualsiasi altro tipo di sito, la sezione universale è sufficiente.

---

## Sezione 1 — Template Universale

Queste guide si applicano a **qualsiasi sito** che usa questo template, indipendentemente dal tipo di ente o organizzazione.

### Configurazione iniziale

| Guida | Cosa impara |
|-------|-------------|
| [Configurazione del template](configurazione-joomla.md) | Come accedere alle impostazioni del template, configurare logo, colori, header, footer e social |
| [Colori e temi](colori-e-temi.md) | Le 5 palette colore preimpostate e come cambiarle |

### Struttura del sito

| Guida | Cosa impara |
|-------|-------------|
| [Topbar (banda slim)](topbar.md) | La banda sottile in cima con nome regione e cambio lingua |
| [Header](header.md) | Logo, nome ente, payoff, social, bottoni login e ricerca |
| [Menu di navigazione](menu.md) | Come creare i menu principale e secondario |
| [Posizioni modulo](moduli.md) | Dove si può posizionare ogni modulo nel layout della pagina |
| [Footer](footer.md) | Le due colonne del footer, logo UE, social, blocco "Contatta" |

### Gestione dei contenuti

| Guida | Cosa impara |
|-------|-------------|
| [Inserimento articoli](inserimento-articoli.md) | Come inserire articoli e selezionare layout alternativi |
| [Pagina Note Legali](note-legali.md) | Come creare la pagina obbligatoria con licenza CC-BY 4.0 |

---

## Sezione 2 — Modello Comuni

> Le guide in questa sezione riguardano esclusivamente i siti web dei **Comuni italiani** conformi al Modello Comuni (Designers Italia). Richiedono la configurazione della sezione universale come prerequisito.

### Struttura dei contenuti

| Guida | Cosa impara |
|-------|-------------|
| [Alberatura delle categorie](alberatura-categorie.md) | La struttura di categorie richiesta dal Modello Comuni con i nomi esatti |
| [Custom field per gli articoli](custom-field-articoli.md) | Come creare e configurare tutti i campi personalizzati per la Scheda Servizio |

### Layout specializzati per categoria

| Guida | Cosa impara |
|-------|-------------|
| [Layout Amministrazione](layout-amministrazione.md) | La categoria Amministrazione con sezione "In evidenza" e sottocategorie |
| [Layout Notizie](layout-notizie.md) | La categoria Notizie con articoli "In evidenza" in cima |
| [Layout Servizi](layout-servizi.md) | La categoria Servizi con ricerca, lista e sottocategorie |
| [Layout Vivere il Comune (articoli)](layout-vivere.md) | La pagina "Vivere il Comune" con eventi e luoghi gestiti come articoli Joomla — senza calendario/mappe. Vedi anche: [Integrazione DPCalendar](integrazione-DPCalendar.md) per la versione avanzata |

### Layout specializzati per articolo

| Guida | Cosa impara |
|-------|-------------|
| [Scheda Servizio](scheda-servizio.md) | Il layout per descrivere un servizio comunale (C.SI.1.3) |

### Integrazioni con componenti di terze parti

| Guida | Cosa impara |
|-------|-------------|
| [Integrazione DPCalendar](integrazione-DPCalendar.md) | Come usare DPCalendar (free) per la sezione eventi: pagina "Vivere il Comune" con dati reali, lista eventi con carousel e filtro, scheda singolo evento con mappa e prenotazione |

### Verifica e conformità

| Guida | Cosa impara |
|-------|-------------|
| [Valutazione Modelli Comuni](valutazione-modelli.md) | Come configurare i mapping data-element e il widget di feedback |

---

## ⚡ Quickstart

Per saltare la configurazione manuale e avere subito un sito di esempio funzionante, è disponibile un pacchetto di backup Akeeba pronto all'installazione:

| Risorsa | Descrizione |
|---------|-------------|
| [📦 Pacchetto Quickstart](../quickstart/quickstart-JoomlaPA-Akeeba.jpa) | Backup Akeeba `.jpa` con Joomla + template già configurato |
| [📖 Istruzioni di installazione](../quickstart/istruzioni.md) | Guida passo-passo all'installazione tramite Akeeba Kickstart |

---

## Percorso consigliato per un nuovo sito Comune

Se stai configurando un sito Comune da zero, segui questo ordine:

1. [Configurazione del template](configurazione-joomla.md) — logo, colore, identità
2. [Colori e temi](colori-e-temi.md) — scegli la palette
3. [Topbar](topbar.md) + [Header](header.md) — aspetto della testata
4. [Menu di navigazione](menu.md) — crea il menu principale
5. [Posizioni modulo](moduli.md) — capire dove metti i moduli
6. [Footer](footer.md) — configura il piè di pagina
7. [Alberatura delle categorie](alberatura-categorie.md) — crea la struttura delle categorie
8. [Custom field per gli articoli](custom-field-articoli.md) — crea i campi per le schede servizio
9. [Layout Servizi](layout-servizi.md) + [Layout Notizie](layout-notizie.md) + [Layout Amministrazione](layout-amministrazione.md) + [Layout Vivere il Comune](layout-vivere.md)
10. [Scheda Servizio](scheda-servizio.md) — inserisci i servizi
11. [Pagina Note Legali](note-legali.md) — crea la pagina obbligatoria
12. [Valutazione Modelli Comuni](valutazione-modelli.md) — collega i menu item ai data-element
