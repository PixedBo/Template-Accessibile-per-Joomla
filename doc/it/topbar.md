# Topbar (banda slim superiore)

La topbar è la striscia sottile nella parte più alta della pagina, sopra all'header principale.

## Cosa contiene

- **Sinistra**: nome della regione (o ente sovraordinato) con link al sito della regione.
- **Destra**: posizione modulo `selezione-lingua` — pensata per un modulo di cambio lingua (es. `mod_languages`).

## Configurazione

Il contenuto della sinistra si configura nei parametri del template:

| Parametro | Campo |
|-----------|-------|
| Nome regione | `nomeregione` (scheda Generale) |
| Link regione | `linkregione` (scheda Generale) |

Se `linkregione` è valorizzato, il nome della regione diventa un link cliccabile. Se è vuoto, appare solo il testo.

> TODO – inserire screenshot della topbar con nome regione e link evidenziati

## Posizione modulo: `selezione-lingua`

Assegna un modulo a questa posizione se il sito è multilingua. Il modulo tipicamente usato è `mod_languages` (incluso in Joomla).

> TODO – inserire screenshot della posizione `selezione-lingua` nel Gestione Moduli con il menu a tendina della posizione

## Note

- Se `nomeregione` è vuoto e nessun modulo è assegnato a `selezione-lingua`, la topbar rimane visibile ma vuota.
- La topbar non è configurabile in colore: segue il colore primario del template.
