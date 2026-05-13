<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * Layout alternativo "Vivere il Comune" per categoria.
 * Modello Comuni (Designers Italia) — emette data-element="live-category-link",
 * "live-button-events" e "live-button-locations" richiesti dall'App Valutazione Modelli.
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper;
use Joomla\Registry\Registry;

$app = Factory::getApplication();
$app->getLanguage()->load('tpl_templateaccessibileperjoomla', JPATH_SITE);

/** @var \Joomla\Component\Content\Site\View\Category\HtmlView $this */
$this->category->text = $this->category->description;
$app->triggerEvent('onContentPrepare', [$this->category->extension . '.categories', &$this->category, &$this->params, 0]);
$this->category->description = $this->category->text;

$results           = $app->triggerEvent('onContentAfterTitle', [$this->category->extension . '.categories', &$this->category, &$this->params, 0]);
$afterDisplayTitle = trim(implode("\n", $results));

$results              = $app->triggerEvent('onContentBeforeDisplay', [$this->category->extension . '.categories', &$this->category, &$this->params, 0]);
$beforeDisplayContent = trim(implode("\n", $results));

$results             = $app->triggerEvent('onContentAfterDisplay', [$this->category->extension . '.categories', &$this->category, &$this->params, 0]);
$afterDisplayContent = trim(implode("\n", $results));

// Parametri configurabili dal menu item
$eventiCatId = (int) $this->params->get('vivere_cat_eventi', 0);
$luoghiCatId = (int) $this->params->get('vivere_cat_luoghi', 0);
$count       = max(1, (int) $this->params->get('vivere_count', 3));

// Chiavi mesi Joomla (localizzate)
$monthKeys = ['', 'JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE',
              'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'];

/**
 * Restituisce gli articoli in evidenza (featured=1) di una categoria.
 */
$queryCategory = static function (int $catId, int $limit): array {
    if ($catId <= 0) {
        return [];
    }

    try {
        $db       = Factory::getDbo();
        $now      = Factory::getDate()->toSql();
        $nullDate = $db->getNullDate();

        $query = $db->getQuery(true)
            ->select([
                $db->quoteName('a.id'),
                $db->quoteName('a.title'),
                $db->quoteName('a.alias'),
                $db->quoteName('a.introtext'),
                $db->quoteName('a.images'),
                $db->quoteName('a.catid'),
                $db->quoteName('a.language'),
                $db->quoteName('a.publish_up'),
                $db->quoteName('a.attribs'),
                $db->quoteName('c.title', 'category_title'),
            ])
            ->from($db->quoteName('#__content', 'a'))
            ->join('INNER', $db->quoteName('#__categories', 'c'), $db->quoteName('c.id') . ' = ' . $db->quoteName('a.catid'))
            ->where($db->quoteName('a.featured') . ' = 1')
            ->where($db->quoteName('a.state') . ' = 1')
            ->where($db->quoteName('a.catid') . ' = ' . $catId)
            ->where(
                '(' .
                $db->quoteName('a.publish_up') . ' IS NULL OR ' .
                $db->quoteName('a.publish_up') . ' <= ' . $db->quote($now) .
                ')'
            )
            ->where(
                '(' .
                $db->quoteName('a.publish_down') . ' IS NULL OR ' .
                $db->quoteName('a.publish_down') . ' = ' . $db->quote($nullDate) . ' OR ' .
                $db->quoteName('a.publish_down') . ' > ' . $db->quote($now) .
                ')'
            )
            ->order($db->quoteName('a.publish_up') . ' DESC');

        $db->setQuery($query, 0, $limit);
        $rows = $db->loadObjectList() ?: [];

        foreach ($rows as $row) {
            $row->slug   = $row->id . ':' . $row->alias;
            $row->params = new Registry($row->attribs ?? '');
        }

        return $rows;
    } catch (\Throwable $e) {
        return [];
    }
};

$eventiItems = $queryCategory($eventiCatId, $count);
$luoghiItems = $queryCategory($luoghiCatId, $count);

$eventiUrl = $eventiCatId > 0
    ? Route::_(RouteHelper::getCategoryRoute($eventiCatId, '*'))
    : '#';
$luoghiUrl = $luoghiCatId > 0
    ? Route::_(RouteHelper::getCategoryRoute($luoghiCatId, '*'))
    : '#';
?>

