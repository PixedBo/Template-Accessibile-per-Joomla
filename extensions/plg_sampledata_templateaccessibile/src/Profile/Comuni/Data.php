<?php

/**
 * @package     Pixed.Plugin
 * @subpackage  SampleData.templateaccessibile
 *
 * @copyright   Copyright (C) 2025 Pixed Web Development. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Pixed\Plugin\SampleData\TemplateAccessibile\Profile\Comuni;

\defined('_JEXEC') or die;

/**
 * Definizioni del profilo "Sito di un Comune" (Modello Comuni di Designers Italia).
 *
 * Titoli e alias sono in italiano a prescindere dalla lingua dell'amministratore: sono i nomi
 * previsti dal modello, e alcuni ("Servizi", le voci della scheda servizio) sono controllati
 * alla lettera dall'App Valutazione Modelli.
 *
 * Alias e nomi dei campi sono le chiavi con cui il plugin riconosce ciò che ha già creato:
 * cambiarli qui significa creare elementi nuovi sui siti dove il plugin è già stato eseguito.
 */
final class Data
{
    public const ARTICLE_CONTEXT = 'com_content.article';
    public const EVENT_CONTEXT   = 'com_dpcalendar.event';

    public const ARTICLE_GROUP = 'Servizi - Modello Comuni';
    public const EVENT_GROUP   = 'Evento - Modello Comuni';

    public const PAGES_CATEGORY = 'pagine-istituzionali';

    public const MENU_MAIN      = 'comuni-principale';
    public const MENU_SECONDARY = 'comuni-secondario';
    public const MENU_FOOTER    = 'comuni-footer';

    /** Uso globale ("come da impostazioni del plugin del campo") per i parametri dei tipi di campo. */
    private const EDITOR   = ['buttons' => '', 'width' => '', 'height' => '', 'filter' => ''];
    private const TEXTAREA = ['rows' => '', 'cols' => '', 'maxlength' => '', 'filter' => ''];
    private const TEXT     = ['filter' => '', 'maxlength' => ''];
    private const URL      = ['schemes' => ['http', 'https'], 'relative' => '1', 'show_url' => '1'];

    /**
     * Alberatura delle categorie di com_content (doc/it/alberatura-categorie.md).
     */
    public static function categories(): array
    {
        $leaf = static fn (array $titles): array => array_map(static fn (string $title): array => ['title' => $title], $titles);

        return [
            'amministrazione' => ['title' => 'Amministrazione', 'children' => $leaf([
                'organi-di-governo'       => 'Organi di governo',
                'aree-amministrative'     => 'Aree amministrative',
                'uffici'                  => 'Uffici',
                'enti-e-fondazioni'       => 'Enti e fondazioni',
                'politici'                => 'Politici',
                'personale-amministrativo' => 'Personale amministrativo',
                'documenti-e-dati'        => 'Documenti e dati',
            ])],
            'novita' => ['title' => 'Novità', 'children' => $leaf([
                'notizie'    => 'Notizie',
                'comunicati' => 'Comunicati',
                'avvisi'     => 'Avvisi',
            ])],
            'servizi' => ['title' => 'Servizi', 'children' => $leaf([
                'agricoltura-e-pesca'                 => 'Agricoltura e pesca',
                'ambiente'                            => 'Ambiente',
                'anagrafe-e-stato-civile'             => 'Anagrafe e stato civile',
                'appalti-pubblici'                    => 'Appalti pubblici',
                'autorizzazioni'                      => 'Autorizzazioni',
                'catasto-e-urbanistica'               => 'Catasto e urbanistica',
                'cultura-e-tempo-libero'              => 'Cultura e tempo libero',
                'educazione-e-formazione'             => 'Educazione e formazione',
                'giustizia-e-sicurezza-pubblica'      => 'Giustizia e sicurezza pubblica',
                'imprese-e-commercio'                 => 'Imprese e commercio',
                'mobilita-e-trasporti'                => 'Mobilità e trasporti',
                'salute-benessere-e-assistenza'       => 'Salute, benessere e assistenza',
                'tributi-finanze-e-contravvenzioni'   => 'Tributi, finanze e contravvenzioni',
                'turismo'                             => 'Turismo',
                'vita-lavorativa'                     => 'Vita lavorativa',
            ])],
            'vivere-il-comune' => ['title' => 'Vivere il Comune', 'children' => $leaf([
                'eventi' => 'Eventi',
                'luoghi' => 'Luoghi',
            ])],
        ];
    }

