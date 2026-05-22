<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles
 *
 * Layout alternativo per Modello Comuni (3 articoli a teaser)
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper as ContentRouteHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

$_parentTpl = \Joomla\CMS\Factory::getApplication()->getTemplate(true)->parent
    ?: \Joomla\CMS\Factory::getApplication()->getTemplate();
require_once JPATH_SITE . '/templates/' . $_parentTpl . '/helpers/TplAccessibileHelper.php';

Factory::getApplication()->getLanguage()->load('tpl_templateaccessibileperjoomla', JPATH_SITE);

if (empty($list)) {
    return;
}

// item_heading: stringa h1–h6 o "div" (XML default: h4)
$itemHeading       = $params->get('item_heading', 'h4');
$maxChars          = (int)  $params->get('introtext_limit', 160);
$showAuthor        = (bool) $params->get('show_author', 0);
$showCategory      = (bool) $params->get('show_category', 0);
$showCategoryLink  = (bool) $params->get('show_category_link', 0);
$showDate          = (bool) $params->get('show_date', 0);
$showDateField     = $params->get('show_date_field', 'publish_up');
$showDateFormat    = $params->get('show_date_format', '');
$showReadmore      = (bool) $params->get('show_readmore', 0);
$showReadmoreTitle = (bool) $params->get('show_readmore_title', 1);
$readmoreLimit     = (int)  $params->get('readmore_limit', 15);
$_allowedTags      = ['h1','h2','h3','h4','h5','h6','p','div'];
$headerTag         = in_array($params->get('header_tag', 'h3'), $_allowedTags, true)
                         ? $params->get('header_tag', 'h3') : 'h3';

$truncate = static function (string $text, int $limit): string {
    if ($limit <= 0 || mb_strlen($text) <= $limit) return $text;
    return rtrim(mb_substr($text, 0, $limit)) . '…';
};

$cleanText = static function (?string $html): string {
    if (!$html) return '';
    $text = trim(strip_tags($html));
    return preg_replace('/\s+/u', ' ', $text) ?: '';
};

?>
<?php if (empty($module->showtitle)) :
    $sectionHeading = !empty($module->title) ? $module->title : Text::_('TPL_ACCESSIBILE_FEATURED_ARTICLES');
?>
    <<?= $headerTag ?> class="visually-hidden"><?= htmlspecialchars($sectionHeading, ENT_QUOTES, 'UTF-8') ?></<?= $headerTag ?>>
<?php endif; ?>
<div class="cmp-evidenza-title">
<div class="row g-4">
    <?php 
    // Mostriamo fino a un massimo di 3 articoli
    $articlesToDisplay = array_slice($list, 0, 3);
    
    foreach ($articlesToDisplay as $index => $item) : 
        
        $images = json_decode($item->images ?? '{}');
        $imgUrl = $images->image_intro ?? '';
        $imgAlt = $images->image_intro_alt ?? $item->title;
        
        $intro = $cleanText($item->introtext ?? '');
        $introTruncated = $truncate($intro, $maxChars);

        // Routing sicuro
        $link = $item->link ?? Route::_(ContentRouteHelper::getArticleRoute($item->slug ?? $item->id, $item->catid, $item->language));
        $categoryLink = $item->catid ? Route::_(ContentRouteHelper::getCategoryRoute($item->catid, $item->language)) : '';

        $displayDate = '';
        $_rawDate = $item->{$showDateField} ?? null;
        if (!empty($_rawDate)) {
            $displayDate = $showDateFormat !== ''
                ? Factory::getDate($_rawDate)->format($showDateFormat)
                : strtoupper(Factory::getDate($_rawDate)->format('d M y'));
        }
    ?>
    <div class="col-md-6 col-xl-4">
        <div class="card-wrapper rounded shadow-sm">
            <div class="card no-after rounded">
                <?php if ($imgUrl) : ?>
                    <div class="img-responsive-wrapper">
                        <div class="img-responsive img-responsive-panoramic">
                            <figure class="img-wrapper">
                                <a href="<?= $link; ?>">
                                    <img src="<?= htmlspecialchars($imgUrl, ENT_QUOTES, 'UTF-8'); ?>"
                                         alt="<?= htmlspecialchars($imgAlt, ENT_QUOTES, 'UTF-8'); ?>"
                                         title="<?= htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?>">
                                </a>
                            </figure>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="card-body">
                    <div class="category-top">
                        <?php if ($showCategory && $item->category_title) : ?>
                            <?php if ($showCategoryLink && $categoryLink) : ?>
                                <a class="category text-decoration-none" href="<?= $categoryLink; ?>">
                                    <?= strtoupper(htmlspecialchars($item->category_title, ENT_QUOTES, 'UTF-8')); ?>
                                </a>
                            <?php else : ?>
                                <span class="category">
                                    <?= strtoupper(htmlspecialchars($item->category_title, ENT_QUOTES, 'UTF-8')); ?>
                                </span>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($showDate && $displayDate) : ?>
                            <span class="data"><?= $displayDate; ?></span>
                        <?php endif; ?>
                        <?php if ($showAuthor) :
                            $authorDisplay = ($item->created_by_alias ?? '') ?: ($item->author ?? '');
                            if ($authorDisplay !== '') : ?>
                                <span class="author">
                                    <span class="visually-hidden"><?= Text::_('JAUTHOR'); ?>: </span>
                                    <?= htmlspecialchars($authorDisplay, ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                        <?php   endif;
                        endif; ?>
                    </div>

                    <a class="text-decoration-none" href="<?= $link; ?>">
                        <<?= $itemHeading ?> class="card-title">
                            <?= htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?>
                        </<?= $itemHeading ?>>
                    </a>

                    <?php if ($introTruncated !== '') : ?>
                        <p class="card-text text-secondary">
                            <?= htmlspecialchars($introTruncated, ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($showReadmore) :
                        $srText = $showReadmoreTitle && !empty($item->title)
                            ? Text::sprintf('JGLOBAL_READ_MORE_TITLE', mb_strlen($item->title) > $readmoreLimit
                                ? rtrim(mb_substr($item->title, 0, $readmoreLimit)) . '…'
                                : $item->title)
                            : Text::_('JGLOBAL_READ_MORE');
                    ?>
                        <a href="<?= $link; ?>" class="read-more mt-2 d-inline-flex align-items-center"
                           aria-label="<?= htmlspecialchars($srText, ENT_QUOTES, 'UTF-8'); ?>">
                            <span aria-hidden="true"><?= Text::_('JGLOBAL_READ_MORE'); ?></span>
                            <svg class="icon icon-sm ms-1" aria-hidden="true">
                                <use href="<?= TplAccessibileHelper::spriteUrl('it-arrow-right') ?>"></use>
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
</div>