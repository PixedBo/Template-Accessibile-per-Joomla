# Ricerca nel sito

Questa guida spiega come funziona la ricerca del template: l'icona nella testata, il riquadro di ricerca a tutta pagina, la pagina dei risultati e i filtri. Vale per qualsiasi sito, non solo per i Comuni.

---

## Il principio da tenere a mente

Il template **non ha un motore di ricerca proprio**. Usa quello di Joomla, il componente **Motore di ricerca** (in inglese *Smart Search*, nome tecnico `com_finder`), e si limita a dargli l'aspetto di Bootstrap Italia.

Ne derivano tre conseguenze pratiche:

1. **Cosa viene trovato lo decide l'indice di Joomla**, non il template. Un contenuto compare nei risultati solo se è stato indicizzato.
2. **Le voci dei filtri arrivano dall'indice.** Tipologie e categorie che vedi a sinistra dei risultati sono quelle che Joomla ha registrato durante l'indicizzazione. Per aggiungerne, toglierne o correggerle si lavora nell'indice (**Componenti → Motore di ricerca**), non nei file del template.
3. **Se la ricerca non restituisce ciò che ti aspetti**, il primo posto dove guardare è l'indice: vedi [Risoluzione dei problemi](#risoluzione-dei-problemi).

Il template decide soltanto **come** la ricerca si presenta e **quali** gruppi di filtri mostrare.

> Nel pannello di amministrazione in italiano il componente si chiama **Motore di ricerca**. In questa guida "Motore di ricerca" e "Smart Search" indicano la stessa cosa.

---

## Cosa serve prima di iniziare

Sono impostazioni di Joomla, da fare una volta sola.

### 1. Attivare i plugin

Vai in **Sistema → Plugin** e controlla che siano **abilitati**:

| Plugin | A cosa serve |
|--------|--------------|
| **Content - Motore di ricerca** | Aggiorna l'indice ogni volta che salvi, pubblichi o elimini un contenuto. Senza, l'indice resta fermo all'ultima indicizzazione manuale. |
| **Motore di ricerca - Contenuti** | Indicizza gli articoli. |
| **Motore di ricerca - Categorie** | Indicizza le categorie. |
| **Motore di ricerca - Tags** | Indicizza i tag (come pagine a sé). |
| **Motore di ricerca - Contatti**, **- Newsfeed** | Solo se usi contatti o newsfeed. |

Per trovarli in fretta, nell'elenco dei plugin filtra per tipo **finder** e cerca "Motore di ricerca".

Le estensioni di terze parti (per esempio un calendario eventi) compaiono nei risultati solo se forniscono un proprio plugin "Motore di ricerca" e questo è abilitato.

### 2. Creare l'indice

1. Vai in **Componenti → Motore di ricerca → Indice**.
2. Clicca **Indice** nella barra degli strumenti.
3. Attendi la fine dell'operazione senza chiudere la finestra.

Da questo momento, con il plugin "Content - Motore di ricerca" attivo, l'indice si aggiorna da solo a ogni salvataggio.

### 3. Controllare due opzioni del Motore di ricerca

Vai in **Componenti → Motore di ricerca → Opzioni**:

| Opzione | Quando attivarla |
|---------|------------------|
| **Consenti ricerca vuota** | Se vuoi che i filtri funzionino anche senza scrivere un testo (per esempio "mostrami tutte le Notizie"). Se è spenta, chi sceglie un filtro senza testo vede l'invito a scrivere cosa sta cercando. |
| **Suggerimenti di ricerca** | Se vuoi i suggerimenti durante la digitazione. Va attivata anche l'opzione corrispondente del template (vedi sotto). |

### 4. (Consigliato) Creare una voce di menu per la pagina di ricerca

Non è obbligatorio, ma dà alla pagina dei risultati un indirizzo leggibile (per esempio `/cerca`) e un titolo.

1. Vai in **Menu** e apri un menu (anche uno non visibile nel sito).
2. Clicca **Nuovo**, come tipo scegli **Motore di ricerca → Risultati del motore di ricerca**.
3. Dai un titolo (es. "Cerca") e salva.

Il template trova da solo questa voce: non serve selezionarla nei parametri.

---

## Impostazioni del template

Vai in **Sistema → Stili template sito**, apri lo stile del template e scegli la scheda **Ricerca**.

