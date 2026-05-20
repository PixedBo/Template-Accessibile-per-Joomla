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
use Joomla\CMS\HTML\HTMLHelper;

$_parentTpl = \Joomla\CMS\Factory::getApplication()->getTemplate(true)->parent
    ?: \Joomla\CMS\Factory::getApplication()->getTemplate();
require_once JPATH_SITE . '/templates/' . $_parentTpl . '/helpers/TplAccessibileHelper.php';

Factory::getApplication()->getLanguage()->load('tpl_templateaccessibileperjoomla', JPATH_SITE);

if (empty($list)) {
    return;
}

// Parametro per troncare l'intro
$maxChars = 160;

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
<div class="row g-4">
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
        $categoryLink = $item->catid ? Route::_(ContentRouteHelper::getCategoryRoute($item->catid, $item->language)) : '';

        $displayDate = '';
        if (!empty($item->publish_up)) {
            $displayDate = strtoupper(Factory::getDate($item->publish_up)->format('d M y'));
        }
    ?>
    <div class="col-md-6 col-xl-4">
        <div class="card-wrapper border border-light rounded shadow-sm">
            <div class="card no-after rounded">
                <?php if ($imgUrl) : ?>
                    <div class="img-responsive-wrapper">
                        <div class="img-responsive img-responsive-panoramic">
                            <figure class="img-wrapper">
                                <a href="<?= $link; ?>">
                                    <img src="<?= htmlspecialchars($imgUrl, ENT_QUOTES, 'UTF-8'); ?>"
                                         alt="<?= htmlspecialchars($imgAlt, ENT_QUOTES, 'UTF-8'); ?>"
                                         title="<?= htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?>">
                                </a>
                            </figure>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="card-body">
                    <div class="category-top">
                        <?php if ($item->category_title) : ?>
                            <?php if ($categoryLink) : ?>
                                <a class="category text-decoration-none" href="<?= $categoryLink; ?>">
                                    <?= strtoupper(htmlspecialchars($item->category_title, ENT_QUOTES, 'UTF-8')); ?>
                                </a>
                            <?php else : ?>
                                <span class="category">
                                    <?= strtoupper(htmlspecialchars($item->category_title, ENT_QUOTES, 'UTF-8')); ?>
                                </span>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($displayDate) : ?>
                            <span class="data"><?= $displayDate; ?></span>
                        <?php endif; ?>
                    </div>

                    <a class="text-decoration-none" href="<?= $link; ?>">
                        <<?= $itemHeading ?> class="card-title">
                            <?= htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?>
                        </<?= $itemHeading ?>>
                    </a>

                    <?php if ($introTruncated !== '') : ?>
                        <p class="card-text text-secondary">
                            <?= htmlspecialchars($introTruncated, ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>