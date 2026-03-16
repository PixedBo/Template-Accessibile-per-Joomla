# Contribuire al Template Accessibile per Joomla 5

Grazie per il tuo interesse nel contribuire a questo progetto! 🎉
Questo template nasce per fornire alle Pubbliche Amministrazioni (e non solo) una base solida, sicura e nativamente accessibile per Joomla 5+, sfruttando il framework Bootstrap Italia.

Per mantenere altissima la qualità del codice e garantire la conformità alle linee guida AgID e WCAG, ti chiediamo di seguire queste indicazioni prima di inviare una Pull Request (PR).

## 🛠 Linee Guida Tecniche (IMPORTANTE)

1. **Zero jQuery / Solo Vanilla JS:** Questo template è progettato per essere leggero e moderno. Non includere jQuery. Qualsiasi script personalizzato deve essere scritto in puro Javascript (Vanilla JS) ES6+.
2. **Accessibilità al primo posto:** Qualsiasi modifica all'HTML o al CSS **deve** rispettare le direttive WCAG 2.1 livello AA (preferibilmente AAA).
   - Assicurati di usare correttamente i tag semantici (`<nav>`, `<main>`, `<aside>`).
   - Verifica che i contrasti cromatici siano a norma.
   - Non rimuovere gli attributi `aria-` o i tag `.visually-hidden` essenziali per gli screen reader.
   - Il sito deve essere completamente navigabile da tastiera (tasto `TAB`).
3. **Internazionalizzazione (i18n):** Non inserire MAI testo statico all'interno dei file `.php` o `.html`. Usa sempre le costanti di lingua native di Joomla (es. `Text::_('TPL_ACCESSIBILE_MIO_TESTO')`) e aggiorna i file `.ini` nella cartella `language`.
4. **Bootstrap Italia:** Rispetta la documentazione ufficiale di [Bootstrap Italia](https://italia.github.io/bootstrap-italia/). Usa le loro classi di utilità invece di scrivere CSS personalizzato, quando possibile.

## 🤝 Come inviare una Pull Request

1. Fai un **Fork** di questo repository.
2. Crea un branch per la tua modifica (`git checkout -b feature/nome-mia-modifica` oppure `bugfix/nome-bug`).
3. Fai il commit delle tue modifiche (`git commit -m 'Aggiunta la funzionalità X'`). Sii descrittivo nei messaggi di commit.
4. Fai il push sul tuo branch (`git push origin feature/nome-mia-modifica`).
5. Apri una **Pull Request** verso il branch `main` di questo repository.

## 🐛 Segnalare un Bug
Se hai trovato un problema ma non sai come risolverlo scrivendo codice, apri una **Issue**. 
Cerca di essere il più dettagliato possibile:
- Indica la versione di Joomla e di PHP che stai usando.
- Descrivi i passaggi esatti per riprodurre il problema.
- Aggiungi screenshot se si tratta di un problema visivo.

Grazie per il tuo aiuto nel rendere il web un posto più accessibile per tutti!