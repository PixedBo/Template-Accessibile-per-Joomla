# Inserimento articoli

Gli articoli in Joomla si creano da **Contenuti → Articoli → Nuovo**. Il template fornisce layout alternativi specifici per alcuni tipi di contenuto.

---

## Articolo standard

Per contenuti generici (pagine istituzionali, comunicati, ecc.) non è necessario selezionare alcun layout alternativo. Il template applica automaticamente l'override `default.php` che include:

- Calcolo e visualizzazione del tempo di lettura stimato
- Indice di pagina con scrollspy (se l'articolo ha sottotitoli con ID)
- Pulsanti di condivisione social (Facebook, X, LinkedIn, WhatsApp)
- Pulsanti stampa e invia via email
- Widget feedback chiarezza (se abilitato nei parametri del template)
- Paginazione accessibile tra articoli della stessa categoria

> TODO – inserire screenshot di un articolo standard renderizzato con il template

---

## Come selezionare un layout alternativo

1. Vai su **Contenuti → Articoli → Modifica** (o Nuovo)
2. Nella scheda **Opzioni** del singolo articolo
3. Campo **Layout alternativo**: seleziona il layout desiderato

> TODO – inserire screenshot della scheda Opzioni con il campo "Layout alternativo" evidenziato

---

## Layout alternativi disponibili

### `scheda-servizio` — Scheda Servizio

Per gli articoli che descrivono un servizio erogato dal comune. Richiede la creazione e il mapping di custom fields specifici.

**Quando usarlo:** ogni articolo dentro la categoria Servizi che deve essere conforme al criterio C.SI.1.3 del Modello Comuni.

Vedere la guida completa: [scheda-servizio.md](scheda-servizio.md)

### `note-legali` — Note Legali

Per la pagina delle note legali obbligatoria (criterio C.SI.3.4).

**Cosa fa:** appende automaticamente in fondo all'articolo una sezione "Licenza dei contenuti" con il testo obbligatorio CC-BY 4.0 in italiano. Il testo non può essere modificato dall'editor (è fisso nel template).

**Quando usarlo:** una sola volta, per la pagina Note Legali del sito. Il link alla pagina va inserito nel footer (vedere [note-legali.md](note-legali.md)).

---

## Categorie e layout

Ricorda che:

- Il **layout dell'articolo** (scheda-servizio, note-legali) si imposta sull'**articolo singolo**
- Il **layout della categoria** (servizi, notizie) si imposta sulla **voce di menu** che punta alla categoria (vedere [alberatura-categorie.md](alberatura-categorie.md))

Questi due concetti sono indipendenti.

---

## Articoli in evidenza

Per marcare un articolo come "in evidenza" (es. per il carosello nell'header o per la sezione "In Evidenza" delle categorie Servizi e Notizie):

- Nell'elenco articoli, clicca la stella nella colonna **In Evidenza**
- Oppure nell'editor articolo, scheda **Pubblicazione** → campo **In Evidenza** impostato su Sì

> TODO – inserire screenshot dell'elenco articoli con la colonna "In Evidenza" e la stella cliccabile
