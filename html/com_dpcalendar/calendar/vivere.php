<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_dpcalendar
 *
 * Layout alternativo "Vivere il Comune" per DPCalendar.
 * Replica il comportamento di com_content/category/vivere.php
 * Supporta il caricamento di luoghi da com_content se la voce di menu punta ad una categoria.
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Menu\Menu;
use Joomla\Component\Content\Site\Helper\RouteHelper;

$_parentTpl = \Joomla\CMS\Factory::getApplication()->getTemplate(true)->parent
    ?: \Joomla\CMS\Factory::getApplication()->getTemplate();
require_once JPATH_SITE . '/templates/' . $_parentTpl . '/helpers/TplAccessibileHelper.php';

$app = Factory::getApplication();
$app->getLanguage()->load('tpl_templateaccessibileperjoomla', JPATH_SITE);

// Parametri configurabili dal menu item
$count                = max(1, (int) $this->params->get('vivere_count', 3));
$includeSubcategorie  = (bool) $this->params->get('vivere_luoghi_sottocategorie', 0);
$mostraImmagine       = (bool) $this->params->get('vivere_mostra_immagine', 1);

$menuTuttiEventiId = (int) $this->params->get('vivere_menu_tutti_eventi', 0);
$menuTuttiLuoghiId = (int) $this->params->get('vivere_menu_tutti_luoghi', 0);

$eventiUrl = $menuTuttiEventiId > 0 ? Route::_('index.php?Itemid=' . $menuTuttiEventiId) : '#';
$luoghiUrl = $menuTuttiLuoghiId > 0 ? Route::_('index.php?Itemid=' . $menuTuttiLuoghiId) : '#';

// Carichiamo l'helper di DPCalendar per le rotte
if (!class_exists('DPCalendarHelperRoute')) {
    require_once JPATH_SITE . '/components/com_dpcalendar/helpers/route.php';
}

/**
 * Funzione per recuperare eventi in evidenza (DPCalendar)
 */
$queryFeaturedEvents = function (int $limit) {
    $db = Factory::getDbo();
    $now = Factory::getDate()->toSql();
    
    $query = $db->getQuery(true)
        ->select([
            'e.id', 'e.title', 'e.alias', 'e.catid', 'e.start_date', 'e.all_day',
            'e.images', 'e.description', 'c.title as category_title'
        ])
        ->from($db->quoteName('#__dpcalendar_events', 'e'))
        ->join('INNER', $db->quoteName('#__categories', 'c'), 'c.id = e.catid')
        ->where('e.featured = 1')
        ->where('e.state = 1')
        ->where('(e.publish_up IS NULL OR e.publish_up <= ' . $db->quote($now) . ')')
        ->where('(e.publish_down IS NULL OR e.publish_down >= ' . $db->quote($now) . ')')
        ->order('e.start_date ASC');
        
    $db->setQuery($query, 0, $limit);
    return $db->loadObjectList() ?: [];
};

/**
 * Funzione per recuperare luoghi in evidenza (DPCalendar)
 */
$queryFeaturedLocations = function (int $limit) {
    $db = Factory::getDbo();
    
    $query = $db->getQuery(true)
        ->select(['l.id', 'l.title', 'l.alias', 'l.description', 'l.params'])
        ->from($db->quoteName('#__dpcalendar_locations', 'l'))
        ->where('l.state = 1')
        ->order('l.title ASC');
        
    $db->setQuery($query, 0, $limit);
    return $db->loadObjectList() ?: [];
};

/**
 * Funzione per recuperare articoli in evidenza (com_content).
 *
 * @param int  $catId               ID della categoria radice da cui caricare gli articoli.
 * @param int  $limit               Numero massimo di articoli da restituire.
 * @param bool $includeSubcategorie Se true, include anche gli articoli delle sottocategorie
 *                                  discendenti tramite il modello Nested Set di Joomla
 *                                  (#__categories.lft / rgt).
 */
