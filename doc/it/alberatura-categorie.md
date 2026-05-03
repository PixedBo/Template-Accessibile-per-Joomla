# Alberatura delle categorie

Joomla organizza i contenuti in categorie ad albero. Il template include layout specializzati per alcune categorie: è quindi importante strutturare l'alberatura in modo coerente.

---

## Struttura consigliata per un Comune

Vedi PDF: https://designers.italia.it/files/resources/modelli/comuni/adotta-il-modello-di-sito-comunale/definisci-architettura-e-contenuti/Alberatura-ModelloComuni-DesignersItalia.pdf


## Categoria Servizi

La categoria Servizi (e le sue sottocategorie) usa il layout `servizi`. Questo layout:

- Mostra una sidebar con i **Servizi in Evidenza** (articoli con `featured = 1` nella categoria o nelle sottocategorie)
- Mostra la lista di tutte le **Sottocategorie** con i link `data-element="service-category-link"`
- I link agli articoli hanno `data-element="service-link"`
- Include la posizione modulo `cerca-servizi` per la ricerca interna

Ogni articolo di servizio nella categoria Servizi dovrebbe usare il layout **Scheda Servizio** (`scheda-servizio`). Vedere [scheda-servizio.md](scheda-servizio.md).

---

## Categoria Notizie

La categoria Notizie usa il layout `notizie`. Questo layout:

- Mostra una sezione **"In Evidenza"** nella prima pagina (articoli con `featured = 1`), configurabile
- Lista tutte le notizie con paginazione
- Mostra le sottocategorie se presenti

Vedere [layout-notizie.md](layout-notizie.md) per i dettagli di configurazione.

---

## Categorie generiche

Per tutte le altre categorie (documenti, pagine informative, ecc.) si usano i layout standard di Joomla (`default` per lista, `blog` per blog), anch'essi soggetti all'override del template per la stilizzazione Bootstrap Italia.

---

## Note

- Il template non impone alcun vincolo sul numero di livelli di profondità nell'alberatura.
- La categoria **Servizi** può avere quanti livelli di sottocategorie si desidera: il layout `servizi` mostra sempre le sottocategorie del livello corrente.
- Per la **Scheda Servizio**, ogni articolo deve avere i custom fields mappati correttamente (vedere [custom-field-articoli.md](custom-field-articoli.md)).
