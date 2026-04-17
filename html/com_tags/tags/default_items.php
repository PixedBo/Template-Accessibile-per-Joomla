<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_tags
 *
 * @copyright   (C) 2013 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Tags\Site\Helper\RouteHelper;

/** @var \Joomla\Component\Tags\Site\View\Tags\HtmlView $this */
/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->getDocument()->getWebAssetManager();
$wa->useScript('com_tags.tags-default');

$user = $this->getCurrentUser();
$n    = count($this->items);
?>

<div class="com-tags__items">
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

    <?php if (!$this->items || $n === 0) : ?>
        <div class="alert alert-info">
            <span class="icon-info-circle" aria-hidden="true"></span><span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
            <?php echo Text::_('COM_TAGS_NO_TAGS'); ?>
        </div>
    <?php else : ?>
        <div class="container py-5" id="argomento">
            <h2 class="title-xxlarge mb-4"><?php echo Text::_('TPL_ACCESSIBILE_TAGS_EXPLORE'); ?></h2>
            <div class="row g-4">
                <?php foreach ($this->items as $item) : ?>
                    <?php if (!empty($item->access) && in_array($item->access, $user->getAuthorisedViewLevels())) : ?>
                        <div class="col-md-6 col-xl-4">
                            <div class="cmp-card-simple card-wrapper pb-0 rounded border border-light">
                                <div class="card shadow-sm rounded">
                                    <div class="card-body">
                                        <a class="text-decoration-none" href="<?php echo Route::_(RouteHelper::getComponentTagRoute($item->id . ':' . $item->alias, $item->language)); ?>" data-element="topic-element">
                                            <h3 class="card-title t-primary title-xlarge"><?php echo $this->escape($item->title); ?></h3>
                                        </a>
                                        <p class="titillium text-paragraph mb-0 description">
                                            <?php if ($this->params->get('all_tags_show_tag_description', 1) && !empty($item->description)) : ?>
                                                <?php echo HTMLHelper::_('string.truncate', strip_tags($item->description), $this->params->get('all_tags_tag_maximum_characters', 150)); ?>
                                            <?php else : ?>
                                                <?php echo Text::sprintf('TPL_ACCESSIBILE_TAGS_DESCRIPTION_PREFIX', $this->escape($item->title)); ?>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php // Paginazione ?>
    <?php if (!empty($this->items)) : ?>
        <?php if (($this->params->def('show_pagination', 2) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
            <div class="com-tags__pagination w-100 mt-5">
                <?php if ($this->params->def('show_pagination_results', 1)) : ?>
                    <p class="counter float-end pt-3 pe-2">
                        <?php echo $this->pagination->getPagesCounter(); ?>
                    </p>
                <?php endif; ?>
                <?php echo $this->pagination->getPagesLinks(); ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
