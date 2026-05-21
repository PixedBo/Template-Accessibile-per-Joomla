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

$_parentTpl = \Joomla\CMS\Factory::getApplication()->getTemplate(true)->parent
    ?: \Joomla\CMS\Factory::getApplication()->getTemplate();
require_once JPATH_SITE . '/templates/' . $_parentTpl . '/helpers/TplAccessibileHelper.php';

if (empty($list)) {
    return;
}

$totalArticles = count($list);
$app           = Factory::getApplication();
$app->getLanguage()->load('tpl_templateaccessibileperjoomla', JPATH_SITE);
$template      = $app->getTemplate();

// item_heading: stringa h1–h6 o "div" (XML default: h4)
$itemHeading       = $params->get('item_heading', 'h4');
$introLimit        = (int)  $params->get('introtext_limit', 100);
$showAuthor        = (bool) $params->get('show_author', 0);
$showCategory      = (bool) $params->get('show_category', 0);
$showCategoryLink  = (bool) $params->get('show_category_link', 0);
$showDate          = (bool) $params->get('show_date', 0);
$showDateField     = $params->get('show_date_field', 'publish_up');
$showDateFormat    = $params->get('show_date_format', '');
$showReadmore      = (bool) $params->get('show_readmore', 0);
$showReadmoreTitle = (bool) $params->get('show_readmore_title', 1);
$readmoreLimit     = (int)  $params->get('readmore_limit', 15);
$_allowedTags      = ['h1','h2','h3','h4','h5','h6','p','div'];
$headerTag         = in_array($params->get('header_tag', 'h3'), $_allowedTags, true)
                         ? $params->get('header_tag', 'h3') : 'h3';

// ==============================================================================
// LOGICA 1: SE C'È UN SOLO ARTICOLO -> LAYOUT STATICO NATIVO (NO SLIDER)
// ==============================================================================
if ($totalArticles === 1) : 
    $item = $list[0];

    $_rawDate    = $item->{$showDateField} ?? null;
    $publishedOn = $_rawDate ?: ($item->publish_up ?: $item->created);
    $niceDate    = HTMLHelper::_('date', $publishedOn, $showDateFormat ?: Text::_('DATE_FORMAT_LC3'));

    $safeLimit = $introLimit;
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
    ?>
