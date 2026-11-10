# Dati di esempio (sito di un Comune)

> **Modello Comuni** — I dati di esempio disponibili oggi sono quelli del sito di un Comune. Non sono adatti così come sono a Scuole, ASL o altri enti.

Il plugin **Dati di esempio - Template Accessibile** crea in pochi clic ciò che altrimenti andrebbe creato a mano seguendo le altre guide: categorie, campi personalizzati, menu, moduli e pagine istituzionali. Associa anche i campi e le voci di menu nei parametri del template.

È un'alternativa leggera al [Quickstart](../quickstart/istruzioni.md): il Quickstart installa un sito intero già pronto, il plugin aggiunge la struttura a un Joomla che hai già.

> ⚠️ **Non usarlo alla leggera su un sito già pubblicato.** I dati di esempio sono pensati per un sito nuovo o in costruzione. Su un sito con visitatori fai prima un backup e leggi [Su un sito già pubblicato](#su-un-sito-già-pubblicato).

---

## Requisiti

- Joomla 5.4 o superiore
- Template Accessibile installato (senza il template i contenuti vengono creati, ma non possono essere associati nei suoi parametri)
- Un utente Super User

---

## Installazione

1. Scarica `plg_sampledata_templateaccessibile_vX.X.X.zip` dalla pagina delle release, la stessa del template.
2. Vai su **Sistema → Installa → Estensioni** e carica il file.
3. Il plugin viene abilitato automaticamente.

---

## Come si usa

Apri il **Pannello di controllo** di Joomla. Nel modulo **Dati di esempio** trovi cinque voci che iniziano con "Sito di un Comune". Ogni voce è un blocco indipendente: clicca **Installa** su quelli che ti servono, nell'ordine che preferisci.

Se il modulo non è visibile, aggiungilo dal pulsante **Aggiungi modulo al pannello di controllo** scegliendo "Dati di esempio".

| Voce | Cosa crea |
|---|---|
| **Struttura** | Le categorie Amministrazione, Novità, Servizi e Vivere il Comune con le sottocategorie; il gruppo di campi "Servizi - Modello Comuni" con tutti i campi della Scheda Servizio; l'associazione dei campi nella scheda **Valutazione** del template |
| **Menu e moduli** | Tre menu (principale, secondario, footer) con le voci; i moduli che li mostrano nelle posizioni del template e il percorso di navigazione; l'associazione delle voci ai criteri di valutazione |
| **Pagine istituzionali** | La categoria "Pagine istituzionali" con sette pagine segnaposto: Note legali, Informativa privacy, Dichiarazione di accessibilità, Domande frequenti, Contatti, Prenotazione appuntamento, Segnalazione disservizio |
| **Campi DPCalendar** | I campi degli eventi usati dalla [Scheda Evento](scheda-evento.md) e la loro associazione nella scheda **Scheda Evento** del template. La voce compare solo se DPCalendar è installato |
| **Contenuti dimostrativi** | Una scheda servizio compilata, una notizia, un avviso e un argomento. I titoli iniziano con "Esempio:" |

Non c'è un ordine obbligato. Un blocco che ha bisogno di qualcosa creato da un altro lo crea da solo: **Menu e moduli** crea le categorie se mancano, **Contenuti dimostrativi** crea categorie e campi della Scheda Servizio se mancano. Lanciare poi **Struttura** non crea duplicati.

Ordine consigliato per avere tutto: **Struttura**, **Pagine istituzionali**, **Menu e moduli**. Le voci del footer vengono create solo per le pagine istituzionali che esistono: se installi "Menu e moduli" prima delle pagine, rilancialo dopo.

---

## Si può rilanciare?

Sì, senza rischi. Ogni blocco:

- **crea solo ciò che manca**: prima di creare un elemento controlla se esiste già (per alias o nome);
- **non modifica e non cancella nulla**: una categoria rinominata, un campo modificato, un articolo riscritto restano come li hai lasciati;
- **non ripristina dal cestino**: se un elemento è nel cestino lo segnala e lo salta;
- **non sovrascrive le associazioni**: un parametro del template già impostato resta com'è.

Al termine di ogni passo compare un resoconto con il numero di elementi creati e di quelli già presenti. Se un passo si interrompe, rilancialo: riparte da dove era arrivato.

Dopo la prima esecuzione, la descrizione della voce mostra la data dell'ultima installazione.

---

## Su un sito già pubblicato

Il plugin non crea doppioni di ciò che riconosce, ma su un sito già avviato può comunque avere effetti indesiderati:

- **Tutto nasce pubblicato.** Menu, pagine segnaposto e contenuti dimostrativi sono visibili ai visitatori appena creati; gli articoli "Esempio:" sono anche in evidenza e possono comparire in home.
- **Il riconoscimento è per alias.** Se hai già una categoria "Servizi" con un alias diverso da `servizi`, o un menu con un nome tecnico diverso, il plugin non li riconosce e ne crea di nuovi accanto. Controlla gli alias in [alberatura-categorie.md](alberatura-categorie.md) prima di lanciare "Struttura".
- **Moduli dei menu.** Se in una posizione (`menu-principale`, `menu-secondario`, `footer1`, `percorso`) c'è già un modulo dello stesso tipo pubblicato, il nuovo modulo viene creato **sospeso** e il resoconto lo segnala, così i visitatori non vedono due menu. Pubblicalo tu quando vuoi sostituire quello esistente.
- **Parametri del template.** Vengono compilati solo quelli vuoti; quelli che hai già impostato restano.

Su un sito pubblicato: fai un backup, installa un blocco alla volta e controlla il sito dopo ognuno. Evita "Contenuti dimostrativi".

---

## Cosa resta da fare a mano

- **Pagina iniziale**: il plugin non la cambia. Scegli tu quale voce di menu deve essere la home.
- **Testi delle pagine istituzionali**: sono segnaposto da sostituire. Per le Note legali vedi [note-legali.md](note-legali.md).
- **Parametri generali del template**: logo, nome del Comune, colore, social, ricerca e login. Vedi [configurazione-joomla.md](configurazione-joomla.md).
- **Moduli "in evidenza"** della home e contenuti reali.
- **Menu di DPCalendar** (lista eventi, luoghi): vedi [integrazione-DPCalendar.md](integrazione-DPCalendar.md).
- **Contenuti dimostrativi**: cancellali prima di pubblicare il sito.

---

## Se qualcosa non viene creato

Il resoconto indica il motivo. I casi più comuni:

| Messaggio | Cosa significa | Cosa fare |
|---|---|---|
| "… è nel cestino" | Esiste un elemento con lo stesso alias nel cestino | Ripristinalo oppure svuota il cestino, poi rilancia |
| "l'alias … è già usato da una voce del menu …" | Un'altra voce di menu del sito ha lo stesso alias | Cambia l'alias della voce esistente, oppure usa quella |
| "il nome … è già usato da un campo di un altro componente" | Esiste un campo con lo stesso nome tecnico | Rinomina il campo esistente, poi rilancia |
| "il Template Accessibile non è installato" | I contenuti sono stati creati, ma non associati | Installa il template e rilancia il blocco |
| "permessi insufficienti o componente non attivo" | L'utente non può creare quel tipo di contenuto | Accedi come Super User |

---

## Disinstallazione

Puoi disinstallare il plugin quando hai finito: i contenuti creati restano. Il plugin non rimuove mai i dati che ha creato.
