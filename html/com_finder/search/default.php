<?php
/**
 * Pagina risultati di Smart Search: campo di ricerca in alto, filtri a sinistra,
 * risultati a destra.
 *
 * Un solo form GET (#search-page-form) contiene il campo di ricerca; le caselle dei
 * filtri stanno nella colonna laterale e vi sono collegate con l'attributo form="".
 * I filtri sono nodi della tassonomia di Smart Search inviati come t[]:
 * vedi TplAccessibileSearchHelper::filterGroups().
 *
 * Sotto-template: default_form, default_filtri, default_results, default_result,
 * default_sorting.
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/** @var \Joomla\Component\Finder\Site\View\Search\HtmlView $this */

$tpl = Factory::getApplication()->getTemplate(true);
require_once JPATH_SITE . '/templates/' . ($tpl->parent ?: $tpl->template) . '/helpers/TplAccessibileHelper.php';
require_once JPATH_SITE . '/templates/' . ($tpl->parent ?: $tpl->template) . '/helpers/TplAccessibileSearchHelper.php';

// Id dei nodi attivi: $this->query->filters è [ramo => [id => titolo]]
$selected = [];

foreach ((array) $this->query->filters as $nodes) {
    $selected = array_merge($selected, array_keys((array) $nodes));
}

$this->tplParams    = $tpl->params;
$this->filterGroups = TplAccessibileSearchHelper::filterGroups($tpl->params, $selected);
$this->hasFilters   = !empty($selected);
$hasSidebar         = !empty($this->filterGroups);

// Filtri scelti ma nessun testo, e Smart Search non ammette la ricerca senza testo
$needsTerm = $this->query->search !== true && $this->hasFilters && trim((string) $this->query->input) === '';
?>
<div class="com-finder finder search-page">
    <?php if ($this->params->get('show_page_heading')) : ?>
        <h1 class="search-page-title">
            <?php echo $this->escape($this->params->get('page_heading') ?: $this->params->get('page_title')); ?>
        </h1>
    <?php endif; ?>

    <div id="search-form" class="com-finder__form">
        <?php echo $this->loadTemplate('form'); ?>
    </div>

    <div class="row">
        <?php if ($hasSidebar) : ?>
            <div class="col-12 col-lg-3">
                <?php echo $this->loadTemplate('filtri'); ?>
            </div>
        <?php endif; ?>

        <div class="col-12 <?php echo $hasSidebar ? 'col-lg-8 offset-lg-1' : 'col-lg-9'; ?>">
            <?php if ($this->query->search === true) : ?>
                <section id="search-results" class="com-finder__results" aria-labelledby="search-results-title">
                    <h2 class="visually-hidden" id="search-results-title"><?php echo Text::_('TPL_ACCESSIBILE_SEARCH_RESULTS_HEADING'); ?></h2>
                    <?php echo $this->loadTemplate('results'); ?>
                </section>
            <?php elseif ($needsTerm) : ?>
                <p class="search-page-hint"><?php echo Text::_('TPL_ACCESSIBILE_SEARCH_NEEDS_TERM'); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
