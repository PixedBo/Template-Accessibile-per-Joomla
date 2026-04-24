<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * Card notizia per il layout "Notizie" (Modello PA).
 * I link agli articoli non portano data-element (conforme ai requisiti AGID).
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Component\Content\Site\Helper\RouteHelper;

/** @var \Joomla\Component\Content\Site\View\Category\HtmlView $this */
$params  = $this->item->params;
$canEdit = $this->item->params->get('access-edit');

$currentDate   = Factory::getDate()->format('Y-m-d H:i:s');
$nullDate      = '0000-00-00 00:00:00';
$isUnpublished = ($this->item->state == ContentComponent::CONDITION_UNPUBLISHED || $this->item->publish_up > $currentDate)
    || ($this->item->publish_down !== null
        && $this->item->publish_down !== $nullDate
        && $this->item->publish_down < $currentDate);

$images   = is_string($this->item->images) && $this->item->images !== '' ? json_decode($this->item->images) : null;
$hasImage = is_object($images) && isset($images->image_intro) && $images->image_intro !== '';

$category     = $this->item->category_title ?? '';
$categoryLink = $this->item->catid ? Route::_(RouteHelper::getCategoryRoute($this->item->catid, $this->item->language)) : '';

$displayDate = '';
if (!empty($this->item->publish_up)) {
    $displayDate = strtoupper(Factory::getDate($this->item->publish_up)->format('d M y'));
}

$articleLink = Route::_(RouteHelper::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language));
?>

<div class="col-md-6 col-xl-4">
    <div class="card-wrapper border border-light rounded shadow-sm <?php echo !$hasImage ? 'cmp-list-card-img cmp-list-card-img-hr' : ''; ?>">
        <div class="card no-after rounded">
            <?php if ($isUnpublished) : ?>
                <div class="system-unpublished">
            <?php endif; ?>

            <?php if ($hasImage) : ?>
                <div class="img-responsive-wrapper">
                    <div class="img-responsive img-responsive-panoramic">
                        <figure class="img-wrapper">
                            <a href="<?php echo $articleLink; ?>">
                                <img src="<?php echo htmlspecialchars($images->image_intro); ?>"
                                     alt="<?php echo htmlspecialchars(($images->image_intro_alt ?? '') ?: $this->item->title); ?>"
                                     title="<?php echo htmlspecialchars($this->item->title); ?>">
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

                    <div class="category-top">
                        <?php if ($category) : ?>
                            <?php if ($categoryLink) : ?>
                                <a class="category text-decoration-none" href="<?php echo $categoryLink; ?>">
                                    <?php echo strtoupper(htmlspecialchars($category)); ?>
                                </a>
                            <?php else : ?>
                                <span class="category text-decoration-none">
                                    <?php echo strtoupper(htmlspecialchars($category)); ?>
                                </span>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($displayDate) : ?>
                            <span class="data"><?php echo $displayDate; ?></span>
                        <?php endif; ?>
                    </div>

                    <a class="text-decoration-none" href="<?php echo $articleLink; ?>">
                        <h3 class="card-title"><?php echo $this->escape($this->item->title); ?></h3>
                    </a>

                    <?php if ($params->get('show_intro') && $this->item->introtext) : ?>
                        <p class="card-text text-secondary">
                            <?php echo HTMLHelper::_('string.truncate', strip_tags($this->item->introtext), 150); ?>
                        </p>
                    <?php endif; ?>

                    <?php if ($canEdit) : ?>
                        <div class="mt-2">
                            <?php echo LayoutHelper::render('joomla.content.icons', ['params' => $params, 'item' => $this->item]); ?>
                        </div>
                    <?php endif; ?>

            <?php if ($hasImage) : ?>
                </div>
            <?php else : ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($isUnpublished) : ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
