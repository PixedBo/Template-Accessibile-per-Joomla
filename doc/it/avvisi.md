# Posizione "avvisi": banner di comunicazione

La posizione modulo `avvisi` permette di pubblicare banner di comunicazione istituzionale visibili su tutta la larghezza della pagina, subito sotto l'intestazione del sito e prima del breadcrumb.

Casi d'uso tipici: modalità dimostrativa, manutenzioni programmate, disservizi temporanei, allerte di sicurezza, comunicazioni urgenti.

---

## Come pubblicare un avviso

1. Vai su **Contenuto → Moduli sito → Nuovo** e scegli il tipo **Personalizzato** (o qualsiasi altro tipo di modulo che produce contenuto HTML).
2. Inserisci il testo dell'avviso nel corpo del modulo.
3. Nel campo **Titolo**, scrivi un titolo descrittivo (es. "Avviso manutenzione") — se non vuoi che appaia in pagina, imposta **Mostra titolo** su *Nascondi*.
4. Nella scheda **Assegnazione menu**, scegli su quali pagine mostrare il banner (tutte le pagine, solo alcune, oppure escludi alcune voci).
5. Nella scheda **Modulo**, imposta **Posizione** su `avvisi`.
6. Nella scheda **Avanzate**, imposta il **Suffisso classe CSS modulo** per scegliere il colore (vedi sezione seguente).
7. Pubblica il modulo.

---

## Come scegliere il colore

Il colore del banner si imposta dalla scheda **Avanzate** del modulo, nel campo **Suffisso classe CSS modulo**.

| Valore | Colore | Quando usarlo |
|---|---|---|
| `warning` | Arancione | Avvisi non bloccanti, informazioni importanti non urgenti **(default)** |
| `danger` | Rosso | Emergenze, criticità, disservizi gravi |
| `info` | Azzurro | Informazioni neutre, comunicazioni ordinarie |
| `success` | Verde | Conferme positive, ripristino di un servizio |

**Se il campo è vuoto** o contiene un valore non riconosciuto (es. `primary`, `dark`), il template applica automaticamente `warning`.

**Valori multipli separati da spazio**: il campo può contenere più classi separate da spazio (es. `warning my-avviso-speciale`). Il template usa il primo valore della whitelist per determinare il colore del banner; le altre classi vengono ignorate ai fini del colore ma restano disponibili per personalizzazioni CSS avanzate.

---

## Esempio di contenuto HTML

Copia e adatta questo snippet nel corpo del modulo. Non includere classi di sfondo (`bg-warning` ecc.): il colore viene fornito dal wrapper del template in base al suffisso scelto.

```html
<div class="d-flex align-items-center gap-3">
  <svg class="icon icon-sm flex-shrink-0" aria-hidden="true" focusable="false">
    <use href="/media/templates/site/templateaccessibileperjoomla/svg/sprites.svg#it-warning-circle"></use>
  </svg>
  <div>
    <strong>Sito in modalità dimostrativa.</strong>
    I dati presenti sono di esempio e non hanno valore ufficiale.
    <a href="/note-legali" class="fw-semibold ms-2">Dettagli</a>
  </div>
</div>
```

---

## Più avvisi contemporaneamente

È possibile pubblicare più moduli sulla stessa posizione `avvisi`. Ciascuno appare come un banner separato, impilato nell'ordine definito dall'**Ordinamento** nella lista moduli. Ogni modulo può avere il proprio colore indipendente.

---

## Accessibilità

Il template avvolge ogni avviso in un elemento `<aside>` con attributo `aria-label="Avvisi importanti"`. Gli screen reader lo riconoscono come regione complementare navigabile e lo annunciano correttamente durante la navigazione per landmark.

Il banner **non è una live region** (`aria-live`): non viene riannunciato automaticamente ogni volta che la pagina si carica o il contenuto cambia. Questo lo rende adatto a comunicazioni persistenti (es. "sito dimostrativo") ma non a messaggi che cambiano dinamicamente senza ricaricamento della pagina.

---

## Limiti

- **Non usare questa posizione per il cookie banner**: il consenso ai cookie è gestito da un plugin Joomla dedicato (es. Klaro, Cookiebot) con logica propria.
- **Non usare questa posizione per form o login complessi**: il banner è pensato per contenuti informativi. Per funzionalità interattive (login, ricerca, form multi-step) usa le posizioni appropriate o un componente dedicato.
