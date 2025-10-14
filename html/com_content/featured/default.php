<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/** @var \Joomla\Component\Content\Site\View\Featured\HtmlView $this */
?>

<div class="blog-featured">
    <?php // Hero con titolo pagina ?>
    <?php if ($this->params->get('show_page_heading') != 0) : ?>
        <div class="col-12 col-lg-10">
            <div class="cmp-hero">
                <section class="it-hero-wrapper bg-white align-items-start">
                    <div class="it-hero-text-wrapper pt-0 ps-0 pb-4 pb-lg-60">
                        <h1 class="text-black hero-title">
                            <?php echo $this->escape($this->params->get('page_heading')); ?>
                        </h1>
                    </div>
                </section>
            </div>
        </div>
    <?php endif; ?>

    <?php // Verifica se ci sono articoli ?>
    <?php if (empty($this->lead_items) && empty($this->intro_items) && empty($this->link_items)) : ?>
        <div class="alert alert-info">
            <span class="icon-info-circle" aria-hidden="true"></span>
            <span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
            <?php echo Text::_('COM_CONTENT_NO_ARTICLES'); ?>
        </div>
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
    <?php if ($this->params->def('show_pagination', 2) == 1 || ($this->params->get('show_pagination') == 2 && $this->pagination->pagesTotal > 1)) : ?>
        <div class="w-100 mt-5">
            <?php if ($this->params->def('show_pagination_results', 1)) : ?>
                <p class="counter text-end pt-3 pe-2">
                    <?php echo $this->pagination->getPagesCounter(); ?>
                </p>
            <?php endif; ?>
            <div class="pagination-wrapper">
                <?php echo $this->pagination->getPagesLinks(); ?>
            </div>
        </div>
    <?php endif; ?>
</div>