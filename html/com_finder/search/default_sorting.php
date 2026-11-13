<?php
/**
 * Sub-template: ordinamento dei risultati (opzione "Mostra ordinamento" di Smart Search).
 *
 * Elenco di link, uno per criterio; quello attivo ha aria-current. Gli URL del core
 * ($sortOrderField->url) perdono i filtri quando ne è selezionato più d'uno: da lì si
 * leggono solo o / od e si applicano all'URL corrente.
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/** @var \Joomla\Component\Finder\Site\View\Search\HtmlView $this */
?>
<nav class="search-sorting" aria-labelledby="search-sorting-label">
    <span class="search-sorting-label" id="search-sorting-label"><?php echo Text::_('COM_FINDER_SORT_BY'); ?></span>
    <ul class="search-sorting-list list-unstyled" role="list">
        <?php foreach ($this->sortOrderFields as $field) : ?>
            <?php
            $fieldUri = new Uri($field->url);
            $url      = TplAccessibileSearchHelper::urlWith(['o' => $fieldUri->getVar('o'), 'od' => $fieldUri->getVar('od')]);
            ?>
            <li>
                <a href="<?php echo $this->escape($url); ?>"<?php echo $field->active ? ' aria-current="true" class="active"' : ''; ?>><?php echo $this->escape($field->label); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
