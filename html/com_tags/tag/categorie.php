<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_tags
 *
 * Layout alternativo: Articoli raggruppati per categoria
 *
 * @copyright   (C) 2013 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

/** @var \Joomla\Component\Tags\Site\View\Tag\HtmlView $this */
$isSingleTag = count($this->item) === 1;
$tagTitle    = $isSingleTag ? $this->item[0]->title : $this->tags_title;
$heading     = $tagTitle;

// Descrizione del tag (solo se singolo tag)
$tagDescription = '';
if ($isSingleTag && $this->params->get('tag_list_show_tag_description', 1) && !empty($this->item[0]->description)) {
    $tagDescription = $this->item[0]->description;
}
?>

<div class="com-tags-tag tag-category">
    <div class="container">
        <div class="row justify-content-center row-shadow">
            <div class="col-12 col-lg-10">
                <div class="cmp-hero">
                    <section class="it-hero-wrapper bg-white align-items-start">
                        <div class="it-hero-text-wrapper pt-0 ps-0 pb-4 pb-lg-60">
                            <h1 class="text-black hero-title">
                                <?php echo $this->escape($heading); ?>
                            </h1>
                            <?php if (!empty($tagDescription)) : ?>
                                <div class="hero-text">
                                    <?php echo HTMLHelper::_('content.prepare', $tagDescription, '', 'com_tags.tag'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <?php echo $this->loadTemplate('items'); ?>

    <?php // Paginazione ?>
    <?php if (($this->params->def('show_pagination', 1) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
        <div class="com-tags-tag__pagination w-100 mt-5">
            <?php if ($this->params->def('show_pagination_results', 1)) : ?>
                <p class="counter float-end pt-3 pe-2">
                    <?php echo $this->pagination->getPagesCounter(); ?>
                </p>
            <?php endif; ?>
            <?php echo $this->pagination->getPagesLinks(); ?>
        </div>
    <?php endif; ?>
</div>