> Fino alla versione 1.2.x i primi campi stavano nella scheda **Opzioni Header**. Sono stati spostati senza cambiare nome: i valori già salvati restano validi.

### Icona nella testata

| Campo | Valori | Cosa fa |
|-------|--------|---------|
| **Mostra pulsante Ricerca** | Sì / No (predefinito: No) | Mostra l'icona con la lente nella testata. |
| **Al clic sull'icona** | *Apri la pagina di ricerca* (predefinito) / *Apri il riquadro di ricerca a tutta pagina* | Vedi [Il riquadro di ricerca](#il-riquadro-di-ricerca-a-tutta-pagina). |
| **Pagina di ricerca** | *Ricerca Smart Search standard* (predefinito) / *Seleziona pagina di menu* | Quale pagina mostra i risultati. Con "standard" il template usa la pagina del Motore di ricerca (e la voce di menu del punto 4, se esiste). |
| **Seleziona menu per ricerca** | una voce di menu | Compare solo scegliendo "Seleziona pagina di menu". Utile se hai più pagine di ricerca o un componente di ricerca diverso. |
| **Suggerimenti durante la digitazione** | Sì / No (predefinito: No) | Propone i termini mentre si scrive, nel riquadro e nella pagina dei risultati. |

Se il Motore di ricerca è disabilitato e non hai scelto una voce di menu alternativa, l'icona non viene mostrata.

### Filtri nella pagina dei risultati

| Campo | Valori | Cosa fa |
|-------|--------|---------|
| **Mostra i filtri** | Sì (predefinito) / No | Mostra la colonna dei filtri a sinistra dei risultati. |
| **Filtro per tipologia** | Sì (predefinito) / No | Gruppo "Tipologie": articoli, categorie, tag e gli altri tipi indicizzati. |
| **Filtro per categoria** | Sì (predefinito) / No | Gruppo "Categorie": le categorie che contengono contenuti indicizzati. |
| **Categorie da mostrare** | selezione multipla | Vuoto = tutte. Scegliendo una categoria compaiono anche le sue sottocategorie. |
| **Selezione inversa** | Sì / No (predefinito: No) | Capovolge il campo precedente: le categorie scelte (con le sottocategorie) vengono **nascoste** e si mostrano tutte le altre. |

Esempi:

- Mostrare solo i filtri di Novità e Servizi: in **Categorie da mostrare** scegli "Novità" e "Servizi", **Selezione inversa** = No.
- Mostrare tutto tranne "Uncategorised" e una categoria di servizio interna: sceglile in **Categorie da mostrare**, **Selezione inversa** = Sì.

> **Attenzione ai nomi uguali.** Il template riconosce le categorie scelte dal loro **titolo**, perché Joomla rinumera le voci dell'indice ogni volta che questo viene svuotato. Se due categorie hanno lo stesso titolo (per esempio due "Avvisi" sotto genitori diversi), vengono trattate allo stesso modo.

---

## Il riquadro di ricerca a tutta pagina

Con **Al clic sull'icona = Apri il riquadro di ricerca a tutta pagina**, la lente apre un riquadro che copre la pagina e contiene:

- il titolo "Cerca" e il pulsante di chiusura (una freccia sui telefoni, una X sugli schermi più grandi);
- il campo di ricerca, già pronto per scrivere;
- il pulsante **Cerca**, che porta alla pagina dei risultati;
- il link **Ricerca avanzata**, che porta alla pagina dei risultati senza testo, dove si trovano i filtri.

Comportamento:

- Si chiude con il pulsante di chiusura o con il tasto **Esc**; alla chiusura il cursore torna sull'icona.
- Finché è aperto, il tasto Tab resta all'interno del riquadro.
- **Senza JavaScript** l'icona è un normale link alla pagina di ricerca: la ricerca resta utilizzabile.
- Il riquadro invia il testo alla pagina del Motore di ricerca. Se in **Pagina di ricerca** hai scelto una voce di menu che non è del Motore di ricerca (un altro componente), il riquadro non viene usato e l'icona resta un link a quella pagina.

> *Screenshot — Riquadro di ricerca a tutta pagina con campo, pulsante Cerca e link Ricerca avanzata*

---

## La pagina dei risultati

La pagina è quella del Motore di ricerca di Joomla, ridisegnata dal template.

### Cosa contiene

