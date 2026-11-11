<?php
/**
 * Sub-template: un risultato, come card con la categoria sopra il titolo.
 *
 * Vale per tutti i tipi di contenuto: la vista cerca default_<tipo>.php
 * (article, contact, category, tag, …) e, se manca, usa questo file.
 *
 * Sopra il titolo va la categoria del contenuto; se non ne ha una (categorie, tag)
 * va il tipo di contenuto.
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Finder\Administrator\Indexer\Helper;
use Joomla\String\StringHelper;

/** @var \Joomla\Component\Finder\Site\View\Search\HtmlView $this */

$levels = $this->getCurrentUser()->getAuthorisedViewLevels();

// getTaxonomy('Category') restituirebbe tutti i rami se il ramo manca: si legge l'array intero
$taxonomy = $this->result->getTaxonomy();
$label    = '';

foreach (['Category', 'Type'] as $branch) {
    foreach ((array) ($taxonomy[$branch] ?? []) as $node) {
        if ((int) $node->state === 1 && in_array((int) $node->access, $levels)) {
            $label = $branch === 'Type' ? TplAccessibileSearchHelper::typeLabel($node->title, false) : $node->title;
            break 2;
        }
    }
}

// Estratto attorno al termine cercato, come nel layout del core
$description = '';

if ($this->params->get('show_description', 1)) {
    $termLength = StringHelper::strlen($this->query->input);
    $descLength = (int) $this->params->get('description_length', 255);
    $padLength  = $termLength < $descLength ? (int) floor(($descLength - $termLength) / 2) : 0;

    $full = (string) $this->result->description;

    if (!empty($this->result->summary) && !empty($this->result->body)) {
        $full = Helper::parse($this->result->summary . $this->result->body);
    }

    $pos   = $termLength ? StringHelper::strpos(StringHelper::strtolower($full), StringHelper::strtolower($this->query->input)) : false;
    $start = ($pos && $pos > $padLength) ? $pos - $padLength : 0;
    $space = StringHelper::strpos($full, ' ', $start > 0 ? $start - 1 : 0);
    $start = ($space && $space < $pos) ? $space + 1 : $start;

    $description = HTMLHelper::_('string.truncate', StringHelper::substr($full, $start), $descLength, true, false);
}

$showDate = $this->result->start_date && $this->params->get('show_date', 1);
?>
<li class="search-result">
    <div class="card shadow-sm rounded">
        <div class="card-body">
            <?php if ($label !== '') : ?>
                <p class="search-result-category"><?php echo $this->escape($label); ?></p>
            <?php endif; ?>
            <h3 class="search-result-title">
                <?php if ($this->result->route) : ?>
                    <a href="<?php echo Route::_($this->result->route); ?>"><?php echo $this->escape($this->result->title); ?></a>
                <?php else : ?>
                    <?php echo $this->escape($this->result->title); ?>
                <?php endif; ?>
            </h3>
            <?php if ($showDate) : ?>
                <p class="search-result-date">
                    <time datetime="<?php echo HTMLHelper::_('date', $this->result->start_date, 'c'); ?>"><?php echo HTMLHelper::_('date', $this->result->start_date, Text::_('DATE_FORMAT_LC3')); ?></time>
                </p>
            <?php endif; ?>
            <?php if ($description !== '') : ?>
                <p class="search-result-description"><?php echo $this->escape($description); ?></p>
            <?php endif; ?>
        </div>
    </div>
</li>
