# Scheda Evento (DPCalendar)

> **Modello Comuni** — Questa guida è specifica per i siti web dei Comuni italiani. Richiede il componente DPCalendar: vedi [Integrazione DPCalendar](integrazione-DPCalendar.md).

La Scheda Evento è la pagina del singolo evento di DPCalendar. Dalla versione 1.3.0 segue la tipologia "evento" del Modello Comuni: sezioni in ordine fisso e indice laterale che elenca solo le sezioni presenti.

Non serve nessuna configurazione per usarla. Le sezioni aggiuntive (A chi è rivolto, Costi, Allegati, Contatti, Patrocinio, Sponsor) compaiono solo se crei i campi e li associ, come spiegato sotto.

---

## Le sezioni, in ordine

| Sezione | Da dove arriva il contenuto | Campo da creare |
|---|---|---|
| **Cos'è** | Descrizione dell'evento in DPCalendar | No |
| ↳ Galleria di immagini | Campo personalizzato | Sì |
| ↳ Video | Campo personalizzato | Sì |
| **A chi è rivolto** | Campo personalizzato | Sì |
| **Luogo** | Luoghi dell'evento in DPCalendar, con mappa | No |
| **Date e orari** | Date dell'evento, ricorrenza, pulsante "Aggiungi al calendario" | No |
| **Costi** | Prezzi di DPCalendar (se presenti), poi campo personalizzato | Facoltativo |
| **Prenotazione** | Prenotazioni di DPCalendar | No |
| **Allegati** | Campo personalizzato | Sì |
| **Contatti** | Campo personalizzato; "Con il supporto di" dagli organizzatori di DPCalendar; "A cura di" dall'autore | Facoltativo |
| *Campi non associati* | Ogni altro campo personalizzato dell'evento, una sezione ciascuno | — |
| **Ulteriori informazioni** | Patrocinato da, Sponsor, testo libero (campi personalizzati); link esterno, serie e biglietti da DPCalendar | Facoltativo |

Una sezione senza contenuto non viene mostrata e non compare nell'indice.

---

## Passo 1 — Crea i campi in DPCalendar

Vai su **Componenti → DPCalendar → Campi** e crea i campi che ti servono. Il nome è libero: sarai tu ad associarlo alla sezione nel passo 2.

| Sezione | Tipo di campo | Sotto-campi (nell'ordine) |
|---|---|---|
| A chi è rivolto | Editor o Area di testo | — |
| Galleria di immagini | Subform ripetibile | 1. immagine (Media) |
| Video | URL o Testo, con l'indirizzo di un video YouTube o Vimeo | — |
| Costi | Subform ripetibile | 1. titolo (Testo) · 2. prezzo (Testo) · 3. descrizione (Area di testo) |
| Allegati | Subform ripetibile | 1. nome (Testo) · 2. file (URL; su Joomla 6.1 o superiore anche Media di tipo Documento) |
| Contatti | Subform ripetibile | 1. nome (Testo) · 2. telefono (Testo) · 3. email (Testo) |
| Patrocinato da | Subform ripetibile | 1. nome dell'ente (Testo) · 2. indirizzo web (URL, facoltativo) |
| Sponsor | Subform ripetibile | 1. nome (Testo) · 2. indirizzo web (URL, facoltativo) |
| Ulteriori informazioni | Editor o Area di testo | — |

Per i campi **Subform**:

1. Crea prima i sotto-campi, uno per volta, con l'opzione che li riserva all'uso nei Subform attiva.
2. Crea poi il campo di tipo Subform (nell'interfaccia in italiano il tipo si chiama **Sottocampo (subform)**), imposta **Ripetibile** su **Sì** e aggiungi i sotto-campi **nell'ordine della tabella**.

La procedura è la stessa descritta per gli articoli in [custom-field-articoli.md](custom-field-articoli.md), ma i campi vanno creati dentro DPCalendar.

Allegati, Patrocinato da e Sponsor hanno la stessa struttura (nome + indirizzo): puoi riusare gli stessi due sotto-campi nei tre Subform.

Casi particolari:

- **Galleria**: il testo alternativo che scrivi per ogni immagine viene usato anche come didascalia.
- **Allegati**: se ti basta un solo file, puoi usare un singolo campo URL (o Documento) al posto del Subform.
- **Costi**: se preferisci scrivere un testo libero, usa un campo Editor al posto del Subform.

---

## Passo 2 — Associa i campi nel template

Vai su **Sistema → Stili template sito**, clicca sul nome del template e apri la scheda **Scheda Evento**.

Per ogni sezione scegli dall'elenco il campo che hai creato. Lascia "Non presente" le sezioni che non usi.

**Sotto-campi.** Il template legge i sotto-campi di un Subform per posizione: il primo è il titolo (o il nome), il secondo il prezzo (o l'indirizzo), e così via. Se hai rispettato l'ordine del passo 1 lascia tutte le voci "sotto-campo" su **Automatico**. Se i tuoi sotto-campi sono in un ordine diverso, indica esplicitamente quale sotto-campo contiene quale dato.

**Campi non associati.** I campi personalizzati dell'evento che non associ a nessuna sezione vengono mostrati ciascuno come sezione a sé, con il proprio titolo. Per non mostrarli scegli **Non mostrare**.

---

## Passo 3 — Compila l'evento

Apri l'evento in DPCalendar e compila i campi: li trovi nelle schede dei gruppi di campi a cui li hai assegnati.

Cosa arriva da DPCalendar senza campi:

- **Luogo e mappa**: i luoghi collegati all'evento.
- **Organizzatori**: compaiono in "Contatti" sotto "Con il supporto di".
- **Prezzi**: se l'evento ha prezzi di DPCalendar, compaiono per primi in "Costi".
- **Immagine**: l'immagine completa dell'evento viene mostrata a tutta larghezza sotto l'intestazione.
- **Argomenti**: i tag dell'evento compaiono nell'intestazione.

Le opzioni di visibilità di DPCalendar (mostra mappa, organizzatori, autore, stampa, esporta calendario, data di modifica) continuano a valere.

---

## Aggiornamento da una versione precedente

- Gli eventi esistenti continuano a funzionare senza modifiche: i campi personalizzati già compilati restano visibili come sezioni a sé finché non li associ.
- Cambia l'ordine delle sezioni e l'intestazione: "Salva evento" ora è il pulsante **Aggiungi al calendario** in fondo a "Date e orari"; gli organizzatori sono in "Contatti".
- Gli indirizzi diretti alle sezioni già pubblicati (`#descrizione`, `#date-orari`, `#luogo`, `#prenotazione`, `#informazioni`) restano validi.
- Se hai un override personale della scheda evento in un template figlio, continua a essere usato il tuo file e non vedrai nessuna di queste novità.
