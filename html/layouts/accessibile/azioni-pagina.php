<?php
/**
 * Partial condiviso: menu "Condividi" e "Vedi azioni" dell'intestazione di pagina
 * (articolo, scheda servizio, scheda evento).
 *
 * Uso:
 *   echo LayoutHelper::render('accessibile.azioni-pagina', [
 *       'title'     => $titolo,          // obbligatorio: oggetto dell'email
 *       'url'       => Uri::current(),   // facoltativo
 *       'showPrint' => true,             // facoltativo
 *       'showEmail' => true,             // facoltativo
 *       'editHtml'  => '',               // facoltativo: output già reso di joomla.content.icons
 *       'idSuffix'  => 'page',           // facoltativo: per ID univoci se il partial è usato più volte
 *   ]);
 *
 * Markup: dropdown di Bootstrap Italia (btn-dropdown + link-list). Le voci hanno la classe
 * "dropdown-item": è quella che il bundle usa per la navigazione con le frecce.
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/** @var array $displayData */

if (!class_exists('TplAccessibileHelper')) {
    $tpl = Factory::getApplication()->getTemplate(true);
    require_once JPATH_SITE . '/templates/' . ($tpl->parent ?: $tpl->template) . '/helpers/TplAccessibileHelper.php';
}

$title     = (string) ($displayData['title'] ?? '');
$url       = (string) ($displayData['url'] ?? Uri::current());
$showPrint = (bool) ($displayData['showPrint'] ?? true);
$showEmail = (bool) ($displayData['showEmail'] ?? true);
$editHtml  = (string) ($displayData['editHtml'] ?? '');
$suffix    = preg_replace('/[^a-z0-9_-]/i', '', (string) ($displayData['idSuffix'] ?? 'page')) ?: 'page';

$esc = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

$encodedUrl = rawurlencode($url);
$newWindow  = Text::_('TPL_ACCESSIBILE_NEW_WINDOW');

$shareLinks = [
    ['icon' => 'it-facebook', 'label' => Text::_('TPL_ACCESSIBILE_SHARE_FACEBOOK'), 'href' => 'https://www.facebook.com/sharer/sharer.php?u=' . $encodedUrl],
    ['icon' => 'it-twitter',  'label' => Text::_('TPL_ACCESSIBILE_SHARE_X'),        'href' => 'https://twitter.com/intent/tweet?text=' . $encodedUrl],
    ['icon' => 'it-linkedin', 'label' => Text::_('TPL_ACCESSIBILE_SHARE_LINKEDIN'), 'href' => 'https://www.linkedin.com/shareArticle?url=' . $encodedUrl],
    ['icon' => 'it-whatsapp', 'label' => Text::_('TPL_ACCESSIBILE_SHARE_WHATSAPP'), 'href' => 'https://api.whatsapp.com/send?text=' . $encodedUrl],
];

$mailto = 'mailto:?subject=' . rawurlencode($title) . '&body=' . $encodedUrl;

$shareId   = 'shareActions-' . $suffix;
$actionsId = 'viewActions-' . $suffix;
?>
<div class="d-flex flex-wrap align-items-center gap-3">

    <div class="dropdown d-inline">
        <button class="btn btn-dropdown dropdown-toggle text-decoration-underline d-inline-flex align-items-center"
                type="button" id="<?php echo $shareId; ?>"
                data-bs-toggle="dropdown" aria-expanded="false">
            <svg class="icon icon-primary icon-sm me-1" aria-hidden="true"><use href="<?php echo TplAccessibileHelper::spriteUrl('it-share'); ?>"></use></svg>
            <small><?php echo Text::_('TPL_ACCESSIBILE_SHARE'); ?></small>
        </button>
        <div class="dropdown-menu shadow-lg" aria-labelledby="<?php echo $shareId; ?>">
            <div class="link-list-wrapper">
                <ul class="link-list">
                    <?php foreach ($shareLinks as $link) : ?>
                        <li>
                            <a class="dropdown-item list-item left-icon" href="<?php echo $esc($link['href']); ?>"
                               target="_blank" rel="noopener noreferrer">
                                <svg class="icon icon-primary icon-sm left" aria-hidden="true"><use href="<?php echo TplAccessibileHelper::spriteUrl($link['icon']); ?>"></use></svg>
                                <span><?php echo $esc($link['label']); ?> <span class="visually-hidden"><?php echo $esc($newWindow); ?></span></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <?php if ($showPrint || $showEmail) : ?>
    <div class="dropdown d-inline">
        <button class="btn btn-dropdown dropdown-toggle text-decoration-underline d-inline-flex align-items-center"
                type="button" id="<?php echo $actionsId; ?>"
                data-bs-toggle="dropdown" aria-expanded="false">
            <svg class="icon icon-primary icon-sm me-1" aria-hidden="true"><use href="<?php echo TplAccessibileHelper::spriteUrl('it-more-items'); ?>"></use></svg>
            <small><?php echo Text::_('TPL_ACCESSIBILE_VIEW_ACTIONS'); ?></small>
        </button>
        <div class="dropdown-menu shadow-lg" aria-labelledby="<?php echo $actionsId; ?>">
            <div class="link-list-wrapper">
                <ul class="link-list">
                    <?php if ($showPrint) : ?>
                        <li>
                            <?php // <a role="button">: eredita lo stile delle voci link-list di Bootstrap Italia (definito solo per <a>).
                                  // Invio è nativo sul link; Spazio va gestito a mano perché il ruolo dichiarato è "button". ?>
                            <a class="dropdown-item list-item left-icon" href="#" role="button"
                               onclick="window.print(); return false;"
                               onkeydown="if (event.key === ' ') { event.preventDefault(); this.click(); }">
                                <svg class="icon icon-primary icon-sm left" aria-hidden="true"><use href="<?php echo TplAccessibileHelper::spriteUrl('it-print'); ?>"></use></svg>
                                <span><?php echo Text::_('TPL_ACCESSIBILE_PRINT'); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if ($showEmail) : ?>
                        <li>
                            <a class="dropdown-item list-item left-icon" href="<?php echo $esc($mailto); ?>">
                                <svg class="icon icon-primary icon-sm left" aria-hidden="true"><use href="<?php echo TplAccessibileHelper::spriteUrl('it-mail'); ?>"></use></svg>
                                <span><?php echo Text::_('TPL_ACCESSIBILE_SEND'); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($editHtml !== '') : ?>
        <div class="d-inline-block"><?php echo $editHtml; ?></div>
    <?php endif; ?>

</div>
