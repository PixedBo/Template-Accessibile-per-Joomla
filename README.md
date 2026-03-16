# Template Accessibile per Joomla 5+ (Modello Comuni / Bootstrap Italia)

## 📄 Descrizione
Questo è il template definitivo per integrare il **Modello Comuni** e il design system di **Designers Italia** nativamente su **Joomla 5+**. 
Progettato senza l'ausilio di framework esterni pesanti (zero jQuery, puro CSS vanilla e Javascript nativo), garantisce un'esperienza utente **altamente accessibile (WCAG 2.1 AA/AAA)**, estremamente performante e a prova di futuri aggiornamenti.

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
Il template è dotato di potenti override nativi per garantire che i componenti standard di Joomla generino codice HTML conforme alle linee guida di Designers Italia:

- **Moduli Articoli (`mod_articles`):** Sono presenti due layout specifici: un **layout a 3 colonne** e un **layout singolo**. Il layout singolo è dinamico: se all'interno del modulo è presente più di un articolo, il sistema crea automaticamente uno slideshow accessibile.
- **Modulo Menu (`mod_menu`):** È incluso il layout `comuni-menu`, essenziale e obbligatorio per impaginare in modo corretto e accessibile sia il Menu Principale che il Menu Secondario all'interno della testata.
- **Articolo Singolo (`com_content > article`):** Layout completo per servizi/notizie con calcolo automatico del tempo di lettura, impaginazione accessibile, tag a "chip" e pulsanti di condivisione social nativi.

## 📜 Licenza e Crediti
Questo template è rilasciato sotto licenza **GNU GPL v3**.  
Basato sulle risorse UI/UX di [Designers Italia](https://designers.italia.it/) e sul framework [Bootstrap Italia](https://italia.github.io/bootstrap-italia/).