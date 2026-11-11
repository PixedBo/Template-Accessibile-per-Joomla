<?php
/**
 * Sub-template: numero di risultati, "forse cercavi", ordinamento, elenco e paginazione.
 *
 * I link "forse cercavi" e quelli dell'ordinamento sono ricostruiti con
 * TplAccessibileSearchHelper::urlWith(): Query::toUri() del core conserva un solo t[]
 * e farebbe perdere i filtri quando ne è selezionato più d'uno.
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/** @var \Joomla\Component\Finder\Site\View\Search\HtmlView $this */

$total = (int) $this->total;
?>
<div class="search-results-summary">
    <p class="search-results-count">
        <?php if ($total === 1) : ?>
            <?php echo Text::_('TPL_ACCESSIBILE_SEARCH_RESULTS_ONE'); ?>
        <?php else : ?>
            <?php echo Text::sprintf('TPL_ACCESSIBILE_SEARCH_RESULTS_N', '<strong>' . $total . '</strong>'); ?>
        <?php endif; ?>
    </p>
    <?php if ($this->hasFilters) : ?>
        <a class="search-results-clear" href="<?php echo $this->escape(TplAccessibileSearchHelper::urlWith(['t' => null])); ?>"><?php echo Text::_('TPL_ACCESSIBILE_SEARCH_FILTERS_CLEAR'); ?></a>
    <?php endif; ?>
</div>

<?php if ($this->suggested && $this->params->get('show_suggested_query', 1)) : ?>
    <p id="search-query-explained" class="search-results-suggested">
        <?php $link = '<a href="' . $this->escape(TplAccessibileSearchHelper::urlWith(['q' => $this->suggested])) . '">' . $this->escape($this->suggested) . '</a>'; ?>
        <?php echo Text::sprintf('COM_FINDER_SEARCH_SIMILAR', $link); ?>
    </p>
<?php endif; ?>

<?php if ($total === 0) : ?>
    <div id="search-result-empty" class="search-results-empty">
        <h3 class="h5"><?php echo Text::_('COM_FINDER_SEARCH_NO_RESULTS_HEADING'); ?></h3>
        <?php $multilang = Factory::getApplication()->getLanguageFilter() ? '_MULTILANG' : ''; ?>
        <p><?php echo Text::sprintf('COM_FINDER_SEARCH_NO_RESULTS_BODY' . $multilang, $this->escape($this->query->input)); ?></p>
    </div>
    <?php return; ?>
<?php endif; ?>

<?php if ($this->params->get('show_sort_order', 0) && !empty($this->sortOrderFields) && !empty($this->results)) : ?>
    <div id="search-sorting" class="search-results-sorting">
        <?php echo $this->loadTemplate('sorting'); ?>
    </div>
<?php endif; ?>

<?php if (!empty($this->query->highlight) && $this->params->get('highlight_terms', 1)) : ?>
    <?php
    // Al massimo 10 termini evidenziati, come nel layout del core
    $this->getDocument()->getWebAssetManager()->useScript('highlight');
    $this->getDocument()->addScriptOptions(
        'highlight',
        [[
            'class'     => 'js-highlight',
            'highLight' => array_slice($this->query->highlight, 0, 10),
        ]]
    );
    ?>
<?php endif; ?>

<ul id="search-result-list" class="js-highlight search-results-list list-unstyled" role="list">
    <?php $this->baseUrl = Uri::getInstance()->toString(['scheme', 'host', 'port']); ?>
    <?php foreach ($this->results as $i => $result) : ?>
        <?php $this->result = &$result; ?>
        <?php $this->result->counter = $i + 1; ?>
        <?php $layout = $this->getLayoutFile($this->result->layout); ?>
        <?php echo $this->loadTemplate($layout); ?>
    <?php endforeach; ?>
</ul>

<?php if ($this->params->get('show_pagination', 1) > 0) : ?>
    <div class="search-results-pagination">
        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>
<?php endif; ?>
