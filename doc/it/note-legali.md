# Note Legali (C.SI.3.4)

La pagina Note Legali è obbligatoria per la conformità al Modello Comuni (criterio C.SI.3.4). Il template fornisce un layout dedicato che aggiunge automaticamente il testo della licenza CC-BY 4.0.

---

## Come creare la pagina Note Legali

### 1. Crea l'articolo

Vai su **Contenuti → Articoli → Nuovo** e inserisci il contenuto aggiuntivo della pagina (es. note sul copyright, limitazioni d'uso, riferimenti normativi specifici dell'ente). Questo contenuto appare **prima** della sezione licenza.

È possibile lasciare l'articolo vuoto se si vuole mostrare solo il testo obbligatorio.

### 2. Assegna il layout alternativo `note-legali`

Nella scheda **Opzioni** dell'articolo → campo **Layout alternativo** → seleziona `note-legali`.

> TODO – inserire screenshot della scheda Opzioni con il layout `note-legali` selezionato

### 3. Pubblica l'articolo

Assegna una categoria appropriata (es. "Pagine Istituzionali") e pubblica.

### 4. Crea la voce di menu

Crea una voce di menu che punta all'articolo (tipo **Articoli → Articolo singolo**) e inseriscila nel footer del sito (vedere [footer.md](footer.md)).

> TODO – inserire screenshot della voce di menu creata nel footer

---

## Cosa aggiunge il layout automaticamente

Il layout `note-legali.php` appende in fondo all'articolo una sezione con `data-element="legal-notes"` contenente:

- L'intestazione **"Licenza dei contenuti"**
- Il testo completo della licenza **Creative Commons BY 4.0** in italiano

Il testo della licenza è fisso nel template e **non può essere modificato dall'editor** tramite il backend. Questo garantisce che il testo ufficiale CC-BY 4.0 richiesto dal Modello Comuni non venga alterato.

> TODO – inserire screenshot della pagina Note Legali renderizzata con la sezione "Licenza dei contenuti" visibile

---

## Mapping per la valutazione automatica

Il `data-element="legal-notes"` viene verificato dall'App Valutazione Modelli (Dipartimento per la Trasformazione Digitale). Il layout lo emette automaticamente: non è necessaria nessuna configurazione aggiuntiva.

---

## Note

- Il layout non richiede custom fields.
- Creare una sola pagina Note Legali per sito.
- Il corpo dell'articolo (introtext e fulltext) è ancora disponibile per contenuto aggiuntivo specifico dell'ente.
