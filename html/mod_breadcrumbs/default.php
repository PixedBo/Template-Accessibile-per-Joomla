<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_breadcrumbs
 *
 * Override breadcrumb per Modello Comuni (Designers Italia).
 * Emette:
 *  - data-element="breadcrumb" sulla ol (App Valutazione Modelli)
 *  - microdata schema.org BreadcrumbList + ListItem
 *  - classi Bootstrap Italia: cmp-breadcrumbs, breadcrumb-container, breadcrumb, separator
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/** @var array $list */
/** @var \Joomla\Registry\Registry $params */

$count = count($list);
if ($count === 0) {
    return;
}
?>
<div class="cmp-breadcrumbs" role="navigation">
    <nav class="breadcrumb-container" aria-label="<?php echo htmlspecialchars(Text::_('TPL_ACCESSIBILE_BREADCRUMB_NAV'), ENT_QUOTES, 'UTF-8'); ?>">
        <ol class="breadcrumb p-0" data-element="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
            <meta name="numberOfItems" content="<?php echo $count; ?>">
            <meta name="itemListOrder" content="Ascending">
            <?php foreach ($list as $key => $item) :
                $isFirst = ($key === 0);
                $isLast  = ($key === $count - 1);
                $classes = 'breadcrumb-item';
                if ($isFirst) {
                    $classes .= ' trail-begin';
                }
                if ($isLast) {
                    $classes .= ' active';
                }
                ?>
                <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="<?php echo $classes; ?>">
                    <?php if (!$isFirst) : ?>
                        <span class="separator" aria-hidden="true">/</span>
                    <?php endif; ?>
                    <?php if ($item->link !== '' && !$isLast) : ?>
                        <a href="<?php echo $item->link; ?>"<?php echo $isFirst ? ' rel="home"' : ''; ?> itemprop="item">
                            <span itemprop="name"><?php echo $item->name; ?></span>
                        </a>
                    <?php else : ?>
                        <span itemprop="item"<?php echo $isLast ? ' aria-current="page"' : ''; ?>>
                            <span itemprop="name"><?php echo $item->name; ?></span>
                        </span>
                    <?php endif; ?>
                    <meta itemprop="position" content="<?php echo $key + 1; ?>">
                </li>
            <?php endforeach; ?>
        </ol>
    </nav>
</div>
