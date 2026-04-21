<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Component\Content\Site\Helper\RouteHelper;

/** @var \Joomla\Component\Content\Site\View\Article\HtmlView $this */

$params  = $this->item->params;
$canEdit = $params->get('access-edit');
$user    = $this->getCurrentUser();
$info    = $params->get('info_block_position', 0);
$htag    = $this->params->get('show_page_heading') ? 'h2' : 'h1';

$assocParam        = (Associations::isEnabled() && $params->get('show_associations'));
$currentDate       = Factory::getDate()->format('Y-m-d H:i:s');
$isNotPublishedYet = $this->item->publish_up > $currentDate;
$isExpired         = !is_null($this->item->publish_down) && $this->item->publish_down < $currentDate;
$useDefList        = $params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
    || $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author') || $assocParam;

$app = Factory::getApplication();
$document = $app->getDocument();
$hasRightColumn = $document->countModules('colonna-destra') > 0;

$leftColClass = 'col-lg-3';
$centerColClass = $hasRightColumn ? 'col-lg-6' : 'col-lg-9';
$rightColClass = 'col-lg-3';
?>
    
    <div class="container pt-4">
        <div class="row">
            <div class="col-lg-8 px-lg-4 py-lg-2">
                <?php if ($params->get('show_title')) : ?>
                    <?php echo '<' . $htag . '>'; ?>
                        <?php echo $this->escape($this->item->title); ?>
                        <?php if ($this->item->state == ContentComponent::CONDITION_UNPUBLISHED) : ?>
                            <span class="badge bg-warning text-dark"><?php echo Text::_('JUNPUBLISHED'); ?></span>
                        <?php endif; ?>
                        <?php if ($isNotPublishedYet) : ?>
                            <span class="badge bg-warning text-dark"><?php echo Text::_('JNOTPUBLISHEDYET'); ?></span>
                        <?php endif; ?>
                        <?php if ($isExpired) : ?>
                            <span class="badge bg-warning text-dark"><?php echo Text::_('JEXPIRED'); ?></span>
                        <?php endif; ?>
                    <?php echo '</' . $htag . '>'; ?>
                <?php endif; ?>
                
                <h2 class="visually-hidden"><?php echo Text::_('TPL_ACCESSIBILE_ARTICLE_DETAILS'); ?></h2>

                <?php if ($this->item->introtext && !empty($this->item->fulltext) && $params->get('show_intro')) : ?>
                    <div class="lead">
                        <?php echo $this->item->introtext; ?>
                    </div>
                <?php endif; ?>
                
                <div class="row mt-5 mb-4">
                    <?php if ($params->get('show_publish_date') || $params->get('show_create_date')) : ?>
                        <div class="col-6">
                            <small><?php echo Text::_('TPL_ACCESSIBILE_DATE'); ?>:</small>
                            <p class="fw-semibold font-monospace">
                                <?php 
                                if ($params->get('show_publish_date')) {
                                    echo HTMLHelper::_('date', $this->item->publish_up, Text::_('d F Y'));
                                } elseif ($params->get('show_create_date')) {
                                    echo HTMLHelper::_('date', $this->item->created, Text::_('d F Y'));
                                }
                                ?>
                            </p>
                        </div>
                    <?php endif; ?>
                    
                    <div class="col-6">
                        <small><?php echo Text::_('TPL_ACCESSIBILE_READING_TIME'); ?>:</small>
                        <p class="fw-semibold" id="readingTime">
                            <?php 
                            $wordCount = str_word_count(strip_tags($this->item->text));
                            $readingTime = ceil($wordCount / 200); 
                            echo $readingTime . ' ' . Text::_('TPL_ACCESSIBILE_MINUTES');
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 offset-lg-1">
                <?php $spritePath = Uri::base(true) . '/templates/' . $app->getTemplate() . '/svg/sprites.svg'; ?>
                
                <?php // Blocco Condividi ?>
                <div class="d-flex align-items-center mb-3">
                    <span class="subtitle-small fw-semibold text-muted me-3 mb-0"><?php echo Text::_('TPL_ACCESSIBILE_SHARE'); ?>:</span>
                    <div class="d-flex gap-2">
                        <a class="btn btn-action-icon d-flex align-items-center justify-content-center rounded" 
                           href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(Uri::current()); ?>" 
                           target="_blank" rel="noopener noreferrer" 
                           title="<?php echo Text::_('TPL_ACCESSIBILE_SHARE_ON_FACEBOOK'); ?>"
                           aria-label="<?php echo Text::_('TPL_ACCESSIBILE_SHARE_ON_FACEBOOK'); ?> - <?php echo Text::_('TPL_ACCESSIBILE_NEW_WINDOW'); ?>">
                            <svg class="icon icon-sm" aria-hidden="true"><use href="<?php echo $spritePath; ?>#it-facebook"></use></svg>
                        </a>
                        <a class="btn btn-action-icon d-flex align-items-center justify-content-center rounded" 
                           href="https://twitter.com/intent/tweet?text=<?php echo urlencode(Uri::current()); ?>" 
                           target="_blank" rel="noopener noreferrer" 
                           title="<?php echo Text::_('TPL_ACCESSIBILE_SHARE_ON_X'); ?>"
                           aria-label="<?php echo Text::_('TPL_ACCESSIBILE_SHARE_ON_X'); ?> - <?php echo Text::_('TPL_ACCESSIBILE_NEW_WINDOW'); ?>">
                            <svg class="icon icon-sm" aria-hidden="true"><use href="<?php echo $spritePath; ?>#it-twitter"></use></svg>
                        </a>
                        <a class="btn btn-action-icon d-flex align-items-center justify-content-center rounded" 
                           href="https://www.linkedin.com/shareArticle?url=<?php echo urlencode(Uri::current()); ?>" 
                           target="_blank" rel="noopener noreferrer" 
                           title="<?php echo Text::_('TPL_ACCESSIBILE_SHARE_ON_LINKEDIN'); ?>"
                           aria-label="<?php echo Text::_('TPL_ACCESSIBILE_SHARE_ON_LINKEDIN'); ?> - <?php echo Text::_('TPL_ACCESSIBILE_NEW_WINDOW'); ?>">
                            <svg class="icon icon-sm" aria-hidden="true"><use href="<?php echo $spritePath; ?>#it-linkedin"></use></svg>
                        </a>
                        <a class="btn btn-action-icon d-flex align-items-center justify-content-center rounded" 
                           href="https://api.whatsapp.com/send?text=<?php echo urlencode(Uri::current()); ?>" 
                           target="_blank" rel="noopener noreferrer" 
                           title="<?php echo Text::_('TPL_ACCESSIBILE_SHARE_ON_WHATSAPP'); ?>"
                           aria-label="<?php echo Text::_('TPL_ACCESSIBILE_SHARE_ON_WHATSAPP'); ?> - <?php echo Text::_('TPL_ACCESSIBILE_NEW_WINDOW'); ?>">
                            <svg class="icon icon-sm" aria-hidden="true"><use href="<?php echo $spritePath; ?>#it-whatsapp"></use></svg>
                        </a>
                    </div>
                </div>
                
                <?php // Blocco Azioni ?>
                <div class="d-flex align-items-center mb-4">
                    <span class="subtitle-small fw-semibold text-muted me-3 mb-0"><?php echo Text::_('TPL_ACCESSIBILE_ACTIONS'); ?>:</span>
                    <div class="d-flex gap-2 align-items-center">
                        <button class="btn btn-action-icon d-flex align-items-center justify-content-center rounded" 
                                onclick="window.print();" 
                                title="<?php echo Text::_('TPL_ACCESSIBILE_PRINT_PAGE'); ?>"
                                aria-label="<?php echo Text::_('TPL_ACCESSIBILE_PRINT_PAGE'); ?>">
                            <svg class="icon icon-sm" aria-hidden="true"><use href="<?php echo $spritePath; ?>#it-print"></use></svg>
                        </button>
                        <a class="btn btn-action-icon d-flex align-items-center justify-content-center rounded" 
                           href="mailto:?subject=<?php echo urlencode($this->item->title); ?>&amp;body=<?php echo urlencode(Uri::current()); ?>" 
                           title="<?php echo Text::_('TPL_ACCESSIBILE_SEND_EMAIL'); ?>"
                           aria-label="<?php echo Text::_('TPL_ACCESSIBILE_SEND_EMAIL'); ?>">
                            <svg class="icon icon-sm" aria-hidden="true"><use href="<?php echo $spritePath; ?>#it-mail"></use></svg>
                        </a>
                        <?php if ($canEdit) : ?>
                            <div class="d-inline-block ms-1">
                                <?php echo LayoutHelper::render('joomla.content.icons', ['params' => $params, 'item' => $this->item]); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php // Argomenti/Tags ?>
                <?php if ($params->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
                    <div class="mt-4 mb-4">
                        <span class="subtitle-small mb-2 d-block fw-semibold text-muted"><?php echo Text::_('TPL_ACCESSIBILE_TAGS'); ?></span>
                        <div class="comuni-tags">
                            <?php $this->item->tagLayout = new FileLayout('joomla.content.tags'); ?>
                            <?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
            </div>
        </div>
    </div> <?php // Immagine full ?>
    <?php if ($params->get('access-view') && isset($this->item->images)) : ?>
        <?php $images = json_decode($this->item->images); ?>
        <?php if (!empty($images->image_fulltext)) : ?>
            <div class="container-fluid my-3">
                <div class="row">
                    <figure class="figure px-0 img-full">
                        <?php echo LayoutHelper::render('joomla.content.full_image', $this->item); ?>
                    </figure>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    
    <div class="container">
        <div class="row border-top border-light row-column-border row-column-menu-left">
            <?php // Sidebar sinistra con indice ?>
            <aside class="<?php echo $leftColClass; ?>">
                <div class="cmp-navscroll sticky-top" role="region" aria-labelledby="accordion-title-one">
                    <nav class="navbar it-navscroll-wrapper navbar-expand-lg" aria-label="<?php echo Text::_('TPL_ACCESSIBILE_PAGE_INDEX'); ?>" data-bs-navscroll="">
                        <div class="navbar-custom" id="navbarNavProgress">
                            <div class="menu-wrapper">
                                <div class="link-list-wrapper">
                                    <div class="accordion">
                                        <div class="accordion-item">
                                            <span class="accordion-header" id="accordion-title-one">
                                                <button class="accordion-button pb-10 px-3 text-uppercase" type="button" 
                                                        aria-controls="collapse-one" aria-expanded="true" 
                                                        data-bs-toggle="collapse" data-bs-target="#collapse-one">
                                                    <?php echo Text::_('TPL_ACCESSIBILE_PAGE_INDEX'); ?>
                                                    <i class="fa-solid fa-chevron-down icon-sm icon-primary align-top" aria-hidden="true"></i>
                                                </button>
                                            </span>
                                            <div class="progress">
                                                <div class="progress-bar it-navscroll-progressbar" role="progressbar"
                                                     aria-label="<?php echo htmlspecialchars(Text::_('TPL_ACCESSIBILE_READING_PROGRESS'), ENT_QUOTES, 'UTF-8'); ?>"
                                                     aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                            </div>
                                            <div id="collapse-one" class="accordion-collapse collapse show" role="region" 
                                                 aria-labelledby="accordion-title-one">
                                                <div class="accordion-body">
                                                    <ul class="link-list" data-element="page-index">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" href="#descrizione">
                                                                <span><?php echo Text::_('TPL_ACCESSIBILE_DESCRIPTION'); ?></span>
                                                            </a>
                                                        </li>
                                                        <?php if ($useDefList) : ?>
                                                            <li class="nav-item">
                                                                <a class="nav-link" href="#informazioni-articolo">
                                                                    <span><?php echo Text::_('TPL_ACCESSIBILE_ARTICLE_INFO'); ?></span>
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
                
                <?php if ($document->countModules('colonna-sinistra')) : ?>
                    <div class="mt-4">
                        <?php echo HTMLHelper::_('content.prepare', '{loadposition colonna-sinistra}'); ?>
                    </div>
                <?php endif; ?>
            </aside>
            
            <?php // Contenuto principale ?>
            <div class="<?php echo $centerColClass; ?> it-page-sections-container border-light">
                <?php echo $this->item->event->afterDisplayTitle; ?>
                <?php echo $this->item->event->beforeDisplayContent; ?>
                
                <?php if ($params->get('access-view')) : ?>
                    <article class="it-page-section anchor-offset">
                        <h2 id="descrizione"><?php echo Text::_('TPL_ACCESSIBILE_DESCRIPTION'); ?></h2>
                        <div class="richtext-wrapper lora">
                            <?php echo !empty($this->item->fulltext) ? $this->item->fulltext : $this->item->text; ?>
                        </div>
                    </article>
                    
                    <?php if ((int) $params->get('urls_position', 0) === 0) : ?>
                        <?php echo $this->loadTemplate('links'); ?>
                    <?php endif; ?>
                    
                    <?php if (!empty($this->item->pagination) && !$this->item->paginationposition && !$this->item->paginationrelative) : ?>
                        <?php echo $this->item->pagination; ?>
                    <?php endif; ?>
                    
                    <?php if (isset($this->item->toc)) : ?>
                        <?php echo $this->item->toc; ?>
                    <?php endif; ?>
                    
                    <?php if ($info == 1 || $info == 2) : ?>
                        <?php if ($useDefList) : ?>
                            <?php echo LayoutHelper::render('joomla.content.info_block', ['item' => $this->item, 'params' => $params, 'position' => 'below']); ?>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <?php if (!empty($this->item->pagination) && $this->item->paginationposition && !$this->item->paginationrelative) : ?>
                        <?php echo $this->item->pagination; ?>
                    <?php endif; ?>
                    
                    <?php if ((int) $params->get('urls_position', 0) === 1) : ?>
                        <?php echo $this->loadTemplate('links'); ?>
                    <?php endif; ?>
                    
                <?php elseif ($params->get('show_noauth') == true && $user->guest) : ?>
                    <?php echo LayoutHelper::render('joomla.content.intro_image', $this->item); ?>
                    <?php echo HTMLHelper::_('content.prepare', $this->item->introtext); ?>
                    
                    <?php if ($params->get('show_readmore') && $this->item->fulltext != null) : ?>
                        <?php $menu = Factory::getApplication()->getMenu(); ?>
                        <?php $active = $menu->getActive(); ?>
                        <?php $itemId = $active->id; ?>
                        <?php $link = new Uri(Route::_('index.php?option=com_users&view=login&Itemid=' . $itemId, false)); ?>
                        <?php $link->setVar('return', base64_encode(RouteHelper::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language))); ?>
                        <?php echo LayoutHelper::render('joomla.content.readmore', ['item' => $this->item, 'params' => $params, 'link' => $link]); ?>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php // Informazioni Articolo ?>
                <?php if ($useDefList) : ?>
                    <article class="it-page-section anchor-offset mt-5">
                        <h2 id="informazioni-articolo"><?php echo Text::_('TPL_ACCESSIBILE_ARTICLE_INFO'); ?></h2>
                        <div class="row">
                            <div class="col-12">
                                <dl class="row">
                                    <?php if ($params->get('show_category')) : ?>
                                        <dt class="col-sm-3 fw-semibold"><?php echo Text::_('JCATEGORY'); ?>:</dt>
                                        <dd class="col-sm-9">
                                            <?php if ($params->get('link_category') && !empty($this->item->catid)) : ?>
                                                <a href="<?php echo Route::_(RouteHelper::getCategoryRoute($this->item->catid)); ?>">
                                                    <?php echo $this->escape($this->item->category_title); ?>
                                                </a>
                                            <?php else : ?>
                                                <?php echo $this->escape($this->item->category_title); ?>
                                            <?php endif; ?>
                                        </dd>
                                    <?php endif; ?>
                                    
                                    <?php if ($params->get('show_parent_category') && !empty($this->item->parent_id) && $this->item->parent_id != 'root') : ?>
                                        <dt class="col-sm-3 fw-semibold"><?php echo Text::_('TPL_ACCESSIBILE_PARENT_CATEGORY'); ?>:</dt>
                                        <dd class="col-sm-9">
                                            <?php if ($params->get('link_parent_category')) : ?>
                                                <a href="<?php echo Route::_(RouteHelper::getCategoryRoute($this->item->parent_id)); ?>">
                                                    <?php echo $this->escape($this->item->parent_title); ?>
                                                </a>
                                            <?php else : ?>
                                                <?php echo $this->escape($this->item->parent_title); ?>
                                            <?php endif; ?>
                                        </dd>
                                    <?php endif; ?>
                                    
                                    <?php if ($params->get('show_author')) : ?>
                                        <dt class="col-sm-3 fw-semibold"><?php echo Text::_('JAUTHOR'); ?>:</dt>
                                        <dd class="col-sm-9">
                                            <?php if (!empty($this->item->author)) : ?>
                                                <?php if ($params->get('link_author') && !empty($this->item->contact_link)) : ?>
                                                    <a href="<?php echo Route::_($this->item->contact_link); ?>">
                                                        <?php echo $this->escape($this->item->author); ?>
                                                    </a>
                                                <?php else : ?>
                                                    <?php echo $this->escape($this->item->author); ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </dd>
                                    <?php endif; ?>
                                    
                                    <?php if ($params->get('show_create_date')) : ?>
                                        <dt class="col-sm-3 fw-semibold"><?php echo Text::_('TPL_ACCESSIBILE_CREATED'); ?>:</dt>
                                        <dd class="col-sm-9">
                                            <?php echo HTMLHelper::_('date', $this->item->created, Text::_('DATE_FORMAT_LC3')); ?>
                                        </dd>
                                    <?php endif; ?>
                                    
                                    <?php if ($params->get('show_publish_date')) : ?>
                                        <dt class="col-sm-3 fw-semibold"><?php echo Text::_('JPUBLISHED'); ?>:</dt>
                                        <dd class="col-sm-9">
                                            <?php echo HTMLHelper::_('date', $this->item->publish_up, Text::_('DATE_FORMAT_LC3')); ?>
                                        </dd>
                                    <?php endif; ?>
                                    
                                    <?php if ($params->get('show_modify_date')) : ?>
                                        <dt class="col-sm-3 fw-semibold"><?php echo Text::_('TPL_ACCESSIBILE_MODIFIED'); ?>:</dt>
                                        <dd class="col-sm-9">
                                            <?php echo HTMLHelper::_('date', $this->item->modified, Text::_('DATE_FORMAT_LC3')); ?>
                                        </dd>
                                    <?php endif; ?>
                                    
                                    <?php if ($params->get('show_hits')) : ?>
                                        <dt class="col-sm-3 fw-semibold"><?php echo Text::_('JGLOBAL_HITS'); ?>:</dt>
                                        <dd class="col-sm-9">
                                            <?php echo $this->item->hits; ?>
                                        </dd>
                                    <?php endif; ?>
                                </dl>
                            </div>
                        </div>
                    </article>
                <?php endif; ?>
                
                <?php echo $this->item->event->afterDisplayContent; ?>
             </div>
            
            <?php // Sidebar destra (solo se ci sono moduli) ?>
            <?php if ($hasRightColumn) : ?>
                <aside class="<?php echo $rightColClass; ?>">
                    <?php echo HTMLHelper::_('content.prepare', '{loadposition colonna-destra}'); ?>
                </aside>
            <?php endif; ?>
        </div>
    </div>