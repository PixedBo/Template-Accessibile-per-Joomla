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
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Component\Content\Site\Helper\RouteHelper;

if (!class_exists('ModelloPAHelper')) {
    require_once JPATH_THEMES . '/' . Factory::getApplication()->getTemplate() . '/helpers/ModelloPAHelper.php';
}

/** @var \Joomla\Component\Content\Site\View\Featured\HtmlView $this */
$params = &$this->item->params;
$canEdit = $this->item->params->get('access-edit');
$info = $this->item->params->get('info_block_position', 0);
$assocParam = (Associations::isEnabled() && $params->get('show_associations'));

$currentDate = Factory::getDate()->format('Y-m-d H:i:s');
$isExpired = !is_null($this->item->publish_down) && $this->item->publish_down < $currentDate;
$isNotPublishedYet = $this->item->publish_up > $currentDate;
$isUnpublished = $this->item->state == ContentComponent::CONDITION_UNPUBLISHED || $isNotPublishedYet || $isExpired;

// Determina se l'articolo ha un'immagine
$images = json_decode($this->item->images);
$hasImage = !empty($images->image_intro);

// Determina la classe della colonna
$colClass = 'col-md-6 col-xl-4';

// Ottieni la categoria dell'articolo
$category = $this->item->category_title ?? '';
$categoryLink = $this->item->catid ? Route::_(RouteHelper::getCategoryRoute($this->item->catid, $this->item->language)) : '';

// data-element Modello PA in base alla categoria (service-link, news-link, event-link, document-link)
$deAttr = ModelloPAHelper::attributeFor((int) $this->item->catid);

// Formatta la data di pubblicazione
$displayDate = '';
if (!empty($this->item->publish_up)) {
    $date = Factory::getDate($this->item->publish_up);
    // Formato: GG MMM AA (es: 19 SET 22)
    $displayDate = strtoupper($date->format('d M y'));
}
?>

<div class="<?php echo $colClass; ?>">
    <div class="card-wrapper border border-light rounded shadow-sm <?php echo !$hasImage ? 'cmp-list-card-img cmp-list-card-img-hr' : ''; ?>">
        <div class="card no-after rounded">
            <?php if ($isUnpublished) : ?>
                <div class="system-unpublished">
            <?php endif; ?>

            <?php if ($hasImage) : ?>
                <?php // Card con immagine ?>
                <div class="img-responsive-wrapper">
                    <div class="img-responsive img-responsive-panoramic">
                        <figure class="img-wrapper">
                            <?php if ($params->get('link_titles') && $params->get('access-view')) : ?>
                                <a href="<?php echo Route::_(RouteHelper::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language)); ?>"<?php echo $deAttr; ?>>
                                    <img src="<?php echo htmlspecialchars($images->image_intro); ?>"
                                         alt="<?php echo htmlspecialchars($images->image_intro_alt ?: $this->item->title); ?>" 
                                         title="<?php echo htmlspecialchars($this->item->title); ?>">
                                </a>
                            <?php else : ?>
                                <img src="<?php echo htmlspecialchars($images->image_intro); ?>" 
                                     alt="<?php echo htmlspecialchars($images->image_intro_alt ?: $this->item->title); ?>" 
                                     title="<?php echo htmlspecialchars($this->item->title); ?>">
                            <?php endif; ?>
                        </figure>
                    </div>
                </div>
                <div class="card-body">
            <?php else : ?>
                <?php // Card senza immagine ?>
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

                    <?php if ($params->get('show_title')) : ?>
                        <?php if ($params->get('link_titles') && $params->get('access-view')) : ?>
                            <a class="text-decoration-none" href="<?php echo Route::_(RouteHelper::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language)); ?>"<?php echo $deAttr; ?>>
                                <h3 class="card-title"><?php echo $this->escape($this->item->title); ?></h3>
                            </a>
                        <?php else : ?>
                            <h3 class="card-title"><?php echo $this->escape($this->item->title); ?></h3>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php // Badge per stati non pubblicati ?>
                    <?php if ($this->item->state == ContentComponent::CONDITION_UNPUBLISHED) : ?>
                        <span class="badge bg-warning text-light"><?php echo Text::_('JUNPUBLISHED'); ?></span>
                    <?php endif; ?>
                    <?php if ($isNotPublishedYet) : ?>
                        <span class="badge bg-warning text-light"><?php echo Text::_('JNOTPUBLISHEDYET'); ?></span>
                    <?php endif; ?>
                    <?php if ($isExpired) : ?>
                        <span class="badge bg-warning text-light"><?php echo Text::_('JEXPIRED'); ?></span>
                    <?php endif; ?>

                    <?php if ($this->item->introtext) : ?>
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