1. **Campo di ricerca** grande, in alto, con il pulsante **Cerca**.
2. **Colonna dei filtri** a sinistra (sui telefoni è richiudibile con il pulsante "Mostra o nascondi i filtri").
3. **Numero di risultati** e, se ci sono filtri attivi, il link **Rimuovi tutti i filtri**.
4. **"Intendevi forse…"**, quando Joomla riconosce un probabile errore di battitura.
5. **Elenco dei risultati**: ogni risultato è un riquadro con, dall'alto, la categoria (o il tipo di contenuto, se non ha categoria), il titolo cliccabile, la data e un estratto del testo attorno alla parola cercata.
6. **Paginazione**.

> *Screenshot — Pagina dei risultati con filtri a sinistra e risultati a destra*

### Opzioni del Motore di ricerca rispettate

Queste opzioni si impostano in **Componenti → Motore di ricerca → Opzioni** (o nella voce di menu della pagina di ricerca) e la pagina le rispetta:

| Opzione di Joomla | Effetto sulla pagina |
|-------------------|----------------------|
| **Descrizione risultato** e **Lunghezza descrizione** | Mostra l'estratto e ne decide la lunghezza. |
| **Data risultati** | Mostra la data sotto il titolo. |
| **Intendevi forse** | Mostra il suggerimento di correzione. |
| **Mostra campi di ordinamento** | Mostra i link per ordinare i risultati. |
| Evidenziazione dei termini | Evidenzia le parole cercate nella pagina dei risultati. |
| Paginazione | Mostra i numeri di pagina. |

Non vengono mostrati, anche se attivi nelle opzioni: l'immagine del risultato, l'indirizzo della pagina sotto il titolo, la frase che spiega come è stata interpretata la ricerca ("Spiegazione Query") e i filtri per data.

---

## I filtri

### Come si usano

- Tutte le voci sono **caselle di spunta**.
- I filtri si applicano con il pulsante **Applica filtri** in fondo alla colonna (o con **Cerca**): spuntare una casella non ricarica la pagina.
- Più caselle **dello stesso gruppo** allargano la ricerca: "Notizie" + "Avvisi" trova ciò che sta in Notizie **oppure** in Avvisi.
- Caselle di **gruppi diversi** la restringono: "Articoli" (tipologia) + "Notizie" (categoria) trova solo gli articoli che stanno in Notizie.
- I filtri restano attivi cambiando pagina o ordinamento.

### Categorie con sottocategorie

Una categoria che ha sottocategorie ha, accanto alla casella, un **pulsante tondo con una freccia**:

- la **freccia** mostra o nasconde le sottocategorie. All'apertura della pagina sono aperti solo i gruppi che contengono un filtro attivo, così l'elenco resta corto;
- la **casella della categoria genitore** seleziona o deseleziona in un colpo tutte le sue sottocategorie;
- se sono selezionate solo alcune sottocategorie, la casella del genitore mostra un **trattino** al posto della spunta.

Se la categoria genitore contiene anche articoli propri (non solo sottocategorie), la sua casella vale sia per quegli articoli sia come "seleziona tutte". Per filtrare solo alcune sottocategorie, spuntale una per una.

Senza JavaScript le sottocategorie sono tutte visibili e ogni casella vale solo per sé.

### Da dove arrivano le voci

Le voci dei filtri **non si scrivono nel template**: sono quelle che il Motore di ricerca registra mentre indicizza. Joomla le chiama **mappe dei contenuti** e le organizza in gruppi; il template ne usa due.

| Gruppo nel sito | Gruppo in Joomla | Chi lo crea |
|-----------------|------------------|-------------|
| **Tipologie** | Type (Tipo) | Ogni plugin "Motore di ricerca": Contenuti crea "Articoli", Categorie crea "Categorie", Tags crea "Tag", e così via. |
| **Categorie** | Category (Categoria) | I plugin che indicizzano contenuti con una categoria (articoli, contatti, newsfeed). |

Regole con cui il template sceglie cosa mostrare:

- Una **tipologia** compare solo se ha almeno un contenuto indicizzato.
- Una **categoria** compare se ha contenuti indicizzati propri, oppure se ha almeno una sottocategoria che ne ha.
- Vengono mostrate solo le voci **pubblicate** e visibili al livello di accesso di chi naviga. Nei siti multilingua, solo quelle della lingua corrente.
- Un gruppo con una sola tipologia non viene mostrato (non ci sarebbe nulla da scegliere).

