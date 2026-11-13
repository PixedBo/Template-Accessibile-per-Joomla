<?php
/**
 * Modale di ricerca a tutta pagina, aperto dall'icona nell'header.
 *
 * Reso una sola volta da index.php, prima di </body>, quando il parametro
 * "ricerca_apertura" vale "modale". Contiene il campo di ricerca, il pulsante
 * "Cerca" e il link alla pagina di ricerca avanzata (la pagina risultati di
 * Smart Search, dove si trovano i filtri).
 *
 * Il componente modal è quello del bundle Bootstrap Italia: gestisce Esc,
 * il blocco del focus dentro il modale e il ritorno del focus sull'icona.
 * js/search-modal.js porta il focus sul campo all'apertura.
 *
 * Parametri ($displayData):
 *   url          string  URL della pagina risultati, già passato da Route
 *   suggestions  bool    suggerimenti durante la digitazione (asset caricati da index.php)
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

$tpl     = Factory::getApplication()->getTemplate(true);
$helpers = JPATH_SITE . '/templates/' . ($tpl->parent ?: $tpl->template) . '/helpers/';

if (!class_exists('TplAccessibileHelper')) {
    require_once $helpers . 'TplAccessibileHelper.php';
}

if (!class_exists('TplAccessibileSearchHelper')) {
    require_once $helpers . 'TplAccessibileSearchHelper.php';
}

$url         = (string) ($displayData['url'] ?? '');
$suggestions = !empty($displayData['suggestions']);

if ($url === '') {
    return;
}

$closeLabel = Text::_('TPL_ACCESSIBILE_SEARCH_MODAL_CLOSE');
?>
<div class="modal fade search-modal" id="search-modal" tabindex="-1" role="dialog" aria-labelledby="search-modal-title">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-lg-10 offset-lg-1">
                            <div class="search-modal-header">
                                <button type="button" class="btn search-modal-dismiss d-md-none" data-bs-dismiss="modal" aria-label="<?php echo $closeLabel; ?>">
                                    <svg class="icon icon-md" aria-hidden="true"><use href="<?php echo TplAccessibileHelper::spriteUrl('it-arrow-left'); ?>"></use></svg>
                                </button>
                                <h2 class="search-modal-title" id="search-modal-title"><?php echo Text::_('TPL_ACCESSIBILE_SEARCH'); ?></h2>
                                <button type="button" class="btn search-modal-dismiss d-none d-md-block ms-auto" data-bs-dismiss="modal" aria-label="<?php echo $closeLabel; ?>">
                                    <svg class="icon icon-md" aria-hidden="true"><use href="<?php echo TplAccessibileHelper::spriteUrl('it-close-big'); ?>"></use></svg>
                                </button>
                            </div>

                            <form role="search" method="get" action="<?php echo $url; ?>" class="search-modal-form<?php echo $suggestions ? ' js-finder-searchform' : ''; ?>">
                                <?php echo TplAccessibileSearchHelper::hiddenFields($url); ?>
                                <div class="search-field">
                                    <svg class="icon icon-md search-field-icon" aria-hidden="true"><use href="<?php echo TplAccessibileHelper::spriteUrl('it-search'); ?>"></use></svg>
                                    <label class="visually-hidden" for="search-modal-q"><?php echo Text::_('TPL_ACCESSIBILE_SEARCH_SITE'); ?></label>
                                    <input type="search" class="search-field-input<?php echo $suggestions ? ' js-finder-search-query' : ''; ?>" id="search-modal-q" name="q" placeholder="<?php echo Text::_('TPL_ACCESSIBILE_SEARCH_SITE'); ?>" autocomplete="off">
                                    <button type="submit" class="btn btn-primary"><?php echo Text::_('TPL_ACCESSIBILE_SEARCH'); ?></button>
                                </div>
                                <p class="search-modal-advanced">
                                    <a href="<?php echo $url; ?>"><?php echo Text::_('TPL_ACCESSIBILE_SEARCH_ADVANCED'); ?></a>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
