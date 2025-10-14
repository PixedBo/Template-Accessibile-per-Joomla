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
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Component\Content\Site\Helper\RouteHelper;

/** @var \Joomla\Component\Content\Site\View\Article\HtmlView $this */

// Create shortcuts to some parameters.
$params  = $this->item->params;
$canEdit = $params->get('access-edit');
$user    = $this->getCurrentUser();
$info    = $params->get('info_block_position', 0);
$htag    = $this->params->get('show_page_heading') ? 'h2' : 'h1';

// Check if associations are implemented. If they are, define the parameter.
$assocParam        = (Associations::isEnabled() && $params->get('show_associations'));
$currentDate       = Factory::getDate()->format('Y-m-d H:i:s');
$isNotPublishedYet = $this->item->publish_up > $currentDate;
$isExpired         = !is_null($this->item->publish_down) && $this->item->publish_down < $currentDate;
$useDefList        = $params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
    || $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author') || $assocParam;

// Controllo presenza moduli nella colonna destra
$app = Factory::getApplication();
$document = $app->getDocument();
$hasRightColumn = $document->countModules('colonna-destra') > 0;

// Definisco le classi delle colonne in base alla presenza della colonna destra
$leftColClass = $hasRightColumn ? 'col-lg-3' : 'col-lg-3';
$centerColClass = $hasRightColumn ? 'col-lg-6' : 'col-lg-9';
$rightColClass = 'col-lg-3';
?>
    
        <div class="row">
            <div class="col-lg-8 px-lg-4 py-lg-2">
                <?php if ($params->get('show_title')) : ?>
                    <<?php echo $htag; ?> data-audio="">
                        <?php echo $this->escape($this->item->title); ?>
                        <?php if ($this->item->state == ContentComponent::CONDITION_UNPUBLISHED) : ?>
                            <span class="badge bg-warning text-light"><?php echo Text::_('JUNPUBLISHED'); ?></span>
                        <?php endif; ?>
                        <?php if ($isNotPublishedYet) : ?>
                            <span class="badge bg-warning text-light"><?php echo Text::_('JNOTPUBLISHEDYET'); ?></span>
                        <?php endif; ?>
                        <?php if ($isExpired) : ?>
                            <span class="badge bg-warning text-light"><?php echo Text::_('JEXPIRED'); ?></span>
                        <?php endif; ?>
                    </<?php echo $htag; ?>>
                <?php endif; ?>
                
                <h2 class="visually-hidden" data-audio="">Dettagli della notizia</h2>
                
                <?php if ($this->item->introtext && $params->get('show_intro')) : ?>
                    <p data-audio="">
                        <?php echo $this->item->introtext; ?>
                    </p>
                <?php endif; ?>
                
                <div class="row mt-5 mb-4">
                    <?php if ($params->get('show_publish_date') || $params->get('show_create_date')) : ?>
                        <div class="col-6">
                            <small>Data:</small>
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
                        <small>Tempo di lettura:</small>
                        <p class="fw-semibold" id="readingTime">
                            <?php // Calcolo automatico tempo di lettura ?>
                            <?php 
                            $wordCount = str_word_count(strip_tags($this->item->text));
                            $readingTime = ceil($wordCount / 200); // 200 parole al minuto
                            echo $readingTime . ' min';
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 offset-lg-1">
                <?php // Dropdown Condividi ?>
                <div class="dropdown d-inline">
                    <button class="btn btn-dropdown dropdown-toggle text-decoration-underline d-inline-flex align-items-center fs-0" 
                            type="button" id="shareActions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" 
                            aria-label="condividi sui social">
                        <i class="fa-solid fa-share-nodes" aria-hidden="true"></i>
                        <small>Condividi</small>
                    </button>
                    <div class="dropdown-menu shadow-lg" aria-labelledby="shareActions">
                        <div class="link-list-wrapper">
                            <ul class="link-list" role="menu">
                                <li role="none">
                                    <a class="list-item" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(Uri::current()); ?>" 
                                       target="_blank" role="menuitem">
                                        <i class="fa-brands fa-facebook" aria-hidden="true"></i>
                                        <span>Facebook</span>
                                    </a>
                                </li>
                                <li role="none">
                                    <a class="list-item" href="https://twitter.com/intent/tweet?text=<?php echo urlencode(Uri::current()); ?>" 
                                       target="_blank" role="menuitem">
                                        <i class="fa-brands fa-twitter" aria-hidden="true"></i>
                                        <span>Twitter</span>
                                    </a>
                                </li>
                                <li role="none">
                                    <a class="list-item" href="https://www.linkedin.com/shareArticle?url=<?php echo urlencode(Uri::current()); ?>" 
                                       target="_blank" role="menuitem">
                                        <i class="fa-brands fa-linkedin" aria-hidden="true"></i>
                                        <span>Linkedin</span>
                                    </a>
                                </li>
                                <li role="none">
                                    <a class="list-item" href="https://api.whatsapp.com/send?text=<?php echo urlencode(Uri::current()); ?>" 
                                       target="_blank" role="menuitem">
                                        <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                                        <span>Whatsapp</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <?php // Dropdown Azioni ?>
                <div class="dropdown d-inline">
                    <button class="btn btn-dropdown dropdown-toggle text-decoration-underline d-inline-flex align-items-center fs-0" 
                            type="button" id="viewActions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa-solid fa-ellipsis" aria-hidden="true"></i>
                        <small>Vedi azioni</small>
                    </button>
                    <div class="dropdown-menu shadow-lg" aria-labelledby="viewActions">
                        <div class="link-list-wrapper">
                            <ul class="link-list" role="menu">
                                <li role="none">
                                    <a class="list-item" href="#" onclick="window.print(); return false;" role="menuitem">
                                        <i class="fa-solid fa-print" aria-hidden="true"></i>
                                        <span>Stampa</span>
                                    </a>
                                </li>
                                <?php if ($canEdit) : ?>
                                    <li role="none">
                                        <?php echo LayoutHelper::render('joomla.content.icons', ['params' => $params, 'item' => $this->item]); ?>
                                    </li>
                                <?php endif; ?>
                                <li role="none">
                                    <a class="list-item" href="mailto:?subject=<?php echo urlencode($this->item->title); ?>&amp;body=<?php echo urlencode(Uri::current()); ?>" 
                                       role="menuitem">
                                        <i class="fa-solid fa-envelope" aria-hidden="true"></i>
                                        <span>Invia</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <?php // Argomenti/Tags ?>
                <?php if ($params->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
                    <div class="mt-4 mb-4">
                        <span class="subtitle-small">Argomenti</span>
                        <div class="d-flex flex-wrap gap-1">
                            <?php $this->item->tagLayout = new FileLayout('joomla.content.tags'); ?>
                            <?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php // Immagine full ?>
    <?php if ($params->get('access-view')) : ?>
        <div class="container-fluid my-3">
            <div class="row">
                <figure class="figure px-0 img-full">
                    <?php echo LayoutHelper::render('joomla.content.full_image', $this->item); ?>
                </figure>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="container">
        <div class="row border-top border-light row-column-border row-column-menu-left">
            <?php // Sidebar sinistra con indice ?>
            <aside class="<?php echo $leftColClass; ?>">
                <div class="cmp-navscroll sticky-top" aria-labelledby="accordion-title-one">
                    <nav class="navbar it-navscroll-wrapper navbar-expand-lg" aria-label="Indice della pagina" data-bs-navscroll="">
                        <div class="navbar-custom" id="navbarNavProgress">
                            <div class="menu-wrapper">
                                <div class="link-list-wrapper">
                                    <div class="accordion">
                                        <div class="accordion-item">
                                            <span class="accordion-header" id="accordion-title-one">
                                                <button class="accordion-button pb-10 px-3 text-uppercase" type="button" 
                                                        aria-controls="collapse-one" aria-expanded="true" 
                                                        data-bs-toggle="collapse" data-bs-target="#collapse-one">
                                                    INDICE DELLA PAGINA
                                                    <i class="fa-solid fa-chevron-down icon-sm icon-primary align-top" aria-hidden="true"></i>
                                                </button>
                                            </span>
                                            <div class="progress">
                                                <div class="progress-bar it-navscroll-progressbar" role="progressbar" 
                                                     aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                            </div>
                                            <div id="collapse-one" class="accordion-collapse collapse show" role="region" 
                                                 aria-labelledby="accordion-title-one">
                                                <div class="accordion-body">
                                                    <ul class="link-list" data-element="page-index">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" href="#descrizione">
                                                                <span>Descrizione</span>
                                                            </a>
                                                        </li>
                                                        <?php if ($useDefList) : ?>
                                                            <li class="nav-item">
                                                                <a class="nav-link" href="#informazioni-articolo">
                                                                    <span>Informazioni Articolo</span>
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
                
                <?php // Posizione modulo colonna-sinistra ?>
                <?php if ($document->countModules('colonna-sinistra')) : ?>
                    <div class="mt-4">
                        <?php echo HTMLHelper::_('content.prepare', '{loadposition colonna-sinistra}'); ?>
                    </div>
                <?php endif; ?>
            </aside>
            
            <?php // Contenuto principale ?>
            <section class="<?php echo $centerColClass; ?> it-page-sections-container border-light">
                <?php // Content is generated by content plugin event "onContentAfterTitle" ?>
                <?php echo $this->item->event->afterDisplayTitle; ?>
                
                <?php // Content is generated by content plugin event "onContentBeforeDisplay" ?>
                <?php echo $this->item->event->beforeDisplayContent; ?>
                
                <?php if ($params->get('access-view')) : ?>
                    <article class="it-page-section anchor-offset" data-audio="">
                        <h4 id="descrizione">Descrizione</h4>
                        <div class="richtext-wrapper lora">
                            <?php echo $this->item->text; ?>
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
                        <h4 id="informazioni-articolo">Informazioni Articolo</h4>
                        <div class="row">
                            <div class="col-12">
                                <dl class="row">
                                    <?php // Categoria ?>
                                    <?php if ($params->get('show_category')) : ?>
                                        <dt class="col-sm-3 fw-semibold">Categoria:</dt>
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
                                    
                                    <?php // Categoria Genitore ?>
                                    <?php if ($params->get('show_parent_category') && !empty($this->item->parent_id) && $this->item->parent_id != 'root') : ?>
                                        <dt class="col-sm-3 fw-semibold">Categoria Genitore:</dt>
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
                                    
                                    <?php // Autore ?>
                                    <?php if ($params->get('show_author')) : ?>
                                        <dt class="col-sm-3 fw-semibold">Autore:</dt>
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
                                    
                                    <?php // Data di Creazione ?>
                                    <?php if ($params->get('show_create_date')) : ?>
                                        <dt class="col-sm-3 fw-semibold">Creato:</dt>
                                        <dd class="col-sm-9">
                                            <?php echo HTMLHelper::_('date', $this->item->created, Text::_('DATE_FORMAT_LC3')); ?>
                                        </dd>
                                    <?php endif; ?>
                                    
                                    <?php // Data di Pubblicazione ?>
                                    <?php if ($params->get('show_publish_date')) : ?>
                                        <dt class="col-sm-3 fw-semibold">Pubblicato:</dt>
                                        <dd class="col-sm-9">
                                            <?php echo HTMLHelper::_('date', $this->item->publish_up, Text::_('DATE_FORMAT_LC3')); ?>
                                        </dd>
                                    <?php endif; ?>
                                    
                                    <?php // Data di Modifica ?>
                                    <?php if ($params->get('show_modify_date')) : ?>
                                        <dt class="col-sm-3 fw-semibold">Ultimo aggiornamento:</dt>
                                        <dd class="col-sm-9">
                                            <?php echo HTMLHelper::_('date', $this->item->modified, Text::_('DATE_FORMAT_LC3')); ?>
                                        </dd>
                                    <?php endif; ?>
                                    
                                    <?php // Visualizzazioni ?>
                                    <?php if ($params->get('show_hits')) : ?>
                                        <dt class="col-sm-3 fw-semibold">Visualizzazioni:</dt>
                                        <dd class="col-sm-9">
                                            <?php echo $this->item->hits; ?>
                                        </dd>
                                    <?php endif; ?>
                                </dl>
                            </div>
                        </div>
                    </article>
                <?php endif; ?>
                
                <?php // Content is generated by content plugin event "onContentAfterDisplay" ?>
                <?php echo $this->item->event->afterDisplayContent; ?>
            </section>
            
            <?php // Sidebar destra (solo se ci sono moduli) ?>
            <?php if ($hasRightColumn) : ?>
                <aside class="<?php echo $rightColClass; ?>">
                    <?php echo HTMLHelper::_('content.prepare', '{loadposition colonna-destra}'); ?>
                </aside>
            <?php endif; ?>
        </div>
    </div>
    
    <?php // Rating section ?>
    <div class="bg-primary">
        <div class="container">
            <div class="row d-flex justify-content-center bg-primary">
                <div class="col-12 col-lg-6 p-lg-0 px-3">
                    <?php // Inserire qui il componente di rating se disponibile ?>
                </div>
            </div>
        </div>
    </div>