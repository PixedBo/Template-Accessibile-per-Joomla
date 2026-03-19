<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles
 *
 * Layout alternativo per Modello Comuni (3 articoli a teaser)
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper as ContentRouteHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

if (empty($list)) {
    return;
}

// Parametro per troncare l'intro
$maxChars = 160;

// URL base per i percorsi SVG
$template  = Factory::getApplication()->getTemplate();
$baseurl   = Uri::base(true);
$spriteUrl = $baseurl . '/templates/' . $template . '/svg/sprites.svg';

// Estraiamo il parametro del tag per il titolo (default: h4)
$itemHeading = $params->get('item_heading', 'h4');

$truncate = static function (string $text, int $limit): string {
    if ($limit <= 0 || mb_strlen($text) <= $limit) return $text;
    return rtrim(mb_substr($text, 0, $limit)) . '…';
};

$cleanText = static function (?string $html): string {
    if (!$html) return '';
    $text = trim(strip_tags($html));
    return preg_replace('/\s+/u', ' ', $text) ?: '';
};

?>
<div class="card-wrapper px-0 card-teaser-wrapper card-teaser-wrapper-equal card-teaser-block-3">
    <?php 
    // Mostriamo fino a un massimo di 3 articoli
    $articlesToDisplay = array_slice($list, 0, 3);
    
    foreach ($articlesToDisplay as $index => $item) : 
        
        $images = json_decode($item->images ?? '{}');
        $imgUrl = $images->image_intro ?? '';
        $imgAlt = $images->image_intro_alt ?? $item->title;
        
        $intro = $cleanText($item->introtext ?? '');
        $introTruncated = $truncate($intro, $maxChars);

        // Routing sicuro
        $link = $item->link ?? Route::_(ContentRouteHelper::getArticleRoute($item->slug ?? $item->id, $item->catid, $item->language));
        
        // Regola originale: se è la prima card, e ha l'immagine, applichiamo lo stile con immagine
        $isFirstAndHasImage = ($index === 0 && !empty($imgUrl));
    ?>
    
    <?php if ($isFirstAndHasImage) : ?>
        <div class="card card-teaser card-teaser-image card-flex no-after rounded shadow-sm border border-light mb-0">
            <div class="card-image-wrapper with-read-more">
                <div class="card-body p-3 pb-5">
                    <div class="category-top">
                        <span class="title-xsmall-semi-bold fw-semibold">
                            <?= htmlspecialchars($item->category_title ?? '', ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </div>
                    <<?= $itemHeading ?> class="card-title text-paragraph-medium u-grey-light">
                        <?= htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?>
                    </<?= $itemHeading ?>>
                    <?php if ($introTruncated !== '') : ?>
                        <p class="text-paragraph-card u-grey-light m-0"><?= htmlspecialchars($introTruncated, ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endif; ?>
                </div>

                <div class="card-image card-image-rounded pb-5">
                    <img src="<?= htmlspecialchars($imgUrl, ENT_QUOTES, 'UTF-8'); ?>"
                         alt="<?= htmlspecialchars($imgAlt, ENT_QUOTES, 'UTF-8'); ?>">
                </div>
            </div>

            <a class="read-more ps-3" href="<?= $link; ?>">
                <span class="text"><?= Text::_('JGLOBAL_READ_MORE') ?: 'Vai alla pagina'; ?></span>
                <svg class="icon" aria-hidden="true">
                    <use href="<?= $spriteUrl ?>#it-arrow-right"></use>
                </svg>
                <span class="visually-hidden"><?= Text::_('JGLOBAL_READ_MORE'); ?></span>
            </a>
        </div>
        
    <?php else : ?>
        <div class="card card-teaser no-after rounded shadow-sm mb-0 border border-light">
            <div class="card-body pb-5">
                <div class="category-top">
                    <span class="title-xsmall-semi-bold fw-semibold">
                        <?= htmlspecialchars($item->category_title ?? '', ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>
                <<?= $itemHeading ?> class="card-title text-paragraph-medium u-grey-light">
                    <?= htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?>
                </<?= $itemHeading ?>>
                <?php if ($introTruncated !== '') : ?>
                    <p class="text-paragraph-card u-grey-light m-0"><?= htmlspecialchars($introTruncated, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endif; ?>
            </div>
            <a class="read-more" href="<?= $link; ?>">
                <span class="text"><?= Text::_('JGLOBAL_READ_MORE') ?: 'Vai alla pagina'; ?></span>
                <svg class="icon ms-0" aria-hidden="true">
                    <use href="<?= $spriteUrl ?>#it-arrow-right"></use>
                </svg>
                <span class="visually-hidden"><?= Text::_('JGLOBAL_READ_MORE'); ?></span>
            </a>
        </div>
    <?php endif; ?>
    
    <?php endforeach; ?>
</div>