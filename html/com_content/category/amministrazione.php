<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * Layout alternativo "Amministrazione" per categoria.
 * Modello Comuni (Designers Italia) — emette data-element="management-category-link"
 * sulle sottocategorie. Articoli in evidenza su sfondo grigio, poi "Esplora l'amministrazione".
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\Registry\Registry;

$app = Factory::getApplication();
$app->getLanguage()->load('tpl_templateaccessibileperjoomla', JPATH_SITE);

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

$showFeatured  = (bool) $this->params->get('show_featured_amm', 1);
$featuredCount = max(1, (int) $this->params->get('featured_amm_count', 3));
$isFirstPage   = ((int) ($this->pagination->pagesCurrent ?? 1)) <= 1;

// Query articoli in evidenza (featured=1) dalla categoria e tutte le sottocategorie
$featuredItems = [];
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
        $rawFeatured = $db->loadObjectList() ?: [];

        foreach ($rawFeatured as $row) {
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

<div class="com-content-category-blog blog blog-amministrazione">

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

    <?php // Sezione "In evidenza" su sfondo grigio — solo pagina 1, solo se ci sono articoli featured ?>
    <?php if ($showFeatured && $isFirstPage && !empty($featuredItems)) : ?>
        <div class="amministrazione-evidenza py-5">
            <div class="container">
                <h2 class="title-xxlarge mb-4">
                    <?php echo Text::_('TPL_ACCESSIBILE_AMMINISTRAZIONE_IN_EVIDENZA'); ?>
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
        </div>
    <?php endif; ?>

    <?php // Sezione "Esplora l'amministrazione" — sottocategorie di secondo livello ?>
    <?php if ($this->maxLevel != 0 && !empty($this->children[$this->category->id])) : ?>
        <div class="container py-5 mt-3" id="esplora-amministrazione">
            <h2 class="title-xxlarge mb-4">
                <?php echo Text::_('TPL_ACCESSIBILE_AMMINISTRAZIONE_ESPLORA'); ?>
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