    /**
     * Campi della Scheda Servizio (doc/it/custom-field-articoli.md).
     * 'param' è il parametro del template in cui va scritto l'ID del campo.
     *
     * @param   bool  $documentType  true se il tipo di campo "Documento" (Joomla 6.1+) è disponibile
     */
    public static function articleFields(bool $documentType): array
    {
        return [
            ['name' => 'comuni-servizio-stato', 'title' => 'Stato', 'type' => 'radio', 'param' => 'cf_stato', 'default_value' => '1',
                'fieldparams' => ['options' => [
                    'options0' => ['name' => 'Servizio attivo', 'value' => '1'],
                    'options1' => ['name' => 'Servizio non attivo', 'value' => '0'],
                ]]],
            ['name' => 'comuni-servizio-motivo-stato', 'title' => 'Motivo stato servizio', 'type' => 'textarea', 'param' => 'cf_motivo_stato_servizio', 'fieldparams' => self::TEXTAREA],
            ['name' => 'comuni-servizio-a-chi-e-rivolto', 'title' => 'A chi è rivolto', 'type' => 'editor', 'param' => 'cf_a_chi_rivolto', 'fieldparams' => self::EDITOR],
            ['name' => 'comuni-servizio-come-fare', 'title' => 'Come fare', 'type' => 'editor', 'param' => 'cf_come_fare', 'fieldparams' => self::EDITOR],
            ['name' => 'comuni-servizio-cosa-serve', 'title' => 'Cosa serve', 'type' => 'editor', 'param' => 'cf_cosa_serve', 'fieldparams' => self::EDITOR],
            ['name' => 'comuni-servizio-cosa-si-ottiene', 'title' => 'Cosa si ottiene', 'type' => 'editor', 'param' => 'cf_cosa_si_ottiene', 'fieldparams' => self::EDITOR],
            ['name' => 'comuni-servizio-tempi-e-scadenze', 'title' => 'Tempi e scadenze', 'type' => 'editor', 'param' => 'cf_tempi_scadenze', 'fieldparams' => self::EDITOR],
            ['name' => 'comuni-servizio-quanto-costa', 'title' => 'Quanto costa', 'type' => 'editor', 'param' => 'cf_quanto_costa', 'fieldparams' => self::EDITOR],
            ['name' => 'comuni-servizio-accesso-online', 'title' => 'Accesso online', 'type' => 'url', 'param' => 'cf_accedi_online', 'fieldparams' => self::URL],
            ['name' => 'comuni-servizio-accesso-online-testo', 'title' => 'Testo - Accesso Online', 'type' => 'text', 'param' => 'cf_accedi_online_testo', 'fieldparams' => self::TEXT],
            ['name' => 'comuni-servizio-prenota-appuntamento', 'title' => 'Prenota un appuntamento', 'type' => 'url', 'param' => 'cf_accedi_appuntamento', 'fieldparams' => self::URL],
            ['name' => 'comuni-servizio-condizioni', 'title' => 'Condizioni di servizio - Introduzione', 'type' => 'editor', 'param' => 'cf_condizioni', 'fieldparams' => self::EDITOR],
            $documentType
                ? ['name' => 'comuni-servizio-condizioni-file', 'title' => 'Condizioni di servizio - FILE', 'type' => 'media_document', 'param' => 'cf_condizioni_file', 'fieldparams' => ['directory' => '']]
                : ['name' => 'comuni-servizio-condizioni-file', 'title' => 'Condizioni di servizio - FILE', 'type' => 'url', 'param' => 'cf_condizioni_file', 'fieldparams' => self::URL],
        ];
    }

