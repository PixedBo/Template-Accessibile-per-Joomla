# Footer

Il footer è composto da due aree di contenuto libero e da elementi fissi gestiti dal template.

---

## Posizioni modulo del footer

| Posizione | Descrizione |
|-----------|-------------|
| `footer1` | Prima colonna del footer (sinistra o piena larghezza) |
| `footer2` | Seconda colonna del footer (destra) |

Entrambe le posizioni accettano qualsiasi tipo di modulo Joomla (testo personalizzato, menu, lista articoli, ecc.).

> TODO – inserire screenshot del footer con le due colonne evidenziate e le posizioni `footer1`/`footer2` indicate

### Cosa mettere nel footer

Tipicamente nel footer si inseriscono:

- Link istituzionali obbligatori: FAQ, Segnalazione disservizi, Dichiarazione accessibilità, Privacy policy (vedere [valutazione-modelli.md](valutazione-modelli.md))
- Link alla pagina Note Legali (vedere [note-legali.md](note-legali.md))
- Informazioni di contatto dell'ente
- Menu secondari o link rapidi

---

## Logo UE

Il template mostra un logo dell'Unione Europea nel footer. Questo comportamento si controlla tramite il parametro `mostra_logo_ue` nella scheda **Footer** delle impostazioni del template.

| Valore | Effetto |
|--------|---------|
| Sì (default) | Il logo UE appare nel footer |
| No | Il logo UE non viene mostrato |

> TODO – inserire screenshot del footer con il logo UE visibile

---

## Link istituzionali obbligatori (Modello Comuni)

I link a FAQ, Segnalazione disservizi, Accessibilità e Privacy non sono hard-coded nel template. Vanno aggiunti come moduli (es. modulo Menu o HTML personalizzato) nelle posizioni `footer1` o `footer2`, e poi mappati nella scheda **Valutazione Comuni** del template tramite i campi `de_faq`, `de_report_inefficiency`, `de_accessibility_link` e `de_privacy_policy_link`.

Vedere la guida completa: [valutazione-modelli.md](valutazione-modelli.md).