<div class="com-content-category-blog blog blog-vivere">

    <?php if ($this->params->get('show_page_heading')) : ?>
        <div class="page-header mb-4">
            <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
        </div>
    <?php endif; ?>

    <?php // Hero ?>
    <?php if ($this->params->get('show_category_title', 1) || ($this->params->get('show_description', 1) && $this->category->description)) : ?>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <div class="cmp-hero">
                        <section class="it-hero-wrapper bg-white align-items-start">
                            <div class="it-hero-text-wrapper pt-0 ps-0 pb-4 pb-lg-60">
                                <?php if ($this->params->get('show_category_title', 1)) : ?>
                                    <h1 class="text-black hero-title">
                                        <?php echo $this->category->title; ?>
                                    </h1>
                                <?php endif; ?>
                                <?php echo $afterDisplayTitle; ?>

                                <?php if ($this->params->get('show_description', 1) && $this->category->description) : ?>
                                    <div class="hero-text">
                                        <?php echo $beforeDisplayContent; ?>
                                        <?php echo HTMLHelper::_('content.prepare', $this->category->description, '', 'com_content.category'); ?>
                                        <?php echo $afterDisplayContent; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($this->params->get('show_cat_tags', 1) && !empty($this->category->tags->itemTags)) : ?>
                                    <div class="mt-3">
                                        <?php $this->category->tagLayout = new FileLayout('joomla.content.tags'); ?>
                                        <?php echo $this->category->tagLayout->render($this->category->tags->itemTags); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php // Sezione "Eventi in evidenza" ?>
    <?php if (!empty($eventiItems)) : ?>
        <div class="vivere-eventi py-5">
            <div class="container">
                <h2 class="title-xxlarge mb-4">
                    <?php echo Text::_('TPL_ACCESSIBILE_VIVERE_EVENTI_IN_EVIDENZA'); ?>
                </h2>
                <div class="row g-4">
                    <?php foreach ($eventiItems as $item) :
                        $images     = json_decode($item->images ?? '');
                        $hasImage   = !empty($images->image_intro);
                        $imgSrc     = $hasImage ? htmlspecialchars($images->image_intro, ENT_QUOTES, 'UTF-8') : '';
                        $imgAlt     = $hasImage ? htmlspecialchars($images->image_intro_alt ?: $item->title, ENT_QUOTES, 'UTF-8') : '';
                        $articleUrl = Route::_(RouteHelper::getArticleRoute($item->slug, $item->catid, $item->language));
                        $catUrl     = Route::_(RouteHelper::getCategoryRoute($item->catid, $item->language ?? '*'));
                        $date       = !empty($item->publish_up) ? Factory::getDate($item->publish_up) : null;
                        $day        = $date ? $date->format('j') : '';
                        $monthName  = $date ? Text::_($monthKeys[(int) $date->format('n')]) : '';
                        $introText  = HTMLHelper::_('string.truncate', strip_tags($item->introtext ?? ''), 150);
                    ?>
                        <div class="col-lg-6 col-xl-4">
                            <div class="card-wrapper shadow-sm rounded border border-light">
                                <div class="card no-after rounded">
                                    <?php if ($hasImage) : ?>
                                        <div class="img-responsive-wrapper">
                                            <div class="img-responsive img-responsive-panoramic">
                                                <figure class="img-wrapper">
                                                    <img src="<?php echo $imgSrc; ?>"
                                                         class="rounded-top img-fluid"
                                                         alt="<?php echo $imgAlt; ?>"
                                                         title="<?php echo $this->escape($item->title); ?>">
                                                </figure>
                                                <?php if ($day && $monthName) : ?>
                                                    <div class="card-calendar d-flex flex-column justify-content-center">
                                                        <span class="card-date"><?php echo $day; ?></span>
                                                        <span class="card-day"><?php echo $monthName; ?></span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <div class="category-top">
                                            <a class="category text-decoration-none"
                                               href="<?php echo $catUrl; ?>">
                                                <?php echo $this->escape($item->category_title); ?>
                                            </a>
                                        </div>
                                        <h3 class="card-title">
                                            <a class="text-decoration-none"
                                               href="<?php echo $articleUrl; ?>"
                                               data-element="live-category-link">
                                                <?php echo $this->escape($item->title); ?>
                                            </a>
                                        </h3>
                                        <?php if ($introText) : ?>
                                            <p class="card-text text-secondary pb-3">
                                                <?php echo $introText; ?>
                                            </p>
                                        <?php endif; ?>
                                        <a class="read-more t-primary text-uppercase"
                                           href="<?php echo $articleUrl; ?>"
                                           aria-label="<?php echo Text::_('TPL_ACCESSIBILE_VIVERE_LEGGI_ARIA') . ' ' . $this->escape($item->title); ?>">
                                            <span class="text"><?php echo Text::_('TPL_ACCESSIBILE_VIVERE_LEGGI_DI_PIU'); ?></span>
                                            <svg class="icon icon-primary icon-xs ms-2" aria-hidden="true">
                                                <use href="#it-arrow-right"></use>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="d-flex justify-content-end mt-3 w-100">
                        <button class="btn btn-outline-primary"
                                data-element="live-button-events"
                                data-focus-mouse="false"
                                onclick="location.href='<?php echo $eventiUrl; ?>'">
                            <?php echo Text::_('TPL_ACCESSIBILE_VIVERE_TUTTI_EVENTI'); ?>
                            <svg class="icon icon-primary icon-xs ms-2" aria-hidden="true">
                                <use href="#it-arrow-right"></use>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php // Sezione "Luoghi in evidenza" ?>
    <?php if (!empty($luoghiItems)) : ?>
        <div class="vivere-luoghi py-5">
            <div class="container">
                <h2 class="title-xxlarge mb-4">
                    <?php echo Text::_('TPL_ACCESSIBILE_VIVERE_LUOGHI_IN_EVIDENZA'); ?>
                </h2>
                <div class="row g-4">
                    <?php foreach ($luoghiItems as $item) :
                        $images     = json_decode($item->images ?? '');
                        $hasImage   = !empty($images->image_intro);
                        $imgSrc     = $hasImage ? htmlspecialchars($images->image_intro, ENT_QUOTES, 'UTF-8') : '';
                        $imgAlt     = $hasImage ? htmlspecialchars($images->image_intro_alt ?: $item->title, ENT_QUOTES, 'UTF-8') : '';
                        $articleUrl = Route::_(RouteHelper::getArticleRoute($item->slug, $item->catid, $item->language));
                        $catUrl     = Route::_(RouteHelper::getCategoryRoute($item->catid, $item->language ?? '*'));
                        $introText  = HTMLHelper::_('string.truncate', strip_tags($item->introtext ?? ''), 150);
                    ?>
                        <div class="col-lg-6 col-xl-4">
                            <div class="card-wrapper shadow-sm rounded border border-light">
                                <div class="card no-after rounded">
                                    <?php if ($hasImage) : ?>
                                        <div class="img-responsive-wrapper">
                                            <div class="img-responsive img-responsive-panoramic">
                                                <figure class="img-wrapper">
                                                    <img src="<?php echo $imgSrc; ?>"
                                                         class="rounded-top img-fluid"
                                                         alt="<?php echo $imgAlt; ?>"
                                                         title="<?php echo $this->escape($item->title); ?>">
                                                </figure>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <div class="category-top">
                                            <a class="category text-decoration-none"
                                               href="<?php echo $catUrl; ?>">
                                                <?php echo $this->escape($item->category_title); ?>
                                            </a>
                                        </div>
                                        <h3 class="card-title">
                                            <a class="text-decoration-none"
                                               href="<?php echo $articleUrl; ?>"
                                               data-element="live-category-link">
                                                <?php echo $this->escape($item->title); ?>
                                            </a>
                                        </h3>
                                        <?php if ($introText) : ?>
                                            <p class="card-text text-secondary pb-3">
                                                <?php echo $introText; ?>
                                            </p>
                                        <?php endif; ?>
                                        <a class="read-more t-primary text-uppercase"
                                           href="<?php echo $articleUrl; ?>"
                                           aria-label="<?php echo Text::_('TPL_ACCESSIBILE_VIVERE_LEGGI_ARIA') . ' ' . $this->escape($item->title); ?>">
                                            <span class="text"><?php echo Text::_('TPL_ACCESSIBILE_VIVERE_LEGGI_DI_PIU'); ?></span>
                                            <svg class="icon icon-primary icon-xs ms-2" aria-hidden="true">
                                                <use href="#it-arrow-right"></use>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="d-flex justify-content-end mt-3 w-100">
                        <button class="btn btn-outline-primary"
                                data-element="live-button-locations"
                                data-focus-mouse="false"
                                onclick="location.href='<?php echo $luoghiUrl; ?>'">
                            <?php echo Text::_('TPL_ACCESSIBILE_VIVERE_TUTTI_LUOGHI'); ?>
                            <svg class="icon icon-primary icon-xs ms-2" aria-hidden="true">
                                <use href="#it-arrow-right"></use>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>