    /**
     * Subform "Lista Tempistiche" della Scheda Servizio.
     * La descrizione è un'Area di testo: il layout la stampa come testo semplice.
     */
    public static function articleDeadlines(): array
    {
        return [
            'field'     => ['name' => 'comuni-servizio-lista-tempistiche', 'title' => 'Lista Tempistiche', 'type' => 'subform', 'param' => 'cf_tempi_scadenze_lista'],
            'subfields' => [
                ['name' => 'comuni-servizio-tempistica-titolo', 'title' => 'Titolo', 'type' => 'text', 'param' => 'cf_tempi_lista_key_titolo', 'fieldparams' => self::TEXT],
                ['name' => 'comuni-servizio-tempistica-data', 'title' => 'Data', 'type' => 'calendar', 'param' => 'cf_tempi_lista_key_data', 'fieldparams' => ['showtime' => '0']],
                ['name' => 'comuni-servizio-tempistica-numero', 'title' => 'Numero giorni/mesi', 'type' => 'text', 'param' => 'cf_tempi_lista_key_numero', 'fieldparams' => self::TEXT],
                ['name' => 'comuni-servizio-tempistica-unita', 'title' => 'Unità di misura', 'type' => 'text', 'param' => 'cf_tempi_lista_key_unita', 'fieldparams' => self::TEXT],
                ['name' => 'comuni-servizio-tempistica-descrizione', 'title' => 'Descrizione', 'type' => 'textarea', 'param' => 'cf_tempi_lista_key_descrizione', 'fieldparams' => self::TEXTAREA],
            ],
        ];
    }

    /**
     * Campi semplici della Scheda Evento (doc/it/scheda-evento.md).
     */
    public static function eventFields(): array
    {
        return [
            ['name' => 'comuni-evento-a-chi-e-rivolto', 'title' => 'A chi è rivolto', 'type' => 'editor', 'param' => 'cf_evento_a_chi_rivolto', 'fieldparams' => self::EDITOR],
            ['name' => 'comuni-evento-video', 'title' => 'Video', 'type' => 'url', 'param' => 'cf_evento_video', 'fieldparams' => self::URL],
            ['name' => 'comuni-evento-ulteriori-informazioni', 'title' => 'Ulteriori informazioni', 'type' => 'editor', 'param' => 'cf_evento_ulteriori_info', 'fieldparams' => self::EDITOR],
        ];
    }

