# Custom field per gli articoli

I custom field per gli articoli sono usati principalmente dal layout **Scheda Servizio** (`scheda-servizio`). Il layout non legge i dati direttamente dai nomi dei campi, ma tramite un sistema di **mapping**: l'admin crea i campi liberamente in Joomla, poi indica al template quale campo corrisponde a quale sezione.

---

## Come funziona il sistema di mapping

1. **Crei i custom field** in Joomla (con qualsiasi nome tu voglia)
2. **Mappi i campi** nella scheda **Valutazione Comuni** delle impostazioni del template, nella sezione **Scheda Servizio**: per ogni sezione del layout selezioni il custom field corrispondente
3. **Compili i campi** negli articoli a cui assegni il layout `scheda-servizio`

---

## Elenco dei custom field necessari per la Scheda Servizio

Per ogni campo è indicato il tipo Joomla consigliato.

| Campo nel template | Tipo Joomla consigliato | Contenuto |
|-------------------|-------------------------|-----------|
| `cf_stato` | Lista o Testo | Stato del servizio (es. `Attivo`, `Sospeso`, `In manutenzione`) |
| `cf_a_chi_rivolto` | Editor | Testo HTML: a chi è rivolto il servizio |
| `cf_come_fare` | Editor | Testo HTML: procedura per usufruire del servizio |
| `cf_cosa_serve` | Editor | Testo HTML: documenti e requisiti necessari |
| `cf_cosa_si_ottiene` | Editor | Testo HTML: cosa si ottiene al termine della procedura |
| `cf_tempi_scadenze` | Editor | Testo introduttivo della sezione tempi e scadenze |
| `cf_tempi_scadenze_lista` | Subform | Lista strutturata di scadenze (vedere sotto) |
| `cf_quanto_costa` | Editor | Testo HTML: costi e modalità di pagamento |
| `cf_accedi_online` | URL | Link diretto per accedere al servizio online |
| `cf_accedi_online_testo` | Testo | Etichetta del pulsante di accesso online |
| `cf_accedi_appuntamento` | URL | Link per prenotare un appuntamento |
| `cf_condizioni` | Editor | Testo HTML: condizioni di servizio |
| `cf_condizioni_file` | Media | File PDF delle condizioni di servizio |


> TODO – inserire screenshot dell'elenco custom field in Joomla con i campi della Scheda Servizio visibili

---

## Il campo Subform per i tempi e le scadenze (`cf_tempi_scadenze_lista`)

Questo è il campo più complesso. È di tipo **Subform** (campo composto) e permette di aggiungere più righe di scadenze, ognuna con più sotto-campi.

Ogni riga della subform può contenere:

| Sotto-campo | Tipo | Descrizione |
|-------------|------|-------------|
| Titolo | Testo | Nome della scadenza (es. "Presentazione domanda") |
| Data | Calendario | Data specifica della scadenza |
| Numero giorni/mesi | Text | Numero di giorni/mesi (alternativa alla data) |
| Unità di misura | Testo | Unità del numero (es. `giorni`, `mesi`) |
| Descrizione | Editor | Descrizione breve della scadenza |

Nel mapping del template dovrai selezionare gli elementi del subform.

> TODO – inserire screenshot della configurazione di un campo Subform in Joomla con i sotto-campi elencati

---

## Come mappare i campi nel template

Dopo aver creato i campi:

1. Vai su **Estensioni → Template → Stili** → modifica lo stile del template
2. Scheda **Valutazione Comuni** → sezione **Scheda Servizio**
3. Per ogni riga, seleziona dal menu a tendina il custom field corrispondente

> TODO – inserire screenshot della sezione "Scheda Servizio" nel backend del template con i dropdown compilati

---

## Come compilare i campi su un articolo

Quando apri un articolo in modifica, sotto l'area editor troverai i custom field raggruppati. Compila i valori per ogni sezione del servizio.

> TODO – inserire screenshot di un articolo in modifica con i custom field della Scheda Servizio visibili sotto l'editor

---

## Note

- I custom field mostrano nel frontend solo se hanno un valore. Le sezioni vuote non vengono renderizzate dalla scheda servizio.
- Il campo `cf_stato` viene visualizzato come badge (es. "Attivo") nella hero del servizio.
- Non è obbligatorio compilare tutti i campi per ogni servizio, ma il validatore del Modello Comuni richiede che le sezioni principali abbiano contenuto (min. 3 caratteri).
