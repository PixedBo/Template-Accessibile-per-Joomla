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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper;

if (!class_exists('ModelloPAHelper')) {
    require_once JPATH_THEMES . '/' . Factory::getApplication()->getTemplate() . '/helpers/ModelloPAHelper.php';
}

/** @var \Joomla\Component\Content\Site\View\Featured\HtmlView $this */
?>

<div class="card border border-light rounded shadow-sm">
    <div class="card-body">
        <h3 class="card-title"><?php echo Text::_('COM_CONTENT_MORE_ARTICLES'); ?></h3>
        <ul class="list-unstyled">
            <?php foreach ($this->link_items as $item) : ?>
                <?php $deAttr = ModelloPAHelper::attributeFor((int) $item->catid); ?>
                <li class="mb-2">
                    <a href="<?php echo Route::_(RouteHelper::getArticleRoute($item->slug, $item->catid, $item->language)); ?>"
                       class="text-decoration-none"<?php echo $deAttr; ?>>
                        <span class="icon-chevron-right me-2" aria-hidden="true"></span>
                        <?php echo $this->escape($item->title); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>