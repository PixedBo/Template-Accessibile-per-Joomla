<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_dpcalendar
 *
 * Layout alternativo "Lista eventi" per DPCalendar.
 * Designers Italia sezione 5.3 — pagina di secondo livello "Vivere il comune".
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/** @var \DigitalPeak\Component\DPCalendar\Site\View\List\HtmlView $this */

$app = Factory::getApplication();
$app->getLanguage()->load('tpl_templateaccessibileperjoomla', JPATH_SITE);

$_parentTpl = \Joomla\CMS\Factory::getApplication()->getTemplate(true)->parent
    ?: \Joomla\CMS\Factory::getApplication()->getTemplate();
require_once JPATH_SITE . '/templates/' . $_parentTpl . '/helpers/TplAccessibileHelper.php';

$params = $this->params;

// Parametri configurabili dal menu item
$mostraEvidenza  = (int) $params->get('mostra_evidenza', 0);
$evidenzaMax     = (int) $params->get('evidenza_max', 6);
$mostraFiltro    = (int) $params->get('mostra_filtro', 1);
$pageTitle       = trim((string) $params->get('page_title', ''));
$pageDescription = trim((string) $params->get('page_description', ''));
$mostraImmagine  = (int) $params->get('mostra_immagine_card', 1);
$mostraCategoria = (int) $params->get('mostra_categoria_card', 1);
$mostraLuogo     = (int) $params->get('mostra_luogo_card', 1);
$repetiInLista   = (int) $params->get('ripeti_in_lista', 0);
$troncaTesto     = (int) $params->get('tronca_testo', 0);

// DPCalendar list view espone $this->events; $this->items come fallback
$allEvents = $this->events ?? $this->items ?? [];

// Suddivisione in evidenza / lista in base alla property featured dell'evento
$featuredEvents    = [];
$nonFeaturedEvents = [];

foreach ($allEvents as $event) {
    if ((int) ($event->featured ?? 0) === 1) {
        $featuredEvents[] = $event;
    } else {
        $nonFeaturedEvents[] = $event;
    }
}

// Limita gli eventi in evidenza al massimo configurato
if (count($featuredEvents) > $evidenzaMax) {
    $featuredEvents = array_slice($featuredEvents, 0, $evidenzaMax);
}

// Fallback: mostra_evidenza=1 ma nessun evento featured → la fascia non appare
// e la lista mostra tutti gli eventi (non solo i non-featured)
$showEvidenza = $mostraEvidenza === 1 && !empty($featuredEvents);

// Lista: se ripeti_in_lista=1 mostra tutti; altrimenti solo i non-featured
if (!$showEvidenza) {
    $listaEvents = $allEvents;
} elseif ($repetiInLista) {
    $listaEvents = $allEvents;
} else {
    $listaEvents = $nonFeaturedEvents;
}

// Mesi localizzati (costanti già presenti nel language file del template)
$monthNames = [];
for ($m = 1; $m <= 12; $m++) {
    $monthNames[$m] = Text::_('TPL_ACCESSIBILE_MONTH_' . $m);
}

// Espone le variabili ai sub-template (condividono $this con il padre)
$this->featuredEvents  = $featuredEvents;
$this->listaEvents     = $listaEvents;
$this->showEvidenza    = $showEvidenza;
$this->monthNames      = $monthNames;
$this->mostraImmagine  = $mostraImmagine;
$this->mostraCategoria = $mostraCategoria;
$this->mostraLuogo     = $mostraLuogo;
$this->troncaTesto     = $troncaTesto;

// Stato filtro: apre il collapse automaticamente se c'è un filtro attivo.
// Usa la sessione DPCalendar (chiave fissa com_dpcalendar.listview.*) come fallback
// per le richieste GET successive al POST (paginazione, link diretti, F5).
$_fi           = $app->getInput()->get('filter', [], 'array');
$_li           = $app->getInput()->get('list', [], 'array');
$_dpFilter     = $app->getUserState('com_dpcalendar.listview.filter', []);
$_dpList       = $app->getUserState('com_dpcalendar.listview.list',   []);
$_dpFilter     = is_array($_dpFilter) ? $_dpFilter : [];
$_dpList       = is_array($_dpList)   ? $_dpList   : [];

$isFiltered = !empty($_fi['search'])      || !empty($_dpFilter['search'])
           || !empty($_li['start-date'])  || !empty($_dpList['start-date'])
           || !empty($_li['end-date'])    || !empty($_dpList['end-date'])
           || isset($_fi['calendars'])    || !empty($_dpFilter['calendars']);
?>

<div class="com-dpcalendar-list-eventi">

    <?php if ($pageTitle !== '' || $pageDescription !== '') : ?>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <div class="cmp-hero">
                        <section class="it-hero-wrapper bg-white align-items-start">
                            <div class="it-hero-text-wrapper pt-0 ps-0 pb-4 pb-lg-60">
                                <?php if ($pageTitle !== '') : ?>
                                    <h1 class="text-black hero-title"><?php echo $this->escape($pageTitle); ?></h1>
                                <?php endif; ?>
                                <?php if ($pageDescription !== '') : ?>
                                    <div class="hero-text"><?php echo $pageDescription; ?></div>
                                <?php endif; ?>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($this->showEvidenza) : ?>
        <?php echo $this->loadTemplate('evidenza'); ?>
    <?php endif; ?>

    <?php if ($mostraFiltro) : ?>
        <div class="container mt-4">
            <button class="btn <?php echo $isFiltered ? 'btn-primary' : 'btn-outline-primary'; ?> d-flex align-items-center gap-2"
                    type="button"
                    data-bs-toggle="collapse" data-bs-target="#eventi-filtro-collapse"
                    aria-expanded="<?php echo $isFiltered ? 'true' : 'false'; ?>"
                    aria-controls="eventi-filtro-collapse">
                <svg class="icon icon-sm" aria-hidden="true" focusable="false">
                    <use href="<?php echo TplAccessibileHelper::spriteUrl('it-funnel'); ?>"></use>
                </svg>
                <?php echo Text::_('TPL_ACCESSIBILE_DPCALENDAR_FILTER_TOGGLE'); ?>
            </button>
            <div class="collapse<?php echo $isFiltered ? ' show' : ''; ?>" id="eventi-filtro-collapse">
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-body">
                        <?php echo $this->loadTemplate('filtro'); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php echo $this->loadTemplate('lista'); ?>

</div>