### Modificare le voci dei filtri

Poiché le voci sono dell'indice, **le modifiche si fanno in Joomla**, in **Componenti → Motore di ricerca**:

| Voglio… | Dove |
|---------|------|
| Nascondere una singola voce (una tipologia o una categoria) | **Mappe contenuti** → scegli il gruppo (Type o Category) → **sospendi** la voce. Sparisce subito dai filtri. |
| Nascondere più categorie in blocco | Più comodo dal template: **Categorie da mostrare** e **Selezione inversa**. |
| Togliere dai risultati un tipo di contenuto | Disabilita il relativo plugin "Motore di ricerca - …" e ricrea l'indice. |
| Far comparire una nuova categoria | Pubblica almeno un contenuto al suo interno: con il plugin "Content - Motore di ricerca" attivo viene indicizzato al salvataggio. In alternativa clicca **Indice**. |
| Aggiornare un nome dopo aver rinominato una categoria | Clicca **Indice** per reindicizzare. Se il vecchio nome resta, usa **Manutenzione → Pulisci indicizzazione** e poi **Indice**. |

### Rinominare una tipologia

Il nome di una tipologia è scelto dall'estensione che la indicizza. Alcune estensioni non lo traducono e nei filtri compare il nome tecnico (per esempio `Event`).

Il template traduce già `Event` in "Eventi". Per le altre:

1. Vai in **Sistema → Gestione → Override lingua**.
2. Scegli la lingua e il lato **Sito**.
3. Crea un nuovo override con queste chiavi:
   - `TPL_ACCESSIBILE_SEARCH_TYPE_P_<TIPO>` — il nome al plurale, mostrato nei filtri;
   - `TPL_ACCESSIBILE_SEARCH_TYPE_S_<TIPO>` — il nome al singolare, mostrato sopra il titolo del risultato.

`<TIPO>` è il nome tecnico in maiuscolo, con i caratteri diversi da lettere e numeri sostituiti da `_`.

Esempio per un tipo che compare come `Web Link`:

| Chiave | Testo |
|--------|-------|
| `TPL_ACCESSIBILE_SEARCH_TYPE_P_WEB_LINK` | Link utili |
| `TPL_ACCESSIBILE_SEARCH_TYPE_S_WEB_LINK` | Link utile |

### Limiti dei filtri

Dipendono da come Joomla costruisce l'indice, non dal template:

- **Non c'è un filtro per argomenti (tag).** Joomla indicizza i tag come pagine a sé, ma non registra quali tag ha ciascun articolo.
- **Ogni contenuto è collegato solo alla sua categoria diretta.** Per questo la casella di una categoria genitore lavora selezionando le sottocategorie una per una, e una categoria che contiene solo sottocategorie non ha un filtro proprio.
- **Non c'è il numero di risultati accanto a ogni voce.**

Per avere filtri per argomenti o per macro-area servirebbe un plugin "Motore di ricerca" dedicato, non incluso in questa versione.

---

## Suggerimenti durante la digitazione

Per averli servono **entrambe** le impostazioni:

1. nel template, scheda **Ricerca**: **Suggerimenti durante la digitazione = Sì**;
2. in **Componenti → Motore di ricerca → Opzioni**: **Suggerimenti di ricerca** attiva.

I suggerimenti compaiono dopo il secondo carattere, nel riquadro e nella pagina dei risultati. Sono parole presenti nell'indice, non ricerche fatte da altri utenti. Si scorrono con i tasti freccia e si scelgono con Invio.

---

## Manutenzione dell'indice

Tutto in **Componenti → Motore di ricerca → Indice**.

| Operazione | Quando serve |
|------------|--------------|
| **Indice** | Dopo aver attivato i plugin, dopo un'importazione di contenuti, o quando i risultati non sono aggiornati. Aggiunge e aggiorna, non cancella ciò che non esiste più. |
| **Manutenzione → Ottimizza** | Ogni tanto, per tenere veloce la ricerca. Elimina anche le voci dei filtri rimaste senza contenuti. |
| **Manutenzione → Pulisci indicizzazione** | Quando nell'indice sono rimasti contenuti o tipologie di estensioni disinstallate, o dopo grandi riorganizzazioni. Svuota tutto: subito dopo va cliccato **Indice**. |

