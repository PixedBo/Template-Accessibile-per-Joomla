# Footer

Il footer è il piè di pagina del sito. È composto da più elementi: le colonne di contenuto libero, i link social, il blocco "Contatta" e il logo UE.

---

## Struttura del footer

Il footer è organizzato così, dall'alto verso il basso:

1. **Sezione logo e social**: logo del sito, nome del comune, payoff, link "Seguici su:" con le icone social
2. **Colonne di contenuto**: due posizioni modulo (`footer1` e `footer2`)
3. **Logo UE**: il logo dell'Unione Europea (se abilitato)

Sopra il footer (ma tecnicamente separato da esso) c'è il blocco **"Contatta il Comune"**, configurato nella scheda Contatta del template (vedi [configurazione-joomla.md](configurazione-joomla.md#scheda-contatta)).

> *Screenshot — Footer completo del sito con le aree etichettate: logo+social, colonne footer1/footer2, logo UE*

---

## Posizioni modulo del footer

Il footer ha due colonne di contenuto libero dove puoi inserire qualsiasi modulo Joomla.

| Posizione | Descrizione |
|-----------|-------------|
| `footer1` | Prima colonna del footer (sinistra o a piena larghezza su mobile) |
| `footer2` | Seconda colonna del footer (destra) |

### Come aggiungere un modulo nel footer

1. Vai su **Estensioni → Moduli → Nuovo** e scegli il tipo di modulo
2. Nella scheda **Avanzate** → campo **Posizione modulo**, inserisci `footer1` oppure `footer2`
3. Imposta lo stato su **Pubblicato** e salva

> *Screenshot — Footer del sito con le due colonne evidenziate: "footer1" a sinistra e "footer2" a destra*

### Cosa inserire nel footer

Tipicamente nel footer si inseriscono (usando moduli di tipo **HTML personalizzato** o **Menu**):

- Link istituzionali obbligatori: FAQ, Segnalazione disservizi, Dichiarazione di Accessibilità, Privacy Policy
- Link alla pagina Note Legali
- Informazioni di contatto dell'ente
- Menu secondari o link rapidi

**Nota per il Modello Comuni:** i link istituzionali (FAQ, Accessibilità, Privacy, Note legali, Segnalazione disservizio) non sono inseriti automaticamente dal template. Per farli riconoscere dall'App Valutazione Modelli:

1. crea un menu con le voci e mostralo con un modulo **Menu** nella posizione `footer1` o `footer2`;
2. nel modulo, scheda **Avanzate** → **Layout**, scegli **comuni-footer**: è il layout che aggiunge i `data-element` ai link (un modulo HTML personalizzato o un menu con il layout predefinito non li aggiungono);
3. associa le voci nella scheda **Valutazione** del template. Vedi [valutazione-modelli.md](valutazione-modelli.md).

Il plugin [Dati di esempio](dati-di-esempio.md) fa questi tre passi da solo.

---

## Social nel footer

I canali social configurati nella scheda **Social** del template appaiono automaticamente **anche nel footer**, nella sezione "Seguici su:", con le stesse icone che appaiono nell'header.

Non esiste una configurazione separata per i social del footer: stessi URL, stesse icone. Se hai configurato i social nella scheda Social, appaiono sia nell'header che nel footer senza azioni aggiuntive.

> *Screenshot — Footer con la sezione "Seguici su:" e le icone social (X, Facebook, YouTube, ecc.)*

---

## Logo UE

Il template può mostrare il logo dell'Unione Europea nel footer.

**Come controllarlo:**
1. Vai su **Estensioni → Template → Stili → scheda Footer**
2. Campo **Mostra logo UE**: seleziona **Sì** (default) o **No**
3. Salva

| Valore | Effetto |
|--------|---------|
| Sì (default) | Il logo UE appare nel footer |
| No | Il logo UE non viene mostrato |

> *Screenshot — Parte inferiore del footer con il logo UE visibile*

---

## Blocco "Contatta il Comune"

Il blocco "Contatta il Comune" è una sezione di link rapidi che appare **sopra il footer** (non dentro le colonne `footer1`/`footer2`). Mostra link a FAQ, Contatti, Telefono, Prenotazione appuntamento e Segnalazione disservizi.

Questo blocco si configura nella scheda **Contatta** delle impostazioni del template. Per la guida completa, vedi [configurazione-joomla.md](configurazione-joomla.md).

Il blocco appare **solo se almeno uno** dei campi nella scheda Contatta è compilato. Se tutti i campi sono vuoti, il blocco non viene mostrato.

> *Screenshot — Blocco "Contatta il Comune" sopra il footer con i vari link*
