<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles
 *
 * Layout alternativo per Modello Comuni (Evidenza Singolo / Slideshow)
 * Totalmente Accessibile (WAI-ARIA WCAG 2.1)
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\Component\Tags\Site\Helper\RouteHelper as TagsRouteHelper;
use Joomla\CMS\Language\Text;
use Joomla\Component\Content\Site\Helper\RouteHelper as ContentRouteHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

if (empty($list)) {
    return;
}

if (!class_exists('ModelloPAHelper')) {
    require_once JPATH_THEMES . '/' . Factory::getApplication()->getTemplate() . '/helpers/ModelloPAHelper.php';
}

$totalArticles = count($list);
$app           = Factory::getApplication();
$template      = $app->getTemplate();
$spriteUrl     = Uri::base(true) . '/templates/' . $template . '/svg/sprites.svg';

// Estraiamo il parametro del tag per il titolo (default: h4 come da XML nativo)
$itemHeading   = $params->get('item_heading', 'h4');

// ==============================================================================
// LOGICA 1: SE C'È UN SOLO ARTICOLO -> LAYOUT STATICO NATIVO (NO SLIDER)
// ==============================================================================
if ($totalArticles === 1) : 
    $item = $list[0];

    $publishedOn = $item->publish_up ?: $item->created;
    $niceDate    = HTMLHelper::_('date', $publishedOn, Text::_('DATE_FORMAT_LC3')); 

    $safeLimit = 1000;
    $rawIntro  = $item->introtext ?? '';
    $plainLen  = mb_strlen(trim(strip_tags($rawIntro)));
    if ($plainLen > $safeLimit) {
        $intro = rtrim(mb_substr(trim(strip_tags($rawIntro)), 0, $safeLimit)) . '…';
    } else {
        $intro = $rawIntro;
    }

    $images = json_decode($item->images ?? '{}');
    $imgUrl   = $images->image_intro ?? '';
    $imgAlt   = !empty($images->image_intro_alt) ? $images->image_intro_alt : Text::sprintf('TPL_ACCESSIBILE_COVER_IMAGE_ALT', $item->title);
    $imgTitle = $images->image_intro_caption ?? $item->title;

    $tags = [];
    if (!empty($item->tags->itemTags)) {
        $tags = $item->tags->itemTags;
    } else {
        try {
            $tagsHelper = new \Joomla\CMS\Helper\TagsHelper;
            $tags = $tagsHelper->getItemTags('com_content.article', (int) $item->id) ?? [];
        } catch (\Throwable $e) {}
    }

    $leftColClass = $imgUrl ? 'col-lg-6 order-2 order-lg-1' : 'col-12';
    $link = $item->link ?? Route::_(ContentRouteHelper::getArticleRoute($item->slug ?? $item->id, $item->catid, $item->language));
    $deAttr = ModelloPAHelper::attributeFor((int) ($item->catid ?? 0));
    ?>

    <?php if (empty($module->showtitle)) :
        $sectionHeading = !empty($module->title) ? $module->title : Text::_('TPL_ACCESSIBILE_FEATURED_ARTICLES');
    ?>
        <h2 class="visually-hidden"><?= htmlspecialchars($sectionHeading, ENT_QUOTES, 'UTF-8') ?></h2>
    <?php endif; ?>

    <div class="row align-items-center">
        <div class="<?= $leftColClass ?>">
            <div class="card mb-0 shadow-none bg-transparent">
                <div class="card-body pb-3 px-0">
                    <div class="category-top">
                        <svg class="icon icon-sm" aria-hidden="true">
                            <use href="<?= $spriteUrl ?>#it-calendar"></use>
                        </svg>
                        <span class="visually-hidden"><?php echo Text::_('TPL_ACCESSIBILE_PUBLISH_DATE_AND_CATEGORY'); ?></span>
                        
                        <?php if (!empty($item->category_title)) : ?>
                            <a class="text-decoration-none" href="<?= Route::_(ContentRouteHelper::getCategoryRoute($item->catid)); ?>">
                                <span class="title-xsmall-semi-bold fw-semibold">
                                    <?= htmlspecialchars($item->category_title, ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </a>
                        <?php endif; ?>

                        <?php if (!empty($niceDate)) : ?>
                            <span class="data fw-normal"><?= htmlspecialchars($niceDate, ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php endif; ?>
                    </div>

                    <a href="<?= $link; ?>" class="text-decoration-none"<?= $deAttr; ?>>
                        <<?= $itemHeading ?> class="card-title"><?= htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?></<?= $itemHeading ?>>
                    </a>

                    <?php if ($intro !== '') : ?>
                        <p class="mb-3 pt-2 lora"><?= $intro; ?></p>
                    <?php endif; ?>

                    <?php if (!empty($tags)) : ?>
                        <ul class="mod-pa-chips-list d-flex flex-wrap gap-1 list-unstyled mb-0" aria-label="<?php echo Text::_('TPL_ACCESSIBILE_RELATED_TOPICS'); ?>">
                            <?php foreach ($tags as $tag) :
                                $tagTitle = $tag->title ?? '';
                                try {
                                    $tagLink = Route::_(TagsRouteHelper::getTagRoute($tag->tag_id));
                                } catch (\Throwable $e) {
                                    $tagLink = '';
                                }
                            ?>
                                <li>
                                    <a class="chip chip-simple" <?= $tagLink ? 'href="' . htmlspecialchars($tagLink, ENT_QUOTES, 'UTF-8') . '"' : '' ?>>
                                        <span class="chip-label"><?= htmlspecialchars($tagTitle, ENT_QUOTES, 'UTF-8'); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if ($imgUrl) : ?>
            <div class="col-lg-6 order-1 order-lg-2 px-0 px-lg-3">
                <img src="<?= htmlspecialchars($imgUrl, ENT_QUOTES, 'UTF-8'); ?>"
                     title="<?= htmlspecialchars($imgTitle, ENT_QUOTES, 'UTF-8'); ?>"
                     alt="<?= htmlspecialchars($imgAlt, ENT_QUOTES, 'UTF-8'); ?>"
                     class="img-fluid mod-pa-featured-img rounded shadow-sm" />
            </div>
        <?php endif; ?>
    </div>

<?php
// ==============================================================================
// LOGICA 2: SE CI SONO PIÙ ARTICOLI -> LAYOUT CAROSELLO SPLIDE.JS
// ==============================================================================
else :
    $carouselId = 'carousel-evidenza-' . $module->id;
?>

    <?php if (empty($module->showtitle)) :
        $sectionHeading = !empty($module->title) ? $module->title : Text::_('TPL_ACCESSIBILE_FEATURED_ARTICLES');
    ?>
        <h2 class="visually-hidden"><?= htmlspecialchars($sectionHeading, ENT_QUOTES, 'UTF-8') ?></h2>
    <?php endif; ?>

    <div id="<?= $carouselId ?>" class="it-carousel-wrapper it-carousel-evidenza splide position-relative" data-bs-carousel-splide
         aria-label="<?php echo Text::_('TPL_ACCESSIBILE_FEATURED_ARTICLES'); ?>" aria-roledescription="carousel">

        <div class="splide__arrows">
            <button class="splide__arrow splide__arrow--prev btn btn-primary rounded-circle position-absolute top-50 translate-middle-y d-flex align-items-center justify-content-center"
                    type="button"
                    aria-label="<?php echo Text::_('TPL_ACCESSIBILE_PREV_SLIDE'); ?>" aria-controls="<?= $carouselId ?>-track">
                <svg class="icon icon-white" aria-hidden="true">
                    <use href="<?= $spriteUrl ?>#it-chevron-left"></use>
                </svg>
            </button>

            <button class="splide__arrow splide__arrow--next btn btn-primary rounded-circle position-absolute top-50 translate-middle-y d-flex align-items-center justify-content-center"
                    type="button"
                    aria-label="<?php echo Text::_('TPL_ACCESSIBILE_NEXT_SLIDE'); ?>" aria-controls="<?= $carouselId ?>-track">
                <svg class="icon icon-white" aria-hidden="true">
                    <use href="<?= $spriteUrl ?>#it-chevron-right"></use>
                </svg>
            </button>
        </div>

        <div class="splide__track" id="<?= $carouselId ?>-track" aria-live="polite">
            <ul class="splide__list">
                
                <?php foreach ($list as $index => $item) : 
                    
                    $publishedOn = $item->publish_up ?: $item->created;
                    $niceDate    = HTMLHelper::_('date', $publishedOn, Text::_('DATE_FORMAT_LC3')); 

                    $maxChars = 260;
                    $intro = trim(strip_tags($item->introtext ?? ''));
                    if (mb_strlen($intro) > $maxChars) {
                        $intro = rtrim(mb_substr($intro, 0, $maxChars)) . '…';
                    }

                    $images = json_decode($item->images ?? '{}');
                    $imgUrl   = $images->image_intro ?? '';
                    $imgAlt   = !empty($images->image_intro_alt) ? $images->image_intro_alt : Text::sprintf('TPL_ACCESSIBILE_COVER_IMAGE_ALT', $item->title);
                    $imgTitle = $images->image_intro_caption ?? $item->title;

                    $tags = [];
                    if (!empty($item->tags->itemTags)) {
                        $tags = $item->tags->itemTags;
                    } else {
                        try {
                            $tagsHelper = new \Joomla\CMS\Helper\TagsHelper;
                            $tags = $tagsHelper->getItemTags('com_content.article', (int) $item->id) ?? [];
                        } catch (\Throwable $e) {}
                    }

                    $leftColClass = $imgUrl ? 'col-lg-6 order-2 order-lg-1' : 'col-12';
                    $link = $item->link ?? Route::_(ContentRouteHelper::getArticleRoute($item->slug ?? $item->id, $item->catid, $item->language));
                    $deAttr = ModelloPAHelper::attributeFor((int) ($item->catid ?? 0));

                    $slideNum = $index + 1;
                ?>
                
                <li class="splide__slide" role="group" aria-roledescription="slide" aria-label="Slide <?= $slideNum ?> di <?= $totalArticles ?>">
                    <div class="it-single-slide-wrapper">
                        <div class="row align-items-center">
                            <div class="<?= $leftColClass ?>">
                                <div class="card mb-0 shadow-none bg-transparent">
                                    <div class="card-body pb-3 px-0">
                                        <div class="category-top">
                                            <svg class="icon icon-sm" aria-hidden="true">
                                                <use href="<?= $spriteUrl ?>#it-calendar"></use>
                                            </svg>
                                            <span class="visually-hidden"><?php echo Text::_('TPL_ACCESSIBILE_PUBLISH_DATE_AND_CATEGORY'); ?></span>
                                            
                                            <?php if (!empty($item->category_title)) : ?>
                                                <a class="text-decoration-none" href="<?= Route::_(ContentRouteHelper::getCategoryRoute($item->catid)); ?>">
                                                    <span class="title-xsmall-semi-bold fw-semibold">
                                                        <?= htmlspecialchars($item->category_title, ENT_QUOTES, 'UTF-8'); ?>
                                                    </span>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (!empty($niceDate)) : ?>
                                                <span class="data fw-normal"><?= htmlspecialchars($niceDate, ENT_QUOTES, 'UTF-8'); ?></span>
                                            <?php endif; ?>
                                        </div>

                                        <a href="<?= $link; ?>" class="text-decoration-none"<?= $deAttr; ?>>
                                            <<?= $itemHeading ?> class="card-title"><?= htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?></<?= $itemHeading ?>>
                                        </a>

                                        <?php if ($intro !== '') : ?>
                                            <p class="mb-3 pt-2 lora"><?= $intro; ?></p>
                                        <?php endif; ?>

                                        <?php if (!empty($tags)) : ?>
                                            <ul class="mod-pa-chips-list d-flex flex-wrap gap-1 list-unstyled mb-0" aria-label="<?php echo Text::_('TPL_ACCESSIBILE_RELATED_TOPICS'); ?>">
                                                <?php foreach ($tags as $tag) :
                                                    $tagTitle = $tag->title ?? '';
                                                    try {
                                                        $tagLink = Route::_(TagsRouteHelper::getTagRoute($tag->tag_id));
                                                    } catch (\Throwable $e) {
                                                        $tagLink = '';
                                                    }
                                                ?>
                                                    <li>
                                                        <a class="chip chip-simple" <?= $tagLink ? 'href="' . htmlspecialchars($tagLink, ENT_QUOTES, 'UTF-8') . '"' : '' ?>>
                                                            <span class="chip-label"><?= htmlspecialchars($tagTitle, ENT_QUOTES, 'UTF-8'); ?></span>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <?php if ($imgUrl) : ?>
                                <div class="col-lg-6 order-1 order-lg-2 px-0 px-lg-3">
                                    <img src="<?= htmlspecialchars($imgUrl, ENT_QUOTES, 'UTF-8'); ?>"
                                         title="<?= htmlspecialchars($imgTitle, ENT_QUOTES, 'UTF-8'); ?>"
                                         alt="<?= htmlspecialchars($imgAlt, ENT_QUOTES, 'UTF-8'); ?>"
                                         class="img-fluid mod-pa-featured-img rounded shadow-sm" />
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </li>
                
                <?php endforeach; ?>

            </ul>
        </div>
    </div>

<?php endif; ?>