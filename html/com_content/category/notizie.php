<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * Layout alternativo "Notizie" per categoria.
 * Modello Comuni (Designers Italia) — emette data-element="news-category-link"
 * sulle sottocategorie. Le card degli articoli non portano data-element.
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\Registry\Registry;

$app = Factory::getApplication();

/** @var \Joomla\Component\Content\Site\View\Category\HtmlView $this */
$this->category->text = $this->category->description;
$app->triggerEvent('onContentPrepare', [$this->category->extension . '.categories', &$this->category, &$this->params, 0]);
$this->category->description = $this->category->text;

$results           = $app->triggerEvent('onContentAfterTitle', [$this->category->extension . '.categories', &$this->category, &$this->params, 0]);
$afterDisplayTitle = trim(implode("\n", $results));

$results              = $app->triggerEvent('onContentBeforeDisplay', [$this->category->extension . '.categories', &$this->category, &$this->params, 0]);
$beforeDisplayContent = trim(implode("\n", $results));

$results             = $app->triggerEvent('onContentAfterDisplay', [$this->category->extension . '.categories', &$this->category, &$this->params, 0]);
$afterDisplayContent = trim(implode("\n", $results));

if (!empty($this->items)) {
    $allItems = $this->items;
} else {
    $allItems = array_merge(
        !empty($this->lead_items) ? $this->lead_items : [],
        !empty($this->intro_items) ? $this->intro_items : [],
        !empty($this->link_items) ? $this->link_items : []
    );
}

$showFeatured  = (bool) $this->params->get('show_featured_news', 1);
$featuredCount = max(1, (int) $this->params->get('featured_news_count', 3));
$isFirstPage   = ((int) ($this->pagination->pagesCurrent ?? 1)) <= 1;

// Query articoli in evidenza (featured=1) dalla categoria e tutte le sottocategorie
$featuredItems = [];
$_rawFeatured  = [];
if ($showFeatured && $isFirstPage) {
    try {
        $db          = Factory::getDbo();
        $catId       = (int) $this->category->id;
        $catPathLike = $this->category->path . '/%';
        $now         = Factory::getDate()->toSql();
        $nullDate    = $db->getNullDate();

        $query = $db->getQuery(true)
            ->select([
                $db->quoteName('a.id'),
                $db->quoteName('a.title'),
                $db->quoteName('a.alias'),
                $db->quoteName('a.introtext'),
                $db->quoteName('a.images'),
                $db->quoteName('a.catid'),
                $db->quoteName('a.language'),
                $db->quoteName('a.publish_up'),
                $db->quoteName('a.publish_down'),
                $db->quoteName('a.state'),
                $db->quoteName('a.attribs'),
                $db->quoteName('c.title', 'category_title'),
                $db->quoteName('c.alias', 'category_alias'),
            ])
            ->from($db->quoteName('#__content', 'a'))
            ->join('INNER', $db->quoteName('#__categories', 'c'), $db->quoteName('c.id') . ' = ' . $db->quoteName('a.catid'))
            ->where($db->quoteName('a.featured') . ' = 1')
            ->where($db->quoteName('a.state') . ' = 1')
            ->where(
                '(' .
                $db->quoteName('a.catid') . ' = ' . $catId . ' OR ' .
                $db->quoteName('c.path') . ' LIKE ' . $db->quote($catPathLike) .
                ')'
            )
            ->where(
                '(' .
                $db->quoteName('a.publish_up') . ' IS NULL OR ' .
                $db->quoteName('a.publish_up') . ' <= ' . $db->quote($now) .
                ')'
            )
            ->where(
                '(' .
                $db->quoteName('a.publish_down') . ' IS NULL OR ' .
                $db->quoteName('a.publish_down') . ' = ' . $db->quote($nullDate) . ' OR ' .
                $db->quoteName('a.publish_down') . ' > ' . $db->quote($now) .
                ')'
            )
            ->order($db->quoteName('a.publish_up') . ' DESC');

        $db->setQuery($query, 0, $featuredCount);
        $_rawFeatured = $db->loadObjectList() ?: [];

        foreach ($_rawFeatured as $row) {
            $row->slug   = $row->id . ':' . $row->alias;
            $row->params = new Registry($row->attribs ?? '');
            $row->params->set('access-edit', false);
            $row->params->set('show_intro', 1);
            $featuredItems[] = $row;
        }
    } catch (\Throwable $e) {
        $featuredItems = [];
    }
}
?>