<div class="cmp-evidenza-title">

    <?php if (empty($module->showtitle)) :
        $sectionHeading = !empty($module->title) ? $module->title : Text::_('TPL_ACCESSIBILE_FEATURED_ARTICLES');
    ?>
        <<?= $headerTag ?> class="visually-hidden"><?= htmlspecialchars($sectionHeading, ENT_QUOTES, 'UTF-8') ?></<?= $headerTag ?>>
    <?php endif; ?>

    <div class="row align-items-stretch">
        <div class="<?= $leftColClass ?>">
            <div class="card mb-0 shadow-none bg-transparent">
                <div class="card-body pb-3 px-0">
                    <div class="category-top">
                        <svg class="icon icon-sm" aria-hidden="true">
                            <use href="<?= TplAccessibileHelper::spriteUrl('it-calendar') ?>"></use>
                        </svg>
                        <span class="visually-hidden"><?php echo Text::_('TPL_ACCESSIBILE_PUBLISH_DATE_AND_CATEGORY'); ?></span>
                        
                        <?php if ($showCategory && !empty($item->category_title)) : ?>
                            <?php if ($showCategoryLink) : ?>
                                <a class="text-decoration-none" href="<?= Route::_(ContentRouteHelper::getCategoryRoute($item->catid)); ?>">
                                    <span class="title-xsmall-semi-bold fw-semibold">
                                        <?= htmlspecialchars($item->category_title, ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </a>
                            <?php else : ?>
                                <span class="title-xsmall-semi-bold fw-semibold">
                                    <?= htmlspecialchars($item->category_title, ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($showDate && !empty($niceDate)) : ?>
                            <span class="data fw-normal"><?= htmlspecialchars($niceDate, ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php endif; ?>
                        <?php if ($showAuthor) :
                            $authorDisplay = ($item->created_by_alias ?? '') ?: ($item->author ?? '');
                            if ($authorDisplay !== '') : ?>
                                <span class="author">
                                    <span class="visually-hidden"><?= Text::_('JAUTHOR'); ?>: </span>
                                    <?= htmlspecialchars($authorDisplay, ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                        <?php   endif;
                        endif; ?>
                    </div>

                    <a href="<?= $link; ?>" class="text-decoration-none">
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
                    <?php if ($showReadmore) :
                        $srText = $showReadmoreTitle && !empty($item->title)
                            ? Text::sprintf('JGLOBAL_READ_MORE_TITLE', mb_strlen($item->title) > $readmoreLimit
                                ? rtrim(mb_substr($item->title, 0, $readmoreLimit)) . '…'
                                : $item->title)
                            : Text::_('JGLOBAL_READ_MORE');
                    ?>
                        <a href="<?= $link; ?>" class="read-more mt-3 d-inline-flex align-items-center"
                           aria-label="<?= htmlspecialchars($srText, ENT_QUOTES, 'UTF-8'); ?>">
                            <span aria-hidden="true"><?= Text::_('JGLOBAL_READ_MORE'); ?></span>
                            <svg class="icon icon-sm ms-1" aria-hidden="true">
                                <use href="<?= TplAccessibileHelper::spriteUrl('it-arrow-right') ?>"></use>
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if ($imgUrl) : ?>
            <div class="col-lg-6 order-1 order-lg-2 px-0 px-lg-3">
                <img src="<?= htmlspecialchars($imgUrl, ENT_QUOTES, 'UTF-8'); ?>"
                     title="<?= htmlspecialchars($imgTitle, ENT_QUOTES, 'UTF-8'); ?>"
                     alt="<?= htmlspecialchars($imgAlt, ENT_QUOTES, 'UTF-8'); ?>"
                     class="w-100 h-100 mod-pa-featured-img rounded shadow-sm"
                     style="object-fit: cover; min-height: 300px;" />
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
// ==============================================================================
// LOGICA 2: SE CI SONO PIÙ ARTICOLI -> LAYOUT CAROSELLO SPLIDE.JS
// ==============================================================================
else :
    $carouselId = 'carousel-evidenza-' . $module->id;
?>
<div class="cmp-evidenza-title">

    <?php if (empty($module->showtitle)) :
        $sectionHeading = !empty($module->title) ? $module->title : Text::_('TPL_ACCESSIBILE_FEATURED_ARTICLES');
    ?>
        <<?= $headerTag ?> class="visually-hidden"><?= htmlspecialchars($sectionHeading, ENT_QUOTES, 'UTF-8') ?></<?= $headerTag ?>>
    <?php endif; ?>

    <div id="<?= $carouselId ?>" class="it-carousel-wrapper it-carousel-evidenza splide position-relative" data-bs-carousel-splide
         aria-label="<?php echo Text::_('TPL_ACCESSIBILE_FEATURED_ARTICLES'); ?>" aria-roledescription="carousel">

        <div class="splide__arrows">
            <button class="splide__arrow splide__arrow--prev btn btn-primary rounded-circle position-absolute top-50 translate-middle-y d-flex align-items-center justify-content-center"
                    type="button"
                    aria-label="<?php echo Text::_('TPL_ACCESSIBILE_PREV_SLIDE'); ?>" aria-controls="<?= $carouselId ?>-track">
                <svg class="icon icon-white" aria-hidden="true">
                    <use href="<?= TplAccessibileHelper::spriteUrl('it-chevron-left') ?>"></use>
                </svg>
            </button>

            <button class="splide__arrow splide__arrow--next btn btn-primary rounded-circle position-absolute top-50 translate-middle-y d-flex align-items-center justify-content-center"
                    type="button"
                    aria-label="<?php echo Text::_('TPL_ACCESSIBILE_NEXT_SLIDE'); ?>" aria-controls="<?= $carouselId ?>-track">
                <svg class="icon icon-white" aria-hidden="true">
                    <use href="<?= TplAccessibileHelper::spriteUrl('it-chevron-right') ?>"></use>
                </svg>
            </button>
        </div>

        <div class="splide__track" id="<?= $carouselId ?>-track" aria-live="polite">
            <ul class="splide__list">
                
                <?php foreach ($list as $index => $item) : 
                    
                    $_rawDate    = $item->{$showDateField} ?? null;
                    $publishedOn = $_rawDate ?: ($item->publish_up ?: $item->created);
                    $niceDate    = HTMLHelper::_('date', $publishedOn, $showDateFormat ?: Text::_('DATE_FORMAT_LC3'));

                    $maxChars = $introLimit;
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

                    $slideNum = $index + 1;
                ?>
                
                <li class="splide__slide" role="group" aria-roledescription="slide" aria-label="Slide <?= $slideNum ?> di <?= $totalArticles ?>">
                    <div class="it-single-slide-wrapper">
                        <div class="row align-items-stretch">
                            <div class="<?= $leftColClass ?>">
                                <div class="card mb-0 shadow-none bg-transparent">
                                    <div class="card-body pb-3 px-0">
                                        <div class="category-top">
                                            <svg class="icon icon-sm" aria-hidden="true">
                                                <use href="<?= TplAccessibileHelper::spriteUrl('it-calendar') ?>"></use>
                                            </svg>
                                            <span class="visually-hidden"><?php echo Text::_('TPL_ACCESSIBILE_PUBLISH_DATE_AND_CATEGORY'); ?></span>
                                            
                                            <?php if ($showCategory && !empty($item->category_title)) : ?>
                                                <?php if ($showCategoryLink) : ?>
                                                    <a class="text-decoration-none" href="<?= Route::_(ContentRouteHelper::getCategoryRoute($item->catid)); ?>">
                                                        <span class="title-xsmall-semi-bold fw-semibold">
                                                            <?= htmlspecialchars($item->category_title, ENT_QUOTES, 'UTF-8'); ?>
                                                        </span>
                                                    </a>
                                                <?php else : ?>
                                                    <span class="title-xsmall-semi-bold fw-semibold">
                                                        <?= htmlspecialchars($item->category_title, ENT_QUOTES, 'UTF-8'); ?>
                                                    </span>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                            <?php if ($showDate && !empty($niceDate)) : ?>
                                                <span class="data fw-normal"><?= htmlspecialchars($niceDate, ENT_QUOTES, 'UTF-8'); ?></span>
                                            <?php endif; ?>
                                            <?php if ($showAuthor) :
                                                $authorDisplay = ($item->created_by_alias ?? '') ?: ($item->author ?? '');
                                                if ($authorDisplay !== '') : ?>
                                                    <span class="author">
                                                        <span class="visually-hidden"><?= Text::_('JAUTHOR'); ?>: </span>
                                                        <?= htmlspecialchars($authorDisplay, ENT_QUOTES, 'UTF-8'); ?>
                                                    </span>
                                            <?php   endif;
                                            endif; ?>
                                        </div>

                                        <a href="<?= $link; ?>" class="text-decoration-none">
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
                                        <?php if ($showReadmore) :
                                            $srText = $showReadmoreTitle && !empty($item->title)
                                                ? Text::sprintf('JGLOBAL_READ_MORE_TITLE', mb_strlen($item->title) > $readmoreLimit
                                                    ? rtrim(mb_substr($item->title, 0, $readmoreLimit)) . '…'
                                                    : $item->title)
                                                : Text::_('JGLOBAL_READ_MORE');
                                        ?>
                                            <a href="<?= $link; ?>" class="read-more mt-2 d-inline-flex align-items-center"
                                               aria-label="<?= htmlspecialchars($srText, ENT_QUOTES, 'UTF-8'); ?>">
                                                <span aria-hidden="true"><?= Text::_('JGLOBAL_READ_MORE'); ?></span>
                                                <svg class="icon icon-sm ms-1" aria-hidden="true">
                                                    <use href="<?= TplAccessibileHelper::spriteUrl('it-arrow-right') ?>"></use>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <?php if ($imgUrl) : ?>
                                <div class="col-lg-6 order-1 order-lg-2 px-0 px-lg-3">
                                    <img src="<?= htmlspecialchars($imgUrl, ENT_QUOTES, 'UTF-8'); ?>"
                                         title="<?= htmlspecialchars($imgTitle, ENT_QUOTES, 'UTF-8'); ?>"
                                         alt="<?= htmlspecialchars($imgAlt, ENT_QUOTES, 'UTF-8'); ?>"
                                         class="w-100 h-100 mod-pa-featured-img rounded shadow-sm"
                                         style="object-fit: cover; min-height: 300px;" />
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </li>
                
                <?php endforeach; ?>

            </ul>
        </div>
    </div>
</div>

<?php endif; ?>