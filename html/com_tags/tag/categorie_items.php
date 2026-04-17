<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_tags
 *
 * Layout alternativo: Articoli raggruppati per categoria
 * Sub-template per la griglia degli item suddivisi per categoria
 *
 * @copyright   (C) 2013 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Site\Helper\RouteHelper as ContentRouteHelper;

/** @var \Joomla\Component\Tags\Site\View\Tag\HtmlView $this */
$user = $this->getCurrentUser();

// Raggruppa gli item per categoria
$groupedItems = [];
$categoryIds  = [];

if (!empty($this->items)) {
    foreach ($this->items as $item) {
        if ($item->core_state == 0) {
            continue;
        }
        $catId = (int) $item->core_catid;
        if (!isset($groupedItems[$catId])) {
            $groupedItems[$catId] = [];
            $categoryIds[] = $catId;
        }
        $groupedItems[$catId][] = $item;
    }
}

// Recupera i titoli delle categorie dal database
$categoryTitles = [];
if (!empty($categoryIds)) {
    $db = Factory::getDbo();
    $query = $db->getQuery(true)
        ->select($db->quoteName(['id', 'title', 'alias', 'language']))
        ->from($db->quoteName('#__categories'))
        ->whereIn($db->quoteName('id'), $categoryIds);
    $db->setQuery($query);
    $categories = $db->loadObjectList('id');

    foreach ($categories as $id => $cat) {
        $categoryTitles[$id] = $cat;
    }
}
?>

<div class="com-tags-tag__items">
    <?php if ($this->params->get('filter_field') || $this->params->get('show_pagination_limit')) : ?>
        <form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm">
            <?php if ($this->params->get('filter_field')) : ?>
                <div class="com-tags-tags__filter btn-group">
                    <label class="filter-search-lbl visually-hidden" for="filter-search">
                        <?php echo Text::_('COM_TAGS_TITLE_FILTER_LABEL'); ?>
                    </label>
                    <input
                        type="text"
                        name="filter-search"
                        id="filter-search"
                        value="<?php echo $this->escape($this->state->get('list.filter')); ?>"
                        class="inputbox" onchange="document.adminForm.submit();"
                        placeholder="<?php echo Text::_('COM_TAGS_TITLE_FILTER_LABEL'); ?>"
                    >
                    <button type="submit" name="filter_submit" class="btn btn-primary"><?php echo Text::_('JGLOBAL_FILTER_BUTTON'); ?></button>
                    <button type="reset" name="filter-clear-button" class="btn btn-secondary"><?php echo Text::_('JSEARCH_FILTER_CLEAR'); ?></button>
                </div>
            <?php endif; ?>
            <?php if ($this->params->get('show_pagination_limit')) : ?>
                <div class="btn-group float-end">
                    <label for="limit" class="visually-hidden">
                        <?php echo Text::_('JGLOBAL_DISPLAY_NUM'); ?>
                    </label>
                    <?php echo $this->pagination->getLimitBox(); ?>
                </div>
            <?php endif; ?>
            <input type="hidden" name="limitstart" value="">
            <input type="hidden" name="task" value="">
        </form>
    <?php endif; ?>

    <?php if (empty($this->items)) : ?>
        <div class="alert alert-info">
            <span class="icon-info-circle" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
            <?php echo Text::_('COM_TAGS_NO_ITEMS'); ?>
        </div>
    <?php else : ?>
        <?php foreach ($groupedItems as $catId => $items) : ?>
            <?php
            $catData  = $categoryTitles[$catId] ?? null;
            $catTitle = $catData ? $catData->title : Text::_('JNONE');
            $catAlias = $catData ? $catData->alias : '';
            $catLang  = $catData ? $catData->language : '*';
            $catLink  = $catId ? Route::_(ContentRouteHelper::getCategoryRoute($catId, $catLang)) : '';
            $sectionId = $catAlias ?: 'cat-' . $catId;
            ?>
            <section id="<?php echo htmlspecialchars($sectionId); ?>">
                <div class="pb-40 pt-40 pt-lg-80">
                    <div class="container">
                        <div class="row row-title">
                            <div class="col-12">
                                <h3 class="u-grey-light border-bottom border-semi-dark pb-2 pb-lg-3 title-large-semi-bold">
                                    <?php echo htmlspecialchars($catTitle); ?>
                                </h3>
                            </div>
                        </div>
                        <div class="row g-4">
                                <?php foreach ($items as $item) : ?>
                                    <?php
                                    $images = json_decode($item->core_images);
                                    $hasImage = !empty($images->image_intro);
                                    $displayDate = '';
                                    if (!empty($item->core_publish_up)) {
                                        $date = Factory::getDate($item->core_publish_up);
                                        $displayDate = strtoupper($date->format('d M y'));
                                    }
                                    ?>
                                    <div class="col-md-6 col-xl-4">
                                        <div class="card-wrapper border border-light rounded shadow-sm <?php echo !$hasImage ? 'cmp-list-card-img cmp-list-card-img-hr' : ''; ?>">
                                            <div class="card no-after rounded">
                                                <?php if ($hasImage) : ?>
                                                    <div class="img-responsive-wrapper">
                                                        <div class="img-responsive img-responsive-panoramic">
                                                            <figure class="img-wrapper">
                                                                <a href="<?php echo Route::_($item->link); ?>">
                                                                    <img src="<?php echo htmlspecialchars($images->image_intro); ?>"
                                                                         alt="<?php echo htmlspecialchars($images->image_intro_alt ?: $item->core_title); ?>"
                                                                         title="<?php echo htmlspecialchars($item->core_title); ?>">
                                                                </a>
                                                            </figure>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                <?php else : ?>
                                                    <div class="row g-2 g-md-0 flex-md-column">
                                                        <div class="col-12 order-1 order-md-2">
                                                            <div class="card-body card-img-none rounded-top">
                                                <?php endif; ?>

                                                    <?php if ($displayDate) : ?>
                                                        <div class="category-top">
                                                            <span class="data"><?php echo $displayDate; ?></span>
                                                        </div>
                                                    <?php endif; ?>

                                                    <a class="text-decoration-none" href="<?php echo Route::_($item->link); ?>">
                                                        <h4 class="card-title"><?php echo $this->escape($item->core_title); ?></h4>
                                                    </a>

                                                    <?php if ($this->params->get('tag_list_show_item_description', 1) && !empty($item->core_body)) : ?>
                                                        <p class="card-text text-secondary">
                                                            <?php echo HTMLHelper::_('string.truncate', strip_tags($item->core_body), $this->params->get('tag_list_item_maximum_characters', 150)); ?>
                                                        </p>
                                                    <?php endif; ?>

                                                <?php if ($hasImage) : ?>
                                                    </div>
                                                <?php else : ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                        </div>
                        <?php if ($catLink) : ?>
                            <div class="row mt-4">
                                <div class="col-12 col-lg-3 offset-lg-9">
                                    <a href="<?php echo $catLink; ?>" class="btn btn-primary text-button w-100">
                                        <?php echo Text::sprintf('TPL_ACCESSIBILE_TAG_ALL_CATEGORY', htmlspecialchars($catTitle)); ?>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
