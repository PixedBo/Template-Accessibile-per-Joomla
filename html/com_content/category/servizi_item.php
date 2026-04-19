<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * Card servizio (cmp-card-latest-messages) con data-element="service-link"
 * per il layout alternativo "Servizi" (blog_servizi).
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Component\Content\Site\Helper\RouteHelper;

/** @var \Joomla\Component\Content\Site\View\Category\HtmlView $this */
$params = $this->item->params;

$currentDate   = Factory::getDate()->format('Y-m-d H:i:s');
$isUnpublished = ($this->item->state == ContentComponent::CONDITION_UNPUBLISHED || $this->item->publish_up > $currentDate)
    || ($this->item->publish_down < $currentDate && $this->item->publish_down !== null);

$articleLink  = Route::_(RouteHelper::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language));
$category     = $this->item->category_title ?? '';
$categoryLink = $this->item->catid ? Route::_(RouteHelper::getCategoryRoute($this->item->catid, $this->item->language)) : '';
$description  = $this->item->introtext ? trim(strip_tags($this->item->introtext)) : '';
?>

<div class="cmp-card-latest-messages mb-3 mb-30">
    <div class="card shadow-sm px-4 pt-4 pb-4 rounded <?php echo $isUnpublished ? 'system-unpublished' : ''; ?>">

        <?php if ($category) : ?>
            <span class="visually-hidden"><?php echo Text::_('JCATEGORY'); ?>:</span>
            <div class="card-header border-0 p-0">
                <?php if ($categoryLink) : ?>
                    <a class="text-decoration-none title-xsmall-bold mb-2 category text-uppercase" href="<?php echo $categoryLink; ?>">
                        <?php echo $this->escape($category); ?>
                    </a>
                <?php else : ?>
                    <span class="title-xsmall-bold mb-2 category text-uppercase">
                        <?php echo $this->escape($category); ?>
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="card-body p-0 my-2">
            <h3 class="green-title-big t-primary mb-8">
                <a class="text-decoration-none" href="<?php echo $articleLink; ?>" data-element="service-link">
                    <?php echo $this->escape($this->item->title); ?>
                </a>
            </h3>
            <?php if ($params->get('show_intro', 1) && $description) : ?>
                <p class="text-paragraph">
                    <?php echo HTMLHelper::_('string.truncate', $description, 220); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>
