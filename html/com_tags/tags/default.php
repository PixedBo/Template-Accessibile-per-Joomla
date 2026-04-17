<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_tags
 *
 * @copyright   (C) 2013 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/** @var \Joomla\Component\Tags\Site\View\Tags\HtmlView $this */
$description = $this->params->get('all_tags_description');
$pageHeading = $this->params->get('page_heading') ?: Factory::getApplication()->getDocument()->getTitle();
?>
<div class="com-tags tag-category">
    <div class="container">
        <div class="row justify-content-center row-shadow">
            <div class="col-12 col-lg-10">
                <div class="cmp-hero">
                    <section class="it-hero-wrapper bg-white align-items-start">
                        <div class="it-hero-text-wrapper pt-0 ps-0 pb-4 pb-lg-60">
                            <h1 class="text-black hero-title">
                                <?php echo $this->escape($pageHeading); ?>
                            </h1>
                            <?php if (!empty($description)) : ?>
                                <div class="hero-text">
                                    <p><?php echo $this->escape($description); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <?php echo $this->loadTemplate('items'); ?>
</div>
