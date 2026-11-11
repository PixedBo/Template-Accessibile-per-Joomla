<?php
/**
 * Sub-template: campo di ricerca della pagina risultati.
 *
 * $this->getFields() restituisce i campi nascosti (option, view, Itemid, f) senza
 * q, t e o: il testo arriva dal campo, i filtri dalle caselle di default_filtri.php.
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

/** @var \Joomla\Component\Finder\Site\View\Search\HtmlView $this */

$suggestions = (int) $this->tplParams->get('ricerca_suggerimenti', 0) === 1;

if ($suggestions) {
    TplAccessibileSearchHelper::useSuggestions();
}
?>
<form action="<?php echo Route::_($this->query->toUri()); ?>" method="get" role="search" id="search-page-form" class="search-page-form<?php echo $suggestions ? ' js-finder-searchform' : ''; ?>">
    <?php echo $this->getFields(); ?>
    <div class="search-field">
        <svg class="icon icon-md search-field-icon" aria-hidden="true"><use href="<?php echo TplAccessibileHelper::spriteUrl('it-search'); ?>"></use></svg>
        <label class="visually-hidden" for="search-page-q"><?php echo Text::_('TPL_ACCESSIBILE_SEARCH_SITE'); ?></label>
        <input type="search" class="search-field-input<?php echo $suggestions ? ' js-finder-search-query' : ''; ?>" id="search-page-q" name="q" value="<?php echo $this->escape($this->query->input); ?>" placeholder="<?php echo Text::_('TPL_ACCESSIBILE_SEARCH_SITE'); ?>" autocomplete="off">
        <button type="submit" class="btn btn-primary"><?php echo Text::_('TPL_ACCESSIBILE_SEARCH'); ?></button>
    </div>
</form>
