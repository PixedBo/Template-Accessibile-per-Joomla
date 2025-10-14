<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
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

$results = $app->triggerEvent('onContentAfterTitle', [$this->category->extension . '.categories', &$this->category, &$this->params, 0]);
$afterDisplayTitle = trim(implode("\n", $results));

$results = $app->triggerEvent('onContentBeforeDisplay', [$this->category->extension . '.categories', &$this->category, &$this->params, 0]);
$beforeDisplayContent = trim(implode("\n", $results));

$results = $app->triggerEvent('onContentAfterDisplay', [$this->category->extension . '.categories', &$this->category, &$this->params, 0]);
$afterDisplayContent = trim(implode("\n", $results));
?>

<div class="com-content-category-blog blog">
    <?php if ($this->params->get('show_page_heading')) : ?>
        <div class="page-header mb-4">
            <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
        </div>
    <?php endif; ?>

    <?php // Hero con titolo e descrizione categoria ?>
    <?php if ($this->params->get('show_category_title', 1) || ($this->params->get('show_description', 1) && $this->category->description)) : ?>
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
                        
                        <?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
                            <div class="mt-3">
                                <img src="<?php echo $this->category->getParams()->get('image'); ?>" 
                                     alt="<?php echo $this->category->getParams()->get('image_alt') ?: $this->category->title; ?>" 
                                     class="img-fluid rounded">
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            </div>
        </div>
    <?php endif; ?>

    <?php if (empty($this->lead_items) && empty($this->link_items) && empty($this->intro_items)) : ?>
        <?php if ($this->params->get('show_no_articles', 1)) : ?>
            <div class="alert alert-info">
                <span class="icon-info-circle" aria-hidden="true"></span>
                <span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
                <?php echo Text::_('COM_CONTENT_NO_ARTICLES'); ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php // Griglia articoli ?>
    <?php if (!empty($this->lead_items) || !empty($this->intro_items)) : ?>
        <div class="row g-4 mt-4">
            <?php // Lead items ?>
            <?php if (!empty($this->lead_items)) : ?>
                <?php foreach ($this->lead_items as &$item) : ?>
                    <?php
                    $this->item = &$item;
                    echo $this->loadTemplate('item');
                    ?>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php // Intro items ?>
            <?php if (!empty($this->intro_items)) : ?>
                <?php foreach ($this->intro_items as $key => &$item) : ?>
                    <?php
                    $this->item = &$item;
                    echo $this->loadTemplate('item');
                    ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php // Link items ?>
    <?php if (!empty($this->link_items)) : ?>
        <div class="items-more mt-5">
            <?php echo $this->loadTemplate('links'); ?>
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
                <?php echo $this->pagination->getPagesLinks(); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php // Sottocategorie ?>
    <?php if ($this->maxLevel != 0 && !empty($this->children[$this->category->id])) : ?>
        <div class="container py-5 mt-5" id="argomento">
            <?php if ($this->params->get('show_category_heading_title_text', 1) == 1) : ?>
                <h2 class="title-xxlarge mb-4"><?php echo Text::_('JGLOBAL_SUBCATEGORIES'); ?></h2>
            <?php endif; ?>
            <?php echo $this->loadTemplate('children'); ?>
        </div>
    <?php endif; ?>

    <?php // Link per creare articolo ?>
    <?php if ($this->category->getParams()->get('access-create')) : ?>
        <div class="mt-4">
            <?php echo HTMLHelper::_('contenticon.create', $this->category, $this->category->params); ?>
        </div>
    <?php endif; ?>
</div>