    /**
     * Subform della Scheda Evento. I sotto-campi sono nell'ordine in cui il template li legge
     * (per posizione): le chiavi esplicite nei parametri restano su "Automatico".
     * Allegati, Patrocinato da e Sponsor condividono gli stessi due sotto-campi.
     */
    public static function eventSubforms(): array
    {
        $link = [
            ['name' => 'comuni-evento-link-nome', 'title' => 'Nome', 'type' => 'text', 'fieldparams' => self::TEXT],
            ['name' => 'comuni-evento-link-indirizzo', 'title' => 'Indirizzo o file', 'type' => 'url', 'fieldparams' => self::URL],
        ];

        return [
            [
                'field'     => ['name' => 'comuni-evento-galleria', 'title' => 'Galleria di immagini', 'type' => 'subform', 'param' => 'cf_evento_galleria'],
                'subfields' => [
                    ['name' => 'comuni-evento-galleria-immagine', 'title' => 'Immagine', 'type' => 'media', 'fieldparams' => ['directory' => '', 'preview' => '', 'image_class' => '']],
                ],
            ],
            [
                'field'     => ['name' => 'comuni-evento-costi', 'title' => 'Costi', 'type' => 'subform', 'param' => 'cf_evento_costi'],
                'subfields' => [
                    ['name' => 'comuni-evento-costo-titolo', 'title' => 'Titolo', 'type' => 'text', 'fieldparams' => self::TEXT],
                    ['name' => 'comuni-evento-costo-prezzo', 'title' => 'Prezzo', 'type' => 'text', 'fieldparams' => self::TEXT],
                    ['name' => 'comuni-evento-costo-descrizione', 'title' => 'Descrizione', 'type' => 'textarea', 'fieldparams' => self::TEXTAREA],
                ],
            ],
            ['field' => ['name' => 'comuni-evento-allegati', 'title' => 'Allegati', 'type' => 'subform', 'param' => 'cf_evento_allegati'], 'subfields' => $link],
            [
                'field'     => ['name' => 'comuni-evento-contatti', 'title' => 'Contatti', 'type' => 'subform', 'param' => 'cf_evento_contatti'],
                'subfields' => [
                    ['name' => 'comuni-evento-contatto-nome', 'title' => 'Nome', 'type' => 'text', 'fieldparams' => self::TEXT],
                    ['name' => 'comuni-evento-contatto-telefono', 'title' => 'Telefono', 'type' => 'text', 'fieldparams' => self::TEXT],
                    ['name' => 'comuni-evento-contatto-email', 'title' => 'Email', 'type' => 'text', 'fieldparams' => self::TEXT],
                ],
            ],
            ['field' => ['name' => 'comuni-evento-patrocinato-da', 'title' => 'Patrocinato da', 'type' => 'subform', 'param' => 'cf_evento_patrocinato'], 'subfields' => $link],
            ['field' => ['name' => 'comuni-evento-sponsor', 'title' => 'Sponsor', 'type' => 'subform', 'param' => 'cf_evento_sponsor'], 'subfields' => $link],
        ];
    }

    /**
     * Voci del menu principale: Blog categoria con il layout alternativo del template.
     * 'category' è il percorso di alias della categoria; 'param' il parametro data-element.
     */
    public static function mainMenu(): array
    {
        return [
            ['alias' => 'amministrazione', 'title' => 'Amministrazione', 'category' => 'amministrazione', 'layout' => 'amministrazione', 'param' => 'de_management'],
            ['alias' => 'novita', 'title' => 'Novità', 'category' => 'novita', 'layout' => 'notizie', 'param' => 'de_news'],
            ['alias' => 'servizi', 'title' => 'Servizi', 'category' => 'servizi', 'layout' => 'servizi', 'param' => 'de_all_services'],
            ['alias' => 'vivere-il-comune', 'title' => 'Vivere il Comune', 'category' => 'vivere-il-comune', 'layout' => 'vivere', 'param' => 'de_live'],
        ];
    }

    /**
     * Pagine istituzionali: articoli segnaposto e relative voci del menu footer.
     * 'params' sono i parametri del template che puntano alla voce di menu della pagina.
     */
    public static function pages(): array
    {
        return [
            ['alias' => 'note-legali', 'title' => 'Note legali', 'layout' => 'note-legali', 'params' => ['de_legal_notes']],
            ['alias' => 'informativa-privacy', 'title' => 'Informativa privacy', 'params' => ['de_privacy_policy_link']],
            ['alias' => 'dichiarazione-di-accessibilita', 'title' => 'Dichiarazione di accessibilità', 'params' => ['de_accessibility_link']],
            ['alias' => 'domande-frequenti', 'title' => 'Domande frequenti', 'params' => ['de_faq', 'footer_faq']],
            ['alias' => 'contatti', 'title' => 'Contatti', 'params' => ['footer_contacts', 'menuitem_contatti']],
            ['alias' => 'prenotazione-appuntamento', 'title' => 'Prenotazione appuntamento', 'params' => ['footer_appointment_booking']],
            ['alias' => 'segnalazione-disservizio', 'title' => 'Segnalazione disservizio', 'params' => ['de_report_inefficiency', 'footer_report']],
        ];
    }

