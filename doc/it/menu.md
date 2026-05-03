# Menu

Il template prevede due posizioni per i menu di navigazione: `menu-principale` e `menu-secondario`. Entrambi richiedono l'uso del layout alternativo `comuni-menu`.

## Prerequisiti

- Avere creato almeno un menu in Joomla (**Menus → Gestione Menu → Aggiungi nuovo menu**)
- Avere aggiunto le voci di menu necessarie

> TODO – inserire screenshot della schermata "Gestione Menu" con un menu di esempio

---

## Come creare un modulo menu con il layout `comuni-menu`

1. Vai su **Estensioni → Moduli → Nuovo**
2. Seleziona il tipo **Menu**
3. Nella scheda **Modulo**:
   - Scegli il menu da mostrare (campo **Select Menu**)
   - In **Layout alternativo** seleziona `comuni-menu`
4. Nella scheda **Assegnazione Menu**: scegli su quali pagine mostrare il modulo
5. Nella scheda **Avanzate** → **Posizione modulo**: scegli `menu-principale` oppure `menu-secondario`
6. Imposta **Stato** su *Pubblicato* e salva

> TODO – inserire screenshot della scheda Modulo con il campo "Layout alternativo" impostato su `comuni-menu`

> TODO – inserire screenshot del dropdown delle posizioni con `menu-principale` selezionato

---

## Differenza tra menu principale e menu secondario

| Posizione | Descrizione |
|-----------|-------------|
| `menu-principale` | Navigazione orizzontale principale, visibile nell'header |
| `menu-secondario` | Navigazione secondaria, tipicamente più compatta, anch'essa nell'header |

> TODO – inserire screenshot dell'header con menu principale e secondario evidenziati separatamente

---

## Comportamento su mobile

Su schermi piccoli la navbar si comprime in un pulsante hamburger. Cliccandolo si apre un pannello laterale con entrambi i menu.

> TODO – inserire screenshot della navbar su mobile con il menu hamburger aperto

---

## Note

- Il layout `comuni-menu` è obbligatorio: senza di esso la struttura HTML per Bootstrap Italia non viene generata correttamente.
- Non è necessario creare due menu separati: è possibile assegnare lo stesso menu a entrambe le posizioni con layout diversi, oppure usare due menu distinti.
- Le voci di menu non hanno limitazioni di tipo: puoi usare voci a URL, voci a categoria, voci ad articolo, ecc.
