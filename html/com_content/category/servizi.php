<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * Layout alternativo "Servizi" per categoria blog.
 * Pensato per la categoria "Servizi" del Comune secondo il modello PA (Designers Italia).
 * Emette gli attributi data-element="service-link" e data-element="service-category-link"
 * richiesti dall'App Valutazione Modelli.
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;

$app = Factory::getApplication();

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

if (!empty($this->items)) {
    $serviceItems = $this->items;
} else {
    $serviceItems = array_merge(
        !empty($this->lead_items) ? $this->lead_items : [],
        !empty($this->intro_items) ? $this->intro_items : [],
        !empty($this->link_items) ? $this->link_items : []
    );
}
?>

<div class="com-content-category-blog blog blog-servizi">

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

    <?php // Esplora tutti i servizi ?>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="title-xxlarge mb-4 mt-5 mb-lg-10">
                    <?php echo Text::_('TPL_ACCESSIBILE_SERVICES_EXPLORE_ALL'); ?>
                </h2>
            </div>

            <div class="col-12">
                <?php if (empty($serviceItems)) : ?>
                    <?php if ($this->params->get('show_no_articles', 1)) : ?>
                        <div class="alert alert-info">
                            <span class="icon-info-circle" aria-hidden="true"></span>
                            <span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
                            <?php echo Text::_('COM_CONTENT_NO_ARTICLES'); ?>
                        </div>
                    <?php endif; ?>
                <?php else : ?>
                    <div id="load-more">
                        <?php foreach ($serviceItems as &$item) : ?>
                            <?php
                            $this->item = &$item;
                            echo $this->loadTemplate('item');
                            ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php // Paginazione ?>
                <?php if (($this->params->def('show_pagination', 1) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
                    <div class="com-content-category-blog__navigation w-100 mt-5">
                        <?php if ($this->params->def('show_pagination_results', 1)) : ?>
                            <p class="com-content-category-blog__counter counter text-end pt-3 pe-2">
                                <?php echo $this->pagination->getPagesCounter(); ?>
                            </p>
                        <?php endif; ?>
                        <div class="com-content-category-blog__pagination">
                            <?php echo preg_replace('/<a\b/i', '<a data-element="pager-link"', $this->pagination->getPagesLinks()); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php // Esplora per categoria (sottocategorie) ?>
    <?php if ($this->maxLevel != 0 && !empty($this->children[$this->category->id])) : ?>
        <div class="container py-5 mt-5" id="argomento">
            <h2 class="title-xxlarge mb-4">
                <?php echo Text::_('TPL_ACCESSIBILE_EXPLORE_BY_CATEGORY'); ?>
            </h2>
            <?php echo $this->loadTemplate('children'); ?>
        </div>
    <?php endif; ?>

    <?php // Link crea articolo ?>
    <?php if ($this->category->getParams()->get('access-create')) : ?>
        <div class="container mt-4">
            <?php echo HTMLHelper::_('contenticon.create', $this->category, $this->category->params); ?>
        </div>
    <?php endif; ?>

</div>