    public static function placeholderText(string $title): string
    {
        return '<p>Questa è una pagina segnaposto creata dai dati di esempio del Template Accessibile. '
            . 'Sostituisci questo testo con il contenuto reale della pagina «' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '» del tuo Comune.</p>';
    }

    /**
     * Contenuti dimostrativi. Gli alias iniziano con "esempio-" e i titoli con "Esempio:".
     */
    public static function demoService(): array
    {
        return [
            'category'  => 'servizi/anagrafe-e-stato-civile',
            'alias'     => 'esempio-richiesta-carta-identita',
            'title'     => 'Esempio: Richiesta della carta d\'identità elettronica',
            'introtext' => '<p>Servizio dimostrativo: richiedi il rilascio o il rinnovo della carta d\'identità elettronica (CIE).</p>',
            'fulltext'  => '<p>La carta d\'identità elettronica è il documento di riconoscimento dei cittadini italiani. Questo testo è un esempio e va sostituito.</p>',
            'values'    => [
                'comuni-servizio-stato'               => '1',
                'comuni-servizio-a-chi-e-rivolto'     => '<p>A tutti i cittadini residenti nel Comune.</p>',
                'comuni-servizio-come-fare'           => '<p>Prenota un appuntamento allo sportello Anagrafe e presentati con i documenti richiesti.</p>',
                'comuni-servizio-cosa-serve'          => '<ul><li>Una fototessera recente</li><li>Il documento scaduto o in scadenza</li><li>La tessera sanitaria</li></ul>',
                'comuni-servizio-cosa-si-ottiene'     => '<p>La carta d\'identità elettronica, spedita all\'indirizzo indicato entro sei giorni lavorativi.</p>',
                'comuni-servizio-tempi-e-scadenze'    => '<p>La richiesta può essere presentata a partire da 180 giorni prima della scadenza del documento.</p>',
                'comuni-servizio-quanto-costa'        => '<p>Il costo è di 22,00 euro, da pagare allo sportello.</p>',
                'comuni-servizio-accesso-online'      => 'https://www.prenotazionicie.interno.gov.it/',
                'comuni-servizio-accesso-online-testo' => 'Prenota online',
                'comuni-servizio-condizioni'          => '<p>Per conoscere i dettagli di scadenze, requisiti e altre informazioni importanti, leggi i termini e le condizioni di servizio.</p>',
            ],
            'deadlines' => [
                ['comuni-servizio-tempistica-titolo' => 'Rilascio del documento', 'comuni-servizio-tempistica-numero' => '6', 'comuni-servizio-tempistica-unita' => 'giorni', 'comuni-servizio-tempistica-descrizione' => 'Giorni lavorativi dalla richiesta allo sportello.'],
            ],
        ];
    }

    public static function demoNews(): array
    {
        return [
            [
                'category'  => 'novita/notizie',
                'alias'     => 'esempio-nuovo-sito-del-comune',
                'title'     => 'Esempio: Online il nuovo sito del Comune',
                'introtext' => '<p>Notizia dimostrativa: il sito è stato rinnovato secondo il modello di sito comunale di Designers Italia.</p>',
                'fulltext'  => '<p>Il nuovo sito è più accessibile, più semplice da consultare da telefono e organizzato per servizi. Questo testo è un esempio e va sostituito.</p>',
            ],
            [
                'category'  => 'novita/avvisi',
                'alias'     => 'esempio-chiusura-uffici',
                'title'     => 'Esempio: Chiusura degli uffici comunali',
                'introtext' => '<p>Avviso dimostrativo: gli uffici comunali resteranno chiusi al pubblico nella giornata indicata.</p>',
                'fulltext'  => '<p>I servizi online restano disponibili. Questo testo è un esempio e va sostituito.</p>',
            ],
        ];
    }
}