$queryFeaturedArticles = function (int $catId, int $limit, bool $includeSubcategorie = false) {
    if ($catId <= 0) return [];

    $db  = Factory::getDbo();
    $now = Factory::getDate()->toSql();

    $query = $db->getQuery(true)
        ->select([
            'a.id', 'a.title', 'a.alias', 'a.catid', 'a.introtext', 'a.images', 'a.language',
            'c.title as category_title'
        ])
        ->from($db->quoteName('#__content', 'a'))
        ->join('INNER', $db->quoteName('#__categories', 'c'), 'c.id = a.catid')
        ->where('a.featured = 1')
        ->where('a.state = 1')
        // Parentesi obbligatorie: senza di esse l'OR rompe la precedenza AND e azzera
        // tutti i filtri (catid, featured, state) per le righe che hanno publish_down != NULL.
        ->where('(a.publish_up IS NULL OR a.publish_up <= ' . $db->quote($now) . ')')
        ->where('(a.publish_down IS NULL OR a.publish_down >= ' . $db->quote($now) . ')')
        ->order('a.publish_up DESC');

    if ($includeSubcategorie) {
        // Nested Set: self-join su #__categories per includere la radice e tutti i discendenti.
        // Il join su 'c' (già presente) è il nodo foglia; 'parent' è la radice selezionata.
        $query->join(
                'INNER',
                $db->quoteName('#__categories', 'parent'),
                'parent.id = ' . (int) $catId
              )
              ->where('c.lft >= parent.lft')
              ->where('c.rgt <= parent.rgt')
              ->where('c.extension = ' . $db->quote('com_content'));
    } else {
        $query->where('a.catid = ' . (int) $catId);
    }

    $db->setQuery($query, 0, $limit);
    $items = $db->loadObjectList() ?: [];

    // Preparazione slug per rotte Joomla
    foreach ($items as $item) {
        $item->slug = $item->id . ':' . $item->alias;
    }

    return $items;
};

// Logica per determinare da dove caricare i luoghi
$luoghiItems = [];
$isContentLuoghi = false;

if ($menuTuttiLuoghiId > 0) {
    $menu = $app->getMenu();
    $item = $menu->getItem($menuTuttiLuoghiId);
    
    // Verifichiamo se è una voce di com_content (category blog o list)
    if ($item && $item->component === 'com_content' && (isset($item->query['view']) && ($item->query['view'] === 'category'))) {
        $catId = (int) ($item->query['id'] ?? 0);
        if ($catId > 0) {
            $luoghiItems = $queryFeaturedArticles($catId, $count, $includeSubcategorie);
            $isContentLuoghi = true;
        }
    }
}

// Se non abbiamo caricato nulla da com_content, carichiamo da DPCalendar
if (empty($luoghiItems)) {
    $luoghiItems = $queryFeaturedLocations($count);
    $isContentLuoghi = false;
}

$eventiItems = $queryFeaturedEvents($count);

// Mesi localizzati
$monthNames = [];
for ($m = 1; $m <= 12; $m++) {
    $monthNames[$m] = Text::_('TPL_ACCESSIBILE_MONTH_' . $m);
}

// Gestione titolo e descrizione Hero
$pageTitle = $this->params->get('page_title', '');
if (empty($pageTitle) && isset($this->category->title)) {
    $pageTitle = $this->category->title;
}

$pageDesc = $this->params->get('vivere_description', '');
if (empty($pageDesc) && isset($this->category->description)) {
    $pageDesc = $this->category->description;
}
?>