Dopo **Pulisci indicizzazione**:

- finché non ricrei l'indice la ricerca non trova nulla;
- gli indirizzi di ricerche filtrate salvati in precedenza (link con `t[]=…`) smettono di valere, perché le voci vengono rinumerate;
- i parametri del template **non** vanno toccati: le categorie scelte in "Categorie da mostrare" sono riconosciute per titolo;
- le voci che avevi sospeso in **Mappe contenuti** tornano pubblicate e vanno sospese di nuovo.

---

## Risoluzione dei problemi

| Problema | Causa probabile | Cosa fare |
|----------|-----------------|-----------|
| L'icona di ricerca non compare | "Mostra pulsante Ricerca" è su No, oppure il Motore di ricerca è disabilitato | Controlla la scheda **Ricerca** del template e **Sistema → Estensioni** |
| La ricerca non trova nulla | Indice vuoto o plugin disattivati | [Attiva i plugin](#1-attivare-i-plugin) e [crea l'indice](#2-creare-lindice) |
| Un contenuto appena pubblicato non si trova | Plugin "Content - Motore di ricerca" disattivato | Abilitalo e clicca **Indice** |
| Il riquadro a tutta pagina non si apre | "Al clic sull'icona" è su "Apri la pagina", oppure la voce di menu scelta non è del Motore di ricerca | Controlla la scheda **Ricerca** |
| La colonna dei filtri non compare | "Mostra i filtri" è su No, oppure l'indice non ha voci da mostrare | Controlla la scheda **Ricerca**; ricrea l'indice |
| Scegliendo un filtro senza testo compare "Scrivi cosa stai cercando…" | "Consenti ricerca vuota" è spenta | Attivala nelle opzioni del Motore di ricerca |
| Nei filtri c'è una tipologia di un'estensione che non c'è più (es. `icagenda_event`) | Nell'indice sono rimasti contenuti indicizzati da quell'estensione | **Indice** → filtra per tipo di contenuto ed elimina quelle voci; oppure **Pulisci indicizzazione** e poi **Indice**. Per nasconderla subito: sospendila in **Mappe contenuti** |
| Una tipologia ha un nome tecnico o in inglese | L'estensione non lo traduce | [Rinomina la tipologia](#rinominare-una-tipologia) |
| Una categoria non compare tra i filtri | Non ha contenuti indicizzati, è esclusa da "Categorie da mostrare", oppure è sospesa in Mappe contenuti | Verifica i tre punti |
| Spuntando una categoria genitore non succede nulla sulle sottocategorie | JavaScript disattivato nel browser | Spunta le sottocategorie una per una |
| I suggerimenti non compaiono | Una delle due impostazioni è spenta | Vedi [Suggerimenti durante la digitazione](#suggerimenti-durante-la-digitazione) |
| Gli eventi del calendario non sono tra i risultati | L'estensione del calendario non ha un plugin "Motore di ricerca" attivo | Verifica tra i plugin di tipo **finder** |

---

## Per chi personalizza il template

| File | Contenuto |
|------|-----------|
| `html/com_finder/search/default.php` | Struttura della pagina dei risultati |
| `html/com_finder/search/default_form.php` | Campo di ricerca |
| `html/com_finder/search/default_filtri.php` | Colonna dei filtri |
| `html/com_finder/search/default_results.php` | Numero di risultati, elenco, paginazione |
| `html/com_finder/search/default_result.php` | Singolo risultato |
| `html/com_finder/search/default_sorting.php` | Ordinamento |
| `html/layouts/accessibile/ricerca-modale.php` | Riquadro di ricerca a tutta pagina |
| `helpers/TplAccessibileSearchHelper.php` | Pagina di destinazione, gruppi di filtri, nomi delle tipologie |
| `js/search-modal.js`, `js/search-filters.js` | Focus nel riquadro; selezione a cascata e apertura delle sottocategorie |
| `css/template-comuni.css` (sezione "Ricerca") | Stili |

Un template figlio che ha già un proprio override di `com_finder/search` continua a usare il proprio.

Per mostrare un risultato in modo diverso secondo il tipo di contenuto, crea accanto a `default_result.php` un file `default_<tipo>.php` (per esempio `default_article.php`): Joomla lo usa al posto di quello generico.
