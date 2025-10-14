<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   (C) 2010 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper;

/** @var \Joomla\Component\Content\Site\View\Category\HtmlView $this */
$lang = $this->getLanguage();
$user = $this->getCurrentUser();
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
                                       data-element="news-category-link">
                                        <h3 class="card-title t-primary title-xlarge">
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

                                    <?php if ($this->maxLevel > 1 && count($child->getChildren()) > 0) : ?>
                                        <div class="mt-3">
                                            <a href="#category-<?php echo $child->id; ?>" 
                                               data-bs-toggle="collapse" 
                                               class="btn btn-sm btn-outline-primary" 
                                               aria-label="<?php echo Text::_('JGLOBAL_EXPAND_CATEGORIES'); ?>">
                                                <span class="icon-plus me-1" aria-hidden="true"></span>
                                                <?php echo Text::_('JGLOBAL_SUBCATEGORIES'); ?>
                                            </a>
                                        </div>
                                        
                                        <div class="collapse fade mt-3" id="category-<?php echo $child->id; ?>">
                                            <?php
                                            $this->children[$child->id] = $child->getChildren();
                                            $this->category = $child;
                                            $this->maxLevel--;
                                            echo $this->loadTemplate('children');
                                            $this->category = $child->getParent();
                                            $this->maxLevel++;
                                            ?>
                                        </div>
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