<div class="com-dpcalendar-vivere blog blog-vivere">

    <?php if ($this->params->get('show_page_heading')) : ?>
        <div class="page-header mb-4">
            <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
        </div>
    <?php endif; ?>

    <?php // Hero Section ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="cmp-hero">
                    <section class="it-hero-wrapper bg-white align-items-start">
                        <div class="it-hero-text-wrapper pt-0 ps-0 pb-4 pb-lg-60">
                            <?php if ($this->params->get('show_category_title', 1) && !empty($pageTitle)) : ?>
                                <h1 class="text-black hero-title">
                                    <?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?>
                                </h1>
                            <?php endif; ?>

                            <?php if ($this->params->get('show_description', 1) && !empty($pageDesc)) : ?>
                                <div class="hero-text">
                                    <?php echo HTMLHelper::_('content.prepare', $pageDesc); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <?php // Eventi in evidenza ?>
    <?php if (!empty($eventiItems)) : ?>
        <div class="vivere-eventi py-5">
            <div class="container">
                <h2 class="title-xxlarge mb-4">
                    <?php echo Text::_('TPL_ACCESSIBILE_VIVERE_EVENTI_IN_EVIDENZA'); ?>
                </h2>
                <div class="row g-4">
                    <?php foreach ($eventiItems as $item) :
                        $images    = json_decode($item->images ?? '');
                        $imgSrc    = $images->image_intro ?? '';
                        $imgAlt    = $images->image_intro_alt ?? $item->title;
                        $eventUrl  = Route::_('index.php?option=com_dpcalendar&view=event&id=' . $item->id . '&calid=' . $item->catid);
                        $catUrl    = Route::_('index.php?option=com_dpcalendar&view=calendar&id=' . $item->catid);
                        
                        if ((bool)$item->all_day) {
                            $startDate = Factory::getDate($item->start_date);
                        } else {
                            $tz        = new \DateTimeZone(Factory::getApplication()->get('offset', 'UTC'));
                            $startDate = Factory::getDate($item->start_date);
                            $startDate->setTimezone($tz);
                        }
                        $day       = $startDate->format('j', true);
                        $monthNum  = (int)$startDate->format('n', true);
                        $monthLabel = $monthNames[$monthNum] ?? '';
                        
                        $desc = strip_tags($item->description ?? '');
                        $introText = HTMLHelper::_('string.truncate', $desc, 150);
                    ?>
                        <div class="col-lg-6 col-xl-4">
                            <div class="card-wrapper shadow-sm rounded border border-light">
                                <div class="card no-after rounded">

                                    <?php if ($mostraImmagine && $imgSrc) : ?>
                                        <!-- Caso A: immagine reale -->
                                        <div class="img-responsive-wrapper">
                                            <div class="img-responsive img-responsive-panoramic">
                                                <figure class="img-wrapper">
                                                    <img src="<?php echo htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8'); ?>"
                                                         class="rounded-top img-fluid"
                                                         alt="<?php echo htmlspecialchars($imgAlt, ENT_QUOTES, 'UTF-8'); ?>">
                                                </figure>
                                                <div class="card-calendar d-flex flex-column justify-content-center">
                                                    <span class="card-date"><?php echo $day; ?></span>
                                                    <span class="card-day"><?php echo htmlspecialchars($monthLabel, ENT_QUOTES, 'UTF-8'); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php elseif ($mostraImmagine) : ?>
                                        <!-- Caso B: immagine abilitata ma assente → placeholder bg-evidenza + data top-right -->
                                        <div class="tpl-event-placeholder bg-evidenza rounded-top" aria-hidden="true"></div>
                                        <time class="card-calendar d-flex flex-column justify-content-center"
                                              datetime="<?php echo $startDate->format('Y-m-d', true); ?>">
                                            <span class="card-date"><?php echo $day; ?></span>
                                            <span class="card-day"><?php echo htmlspecialchars($monthLabel, ENT_QUOTES, 'UTF-8'); ?></span>
                                        </time>
                                    <?php endif; ?>
                                    <!-- Caso C: $mostraImmagine=false → nessun blocco immagine; data pill nel card-body -->

                                    <div class="card-body">
                                        <?php if (!$mostraImmagine) : ?>
                                            <!-- Caso C: data pill -->
                                            <time class="tpl-event-date-pill" datetime="<?php echo $startDate->format('Y-m-d', true); ?>">
                                                <svg class="icon icon-sm icon-primary" aria-hidden="true">
                                                    <use href="<?php echo TplAccessibileHelper::spriteUrl('it-calendar'); ?>"></use>
                                                </svg>
                                                <span><?php echo $day . ' ' . htmlspecialchars($monthLabel, ENT_QUOTES, 'UTF-8'); ?></span>
                                            </time>
                                        <?php endif; ?>
                                        <div class="category-top">
                                            <a class="category text-decoration-none" href="<?php echo $catUrl; ?>">
                                                <?php echo htmlspecialchars($item->category_title, ENT_QUOTES, 'UTF-8'); ?>
                                            </a>
                                        </div>
                                        <h3 class="card-title">
                                            <a class="text-decoration-none" href="<?php echo $eventUrl; ?>" data-element="live-category-link">
                                                <?php echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?>
                                            </a>
                                        </h3>
                                        <?php if ($introText) : ?>
                                            <p class="card-text text-secondary pb-3"><?php echo $introText; ?></p>
                                        <?php endif; ?>
                                        <a class="read-more t-primary text-uppercase" href="<?php echo $eventUrl; ?>">
                                            <span class="text"><?php echo Text::_('TPL_ACCESSIBILE_VIVERE_LEGGI_DI_PIU'); ?></span>
                                            <svg class="icon icon-primary icon-xs ms-2" aria-hidden="true">
                                                <use href="<?= TplAccessibileHelper::spriteUrl('it-arrow-right') ?>"></use>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="d-flex justify-content-end mt-3 w-100">
                        <button class="btn btn-outline-primary" data-element="live-button-events" onclick="location.href='<?php echo $eventiUrl; ?>'">
                            <?php echo Text::_('TPL_ACCESSIBILE_VIVERE_TUTTI_EVENTI'); ?>
                            <svg class="icon icon-primary icon-xs ms-2" aria-hidden="true">
                                <use href="<?= TplAccessibileHelper::spriteUrl('it-arrow-right') ?>"></use>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php // Luoghi in evidenza ?>
    <?php if (!empty($luoghiItems)) : ?>
        <div class="vivere-luoghi py-5">
            <div class="container">
                <h2 class="title-xxlarge mb-4">
                    <?php echo Text::_('TPL_ACCESSIBILE_VIVERE_LUOGHI_IN_EVIDENZA'); ?>
                </h2>
                <div class="row g-4">
                    <?php foreach ($luoghiItems as $item) : ?>
                        <?php
                        if ($isContentLuoghi) :
                            // Rendering per articoli com_content
                            $images = json_decode($item->images ?? '');
                            $imgSrc = $images->image_intro ?? '';
                            $imgAlt = $images->image_intro_alt ?? $item->title;
                            $url    = Route::_(RouteHelper::getArticleRoute($item->slug, $item->catid, $item->language));
                            $catUrl = Route::_(RouteHelper::getCategoryRoute($item->catid));
                            $title  = $item->title;
                            $catTitle = $item->category_title;
                            $introText = HTMLHelper::_('string.truncate', strip_tags($item->introtext ?? ''), 150);
                        else :
                            // Rendering per luoghi DPCalendar
                            $url    = Route::_('index.php?option=com_dpcalendar&view=location&id=' . $item->id);
                            $catUrl = '';
                            $title  = $item->title;
                            $catTitle = '';
                            $introText = HTMLHelper::_('string.truncate', strip_tags($item->description ?? ''), 150);
                            $locParams = new \Joomla\Registry\Registry($item->params);
                            $imgSrc = $locParams->get('image', '');
                            $imgAlt = $title;
                        endif;
                        ?>
                        <div class="col-lg-6 col-xl-4">
                            <div class="card-wrapper shadow-sm rounded border border-light">
                                <div class="card no-after rounded">

                                    <?php if ($mostraImmagine && $imgSrc) : ?>
                                        <!-- Caso A: immagine reale -->
                                        <div class="img-responsive-wrapper">
                                            <div class="img-responsive img-responsive-panoramic">
                                                <figure class="img-wrapper">
                                                    <img src="<?php echo htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8'); ?>"
                                                         class="rounded-top img-fluid"
                                                         alt="<?php echo htmlspecialchars($imgAlt, ENT_QUOTES, 'UTF-8'); ?>">
                                                </figure>
                                            </div>
                                        </div>
                                    <?php elseif ($mostraImmagine) : ?>
                                        <!-- Caso B: immagine abilitata ma assente → placeholder bg-evidenza (no data per i luoghi) -->
                                        <div class="tpl-event-placeholder bg-evidenza rounded-top" aria-hidden="true"
                                             style="min-height:120px;"></div>
                                    <?php endif; ?>
                                    <!-- Caso C: $mostraImmagine=false → nessun blocco immagine -->

                                    <div class="card-body">
                                        <?php if ($catTitle) : ?>
                                            <div class="category-top">
                                                <a class="category text-decoration-none" href="<?php echo $catUrl; ?>">
                                                    <?php echo htmlspecialchars($catTitle, ENT_QUOTES, 'UTF-8'); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        <h3 class="card-title">
                                            <a class="text-decoration-none" href="<?php echo $url; ?>" data-element="live-category-link">
                                                <?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>
                                            </a>
                                        </h3>
                                        <?php if ($introText) : ?>
                                            <p class="card-text text-secondary pb-3"><?php echo $introText; ?></p>
                                        <?php endif; ?>
                                        <a class="read-more t-primary text-uppercase" href="<?php echo $url; ?>">
                                            <span class="text"><?php echo Text::_('TPL_ACCESSIBILE_VIVERE_LEGGI_DI_PIU'); ?></span>
                                            <svg class="icon icon-primary icon-xs ms-2" aria-hidden="true">
                                                <use href="<?= TplAccessibileHelper::spriteUrl('it-arrow-right') ?>"></use>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="d-flex justify-content-end mt-3 w-100">
                        <button class="btn btn-outline-primary" data-element="live-button-locations" onclick="location.href='<?php echo $luoghiUrl; ?>'">
                            <?php echo Text::_('TPL_ACCESSIBILE_VIVERE_TUTTI_LUOGHI'); ?>
                            <svg class="icon icon-primary icon-xs ms-2" aria-hidden="true">
                                <use href="<?= TplAccessibileHelper::spriteUrl('it-arrow-right') ?>"></use>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>
