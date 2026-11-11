<?php

/**
 * Override del layout core joomla.content.tags: i tag sono resi come chip di Bootstrap Italia
 * (stesso markup del partial accessibile.argomenti). Usato dalle viste categoria.
 *
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   (C) 2013 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Tags\Site\Helper\RouteHelper;

$authorised = Factory::getApplication()->getIdentity()->getAuthorisedViewLevels();

?>
<?php if (!empty($displayData)) : ?>
    <ul class="d-flex flex-wrap gap-1 list-unstyled ps-0 mb-0" aria-label="<?php echo Text::_('TPL_ACCESSIBILE_TAGS'); ?>">
        <?php foreach ($displayData as $tag) : ?>
            <?php if (in_array($tag->access, $authorised)) : ?>
                <li>
                    <a class="chip chip-simple chip-primary"
                       href="<?php echo Route::_(RouteHelper::getComponentTagRoute($tag->tag_id . ':' . $tag->alias, $tag->language)); ?>">
                        <span class="chip-label"><?php echo $this->escape($tag->title); ?></span>
                    </a>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
