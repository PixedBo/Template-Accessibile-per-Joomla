<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * Sottocategorie per il layout "Notizie" con data-element="news-category-link".
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper;

/** @var \Joomla\Component\Content\Site\View\Category\HtmlView $this */
$user   = $this->getCurrentUser();
$groups = $user->getAuthorisedViewLevels();

if ($this->maxLevel != 0 && count($this->children[$this->category->id]) > 0) : ?>
    <div class="row g-4">
        <?php foreach ($this->children[$this->category->id] as $id => $child) : ?>
            <?php if (in_array($child->access, $groups)) : ?>
                <?php if ($this->params->get('show_empty_categories') || $child->numitems || count($child->getChildren())) : ?>
                    <div class="col-md-6 col-xl-4">
                        <div class="cmp-card-simple card-wrapper pb-0 rounded border border-light">
                            <div class="card shadow-sm rounded">
                                <div class="card-body">
                                    <a class="text-decoration-none"
                                       href="<?php echo Route::_(RouteHelper::getCategoryRoute($child->id, $child->language)); ?>"
                                       data-element="news-category-link"
                                       data-focus-mouse="false">
                                        <h3 class="card-title t-primary">
                                            <?php echo $this->escape($child->title); ?>
                                            <?php if ($this->params->get('show_cat_num_articles', 1)) : ?>
                                                <span class="badge bg-info ms-2">
                                                    <?php echo $child->getNumItems(true); ?>
                                                </span>
                                            <?php endif; ?>
                                        </h3>
                                    </a>

                                    <?php if ($this->params->get('show_subcat_desc') == 1 && $child->description) : ?>
                                        <p class="text-secondary mb-0">
                                            <?php echo HTMLHelper::_('string.truncate', strip_tags($child->description), 150); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
