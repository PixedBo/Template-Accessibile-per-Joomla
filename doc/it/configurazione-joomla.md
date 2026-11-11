# Configurazione del template

Questa guida spiega come accedere alle impostazioni del template e cosa fa ogni parametro.

---

## Come accedere alle impostazioni

1. Dal backend di Joomla, vai su **Estensioni → Template → Stili**
2. Nella lista, trova la riga del **Template Accessibile per Joomla** e clicca sul suo nome (non sull'icona della stellina, ma sul nome testuale)
3. Si aprirà il pannello di configurazione con tutte le schede (tab) disponibili

> *Screenshot — Schermata "Stili template" con la riga del template evidenziata e il nome cliccabile*

Le impostazioni sono organizzate nelle schede seguenti:

- **Generale** — branding e colore primario
- **Opzioni Header** — login e ricerca
- **Footer** — logo UE
- **Contatta** — blocco di contatto sopra il footer
- **Social** — link ai canali social
- **Valutazione Comuni** — mapping data-element e widget feedback *(solo Modello Comuni)*

---

## Scheda: Generale

Questa scheda contiene le impostazioni di identità visiva del sito. È la prima da compilare quando si configura un nuovo sito.

> *Screenshot — Scheda "Generale" del template con tutti i campi visibili*

### Sezione: Logo

| Campo | Tipo | Descrizione |
|-------|------|-------------|
| Logo del Comune | Media | L'immagine del logo visualizzata nell'header. |
| Favicon SVG | Media | L'icona mostrata nella scheda del browser, in formato SVG. |
| Favicon PNG | Media | Fallback dell'icona per dispositivi Apple (180×180 pixel). |

**Come caricare il logo:**
1. Clicca sul campo **Logo del Comune** — si aprirà il Media Manager di Joomla
2. Seleziona l'immagine già caricata, oppure usa il pulsante **Carica** per caricarla
3. Clicca **Inserisci media** per confermare
4. Ripeti per Favicon SVG e Favicon PNG se disponibili

**Formato consigliato per il logo:** SVG. Un file SVG è vettoriale — rimane nitido a qualsiasi dimensione e su qualsiasi schermo, anche ad alta densità di pixel (Retina). Se non hai un SVG, usa un PNG trasparente ad alta risoluzione.

**Favicon:** il browser moderno usa il formato SVG (scalabile); se hai anche il PNG, Joomla lo imposta automaticamente come `apple-touch-icon` per iOS. Puoi caricare solo il PNG se non hai l'SVG — funziona comunque.

> *Screenshot — Media Manager di Joomla con un logo caricato, pronto per essere selezionato*

### Sezione: Identità

| Campo | Tipo | Descrizione |
|-------|------|-------------|
| Nome del Comune | Testo | Il nome dell'ente (es. `Comune di Milano`). Appare nell'header e nei metadati JSON-LD delle schede servizio. |
| Nome della Regione | Testo | Il nome della regione o ente sovraordinato (es. `Regione Lombardia`). Appare nella banda slim superiore. |
| URL Regione | URL | Il link al sito della regione. Se compilato, il nome della regione diventa cliccabile. |
| Payoff | Testo | Il sottotitolo sotto il nome del comune (es. `Città Metropolitana`). |

**Importante:** il campo **Nome del Comune** viene usato anche come `areaServed` nel blocco JSON-LD delle Schede Servizio. Compila sempre questo campo con il nome ufficiale e completo dell'ente.

> *Screenshot — Header del sito con nome comune, nome regione e payoff indicati con frecce*

### Sezione: Colore primario

| Campo | Tipo | Descrizione |
|-------|------|-------------|
| Colore primario del sito | Lista | Seleziona la palette colore tra le 5 disponibili. |

Vedi la guida dedicata: [colori-e-temi.md](colori-e-temi.md).

---

## Scheda: Opzioni Header

Questa scheda controlla i bottoni di login e ricerca che appaiono nell'header.

> *Screenshot — Scheda "Opzioni Header" con i campi login e ricerca visibili*

### Sezione: Login

| Campo | Tipo | Appare quando | Descrizione |
|-------|------|---------------|-------------|
| Mostra bottone login | Radio (Sì/No) | sempre | Abilita o disabilita il bottone "Accedi" nell'header. Default: No. |
| Tipo di login | Lista | solo se Sì | `standard` = apre il form login nativo di Joomla; `custom` = rimanda a una pagina personalizzata. |
| Voce di menu login | Menu item | solo se tipo = custom | Seleziona la voce di menu della pagina di login. |

**Come funziona:**
- Se scegli **standard**, il bottone aprirà direttamente il form di login di Joomla (componente `com_users`). Non richiede altre configurazioni.
- Se scegli **custom**, devi avere già creato una voce di menu che porta alla tua pagina di login (es. una pagina con un modulo di login personalizzato) e selezionarla nel campo **Voce di menu login**.

> *Screenshot — Header con il bottone "Accedi all'area personale" visibile nell'angolo superiore destro*

---

## Scheda: Ricerca

Dalla 1.3.0 la ricerca ha una scheda propria. I tre campi che prima stavano in **Opzioni Header** (`mostra_ricerca`, `tipo_ricerca`, `menuitem_ricerca`) sono stati spostati qui senza cambiare nome: i valori già salvati restano validi.

La ricerca usa il **Motore di ricerca** di Joomla (*Smart Search*, `com_finder`): il template ne ridisegna la pagina dei risultati e aggiunge un riquadro di ricerca a tutta pagina. Cosa viene trovato e quali voci compaiono nei filtri dipende dall'indice di Joomla, non dal template.

> La guida completa, con i prerequisiti di Joomla, il funzionamento dei filtri, la manutenzione dell'indice e la risoluzione dei problemi, è in **[Ricerca nel sito](ricerca.md)**. Qui sotto c'è solo l'elenco dei campi.

| Campo | Parametro | Predefinito | Descrizione |
|-------|-----------|-------------|-------------|
| Mostra pulsante Ricerca | `mostra_ricerca` | No | Mostra l'icona di ricerca nella testata. |
| Al clic sull'icona | `ricerca_apertura` | Apri la pagina | `pagina` = apre la pagina di ricerca; `modale` = apre il riquadro a tutta pagina con campo, pulsante "Cerca" e link "Ricerca avanzata". |
| Pagina di ricerca | `tipo_ricerca` | Smart Search standard | `smartsearch` = pagina dei risultati del Motore di ricerca; `menuitem` = una voce di menu. |
| Seleziona menu per ricerca | `menuitem_ricerca` | — | La voce di menu della pagina di ricerca. Appare solo con `menuitem`. |
| Suggerimenti durante la digitazione | `ricerca_suggerimenti` | No | Propone i termini mentre si scrive. Richiede anche l'opzione "Suggerimenti di ricerca" del Motore di ricerca. |
| Mostra i filtri | `ricerca_filtri` | Sì | Colonna dei filtri a sinistra dei risultati. |
| Filtro per tipologia | `ricerca_filtri_tipo` | Sì | Gruppo "Tipologie" (articoli, categorie, tag…). |
| Filtro per categoria | `ricerca_filtri_cat` | Sì | Gruppo "Categorie". |
| Categorie da mostrare | `ricerca_filtri_categorie` | vuoto (tutte) | Selezione multipla. Scegliendo una categoria compaiono anche le sue sottocategorie. |
| Selezione inversa | `ricerca_filtri_categorie_inverti` | No | Le categorie scelte vengono nascoste e si mostrano tutte le altre. |

---

## Scheda: Footer

Questa scheda contiene un solo parametro.

| Campo | Tipo | Descrizione |
|-------|------|-------------|
| Mostra logo UE | Radio (Sì/No) | Mostra il logo dell'Unione Europea nel footer. Default: Sì. |

Il logo UE viene mostrato automaticamente nella parte bassa del footer, a sinistra. Impostalo su **No** solo se il tuo ente non è co-finanziato da fondi UE.

---

## Scheda: Contatta

Questa scheda configura il blocco **"Contatta il Comune"** che appare tra il contenuto principale e il footer. Il blocco si attiva automaticamente quando almeno uno dei campi qui sotto è compilato — se tutti sono vuoti, il blocco non viene mostrato.

> *Screenshot — Blocco "Contatta il Comune" sul sito, con i link FAQ, Contatti, telefono, prenotazione e segnalazione visibili*

| Campo | Tipo | Descrizione |
|-------|------|-------------|
| FAQ | Voce di menu | Crea un link "Leggi le FAQ" |
| Contatti | Voce di menu | Crea un link "Contatti" con `data-element="contacts"` |
| Telefono | Testo | Il numero di telefono dell'ente (es. `800 123 456`). Viene trasformato in un link cliccabile `tel:`. |
| Prenota un appuntamento | Voce di menu | Crea un link "Prenota un appuntamento" con `data-element="appointment-booking"` |
| Segnala un disservizio | Voce di menu | Crea un link "Segnala un disservizio" |

**Come configurare:**
1. Prima crea le pagine necessarie come articoli Joomla (es. una pagina FAQ, una pagina Contatti) e le relative voci di menu
2. Torna su **Estensioni → Template → Stili → scheda Contatta**
3. Per ogni campo, clicca sul selettore e scegli la voce di menu corrispondente dal popup
4. Per il telefono, digita solo il numero (puoi includere spazi per leggibilità: `800 123 456`)
5. Salva

**Nota:** I campi `data-element="contacts"` e `data-element="appointment-booking"` sono richiesti dall'App Valutazione Modelli per i siti Comuni. Vedi [valutazione-modelli.md](valutazione-modelli.md).

---

## Scheda: Social

Ogni campo accetta un URL completo. Se un campo è lasciato vuoto, l'icona social corrispondente non viene mostrata — né nell'header né nel footer.

| Campo | Tipo | Esempio |
|-------|------|---------|
| X (Twitter) | URL | `https://x.com/comune_milano` |
| Facebook | URL | `https://www.facebook.com/comunemilano` |
| YouTube | URL | `https://www.youtube.com/channel/...` |
| Telegram | URL | `https://t.me/comunemilano` |
| WhatsApp | Testo | Solo il numero, es. `+39 02 12345678` |
| LinkedIn | URL | `https://www.linkedin.com/company/...` |

**Nota sul campo WhatsApp:** inserisci solo il numero di telefono (con prefisso internazionale, es. `+39 02 12345678`). Il template genera automaticamente il link `https://wa.me/390212345678` rimuovendo simboli e spazi. Non inserire un URL: inserisci solo il numero.

I social configurati qui appaiono in due posti: nell'header (icone piccole accanto al logo) e nel footer (sezione "Seguici su:"). Non esiste una configurazione separata per header e footer — stesso set di valori.

> *Screenshot — Sezione social nell'header con le icone visibili*

---

## Scheda: Valutazione Comuni

> **Solo Modello Comuni** — Questa scheda è necessaria solo per i siti web dei Comuni italiani.

Questa scheda serve a collegare le voci di menu del tuo sito ai `data-element` richiesti dall'App Valutazione Modelli, e ad abilitare il widget di feedback. Per la guida completa, vedi [valutazione-modelli.md](valutazione-modelli.md).