<div class="com-content-category-blog blog blog-notizie">

    <?php if ($this->params->get('show_page_heading')) : ?>
        <div class="page-header mb-4">
            <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
        </div>
    <?php endif; ?>

    <?php // Hero ?>
    <?php if ($this->params->get('show_category_title', 1) || ($this->params->get('show_description', 1) && $this->category->description)) : ?>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <div class="cmp-hero">
                        <section class="it-hero-wrapper bg-white align-items-start">
                            <div class="it-hero-text-wrapper pt-0 ps-0 pb-4 pb-lg-60">
                                <?php if ($this->params->get('show_category_title', 1)) : ?>
                                    <h1 class="text-black hero-title">
                                        <?php echo $this->category->title; ?>
                                    </h1>
                                <?php endif; ?>
                                <?php echo $afterDisplayTitle; ?>

                                <?php if ($this->params->get('show_description', 1) && $this->category->description) : ?>
                                    <div class="hero-text">
                                        <?php echo $beforeDisplayContent; ?>
                                        <?php echo HTMLHelper::_('content.prepare', $this->category->description, '', 'com_content.category'); ?>
                                        <?php echo $afterDisplayContent; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($this->params->get('show_cat_tags', 1) && !empty($this->category->tags->itemTags)) : ?>
                                    <div class="mt-3">
                                        <?php $this->category->tagLayout = new FileLayout('joomla.content.tags'); ?>
                                        <?php echo $this->category->tagLayout->render($this->category->tags->itemTags); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php // Sezione "In evidenza" — solo pagina 1, solo se abilitata e se ci sono articoli featured ?>
    <?php if ($showFeatured && $isFirstPage && !empty($featuredItems)) : ?>
        <div class="container py-5">
            <h2 class="title-xxlarge mb-4">
                <?php echo Text::_('TPL_ACCESSIBILE_NOTIZIE_IN_EVIDENZA'); ?>
            </h2>
            <div class="row g-4">
                <?php foreach ($featuredItems as $item) : ?>
                    <?php
                    $this->item = $item;
                    echo $this->loadTemplate('item');
                    ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php // Sezione "Esplora tutte le novità" — tutti gli articoli + paginazione ?>
    <div class="py-5">
        <div class="container">
            <h2 class="title-xxlarge mb-4">
                <?php echo Text::_('TPL_ACCESSIBILE_NOTIZIE_ESPLORA'); ?>
            </h2>

            <?php if (empty($allItems)) : ?>
                <?php if ($this->params->get('show_no_articles', 1)) : ?>
                    <div class="alert alert-info">
                        <span class="icon-info-circle" aria-hidden="true"></span>
                        <span class="visually-hidden"><?php echo Text::_('INFO'); ?></span>
                        <?php echo Text::_('COM_CONTENT_NO_ARTICLES'); ?>
                    </div>
                <?php endif; ?>
            <?php else : ?>
                <div class="row g-4" id="load-more">
                    <?php foreach ($allItems as $item) : ?>
                        <?php
                        $this->item = $item;
                        echo $this->loadTemplate('item');
                        ?>
                    <?php endforeach; ?>
                </div>

                <?php if (($this->params->def('show_pagination', 1) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
                    <div class="com-content-category-blog__navigation w-100 mt-5">
                        <?php if ($this->params->def('show_pagination_results', 1)) : ?>
                            <p class="com-content-category-blog__counter counter text-end pt-3 pe-2">
                                <?php echo $this->pagination->getPagesCounter(); ?>
                            </p>
                        <?php endif; ?>
                        <div class="com-content-category-blog__pagination">
                            <?php echo $this->pagination->getPagesLinks(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php // Esplora per categoria (sottocategorie) ?>
    <?php if ($this->maxLevel != 0 && !empty($this->children[$this->category->id])) : ?>
        <div class="container py-5 mt-5" id="categoria">
            <h2 class="title-xxlarge mb-4">
                <?php echo Text::_('TPL_ACCESSIBILE_NOTIZIE_ESPLORA_CATEGORIA'); ?>
            </h2>
            <?php echo $this->loadTemplate('children'); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->category->getParams()->get('access-create')) : ?>
        <div class="container mt-4">
            <?php echo HTMLHelper::_('contenticon.create', $this->category, $this->category->params); ?>
        </div>
    <?php endif; ?>

</div>
