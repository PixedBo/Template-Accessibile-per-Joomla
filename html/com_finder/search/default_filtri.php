<?php
/**
 * Sub-template: colonna dei filtri della pagina risultati.
 *
 * Tutte le voci sono caselle di spunta scritte allo stesso modo, in elenchi annidati.
 * Le caselle appartengono al form #search-page-form (attributo form="") e inviano
 * t[] = id del nodo di tassonomia. Nessun invio automatico al cambio di una casella:
 * i filtri si applicano con il pulsante (WCAG 3.2.2).
 *
 * Voce con sottovoci (categoria con sottocategorie):
 *   - la sua casella seleziona o deseleziona anche tutte le sottovoci (js/search-filters.js);
 *     se solo alcune sottovoci sono selezionate la casella è nello stato "misto";
 *   - un pulsante accanto mostra o nasconde le sottovoci (aria-expanded / aria-controls);
 *   - le sottovoci sono un gruppo (role="group") che prende il nome dalla voce.
 *
 * Una categoria senza contenuti propri (`selectable` falso) non ha un valore da inviare:
 * la sua casella serve solo a selezionare le sottovoci, quindi senza JavaScript non
 * avrebbe effetto. Per questo nasce nascosta, con al suo posto il nome come testo;
 * lo script scambia i due. Senza JavaScript le sottovoci restano tutte visibili.
 *
 * Sotto lg la colonna è richiudibile (collapse di Bootstrap Italia); da lg in su è
 * sempre aperta (.d-lg-block).
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/** @var \Joomla\Component\Finder\Site\View\Search\HtmlView $this */

$this->getDocument()->getWebAssetManager()->useScript('template.search-filters');

// Dall'elenco piatto (in ordine d'albero, con la profondità) all'albero
$toTree = static function (array $nodes, int &$i, int $depth) use (&$toTree): array {
    $items = [];

    while ($i < count($nodes) && $nodes[$i]['depth'] >= $depth) {
        $node = $nodes[$i++];

        $node['children'] = ($i < count($nodes) && $nodes[$i]['depth'] > $node['depth'])
            ? $toTree($nodes, $i, $node['depth'] + 1)
            : [];

        $items[] = $node;
    }

    return $items;
};

$sprite = TplAccessibileHelper::spriteUrl('it-expand');

// Ordine alfabetico a ogni livello (l'indice restituisce le voci in ordine di creazione).
// Con l'estensione intl l'ordine segue la lingua del sito (lettere accentate comprese).
$collator = class_exists('Collator') ? new Collator(str_replace('-', '_', $this->getLanguage()->getTag())) : null;
$byTitle  = static function (array $a, array $b) use ($collator): int {
    return $collator
        ? (int) $collator->compare($a['title'], $b['title'])
        : strnatcasecmp($a['title'], $b['title']);
};

$renderList = function (array $items) use (&$renderList, $sprite, $byTitle): string {
    usort($items, $byTitle);

    $html = '<ul class="search-filters-list" role="list">';

    foreach ($items as $node) {
        $id          = (int) $node['id'];
        $title       = $this->escape($node['title']);
        $hasChildren = !empty($node['children']);
        $inputId     = 'search-filter-' . $id;
        $labelId     = 'search-filter-label-' . $id;
        $groupId     = 'search-filter-group-' . $id;

        $html .= '<li class="search-filters-item">';
        $html .= '<div class="search-filters-row">';

        if ($node['selectable']) {
            $html .= '<div class="form-check">'
                . '<input type="checkbox" form="search-page-form" name="t[]" value="' . $id . '" id="' . $inputId . '"'
                . ($node['checked'] ? ' checked' : '') . ($hasChildren ? ' data-filter-parent' : '') . '>'
                . '<label for="' . $inputId . '" id="' . $labelId . '">' . $title . '</label>'
                . '</div>';
        } else {
            // Senza JavaScript: solo il nome. Con JavaScript: casella "seleziona tutte le sottovoci" (non inviata).
            $html .= '<p class="search-filters-heading" id="' . $labelId . '-text" data-filter-nojs>' . $title . '</p>'
                . '<div class="form-check" hidden data-filter-js>'
                . '<input type="checkbox" id="' . $inputId . '" data-filter-parent>'
                . '<label for="' . $inputId . '" id="' . $labelId . '">' . $title . '</label>'
                . '</div>';
        }

        if ($hasChildren) {
            $html .= '<button type="button" class="search-filters-toggle" hidden data-filter-toggle aria-expanded="true" aria-controls="' . $groupId . '">'
                . '<span class="visually-hidden">' . Text::sprintf('TPL_ACCESSIBILE_SEARCH_FILTERS_SUBITEMS', $title) . '</span>'
                . '<svg class="icon icon-sm" aria-hidden="true"><use href="' . $sprite . '"></use></svg>'
                . '</button>';
        }

        $html .= '</div>';

        if ($hasChildren) {
            // Il nome del gruppo: l'etichetta della casella, o il testo finché la casella è nascosta (lo script lo aggiorna)
            $html .= '<div class="search-filters-children" id="' . $groupId . '" role="group" aria-labelledby="'
                . ($node['selectable'] ? $labelId : $labelId . '-text') . '">'
                . $renderList($node['children'])
                . '</div>';
        }

        $html .= '</li>';
    }

    return $html . '</ul>';
};
?>
<aside class="search-filters" aria-labelledby="search-filters-title">
    <h2 class="visually-hidden" id="search-filters-title"><?php echo Text::_('TPL_ACCESSIBILE_SEARCH_FILTERS'); ?></h2>

    <button class="btn btn-outline-primary w-100 d-lg-none mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#search-filters-body" aria-expanded="<?php echo $this->hasFilters ? 'true' : 'false'; ?>" aria-controls="search-filters-body">
        <?php echo Text::_('TPL_ACCESSIBILE_SEARCH_FILTERS_TOGGLE'); ?>
    </button>

    <div class="collapse d-lg-block<?php echo $this->hasFilters ? ' show' : ''; ?>" id="search-filters-body">
        <?php foreach ($this->filterGroups as $group) : ?>
            <?php $i = 0; ?>
            <fieldset class="search-filters-group">
                <legend class="search-filters-legend"><?php echo $this->escape($group['label']); ?></legend>
                <?php echo $renderList($toTree($group['nodes'], $i, 0)); ?>
            </fieldset>
        <?php endforeach; ?>

        <div class="search-filters-actions">
            <button type="submit" form="search-page-form" class="btn btn-primary"><?php echo Text::_('TPL_ACCESSIBILE_SEARCH_FILTERS_APPLY'); ?></button>
            <?php if ($this->hasFilters) : ?>
                <a href="<?php echo $this->escape(TplAccessibileSearchHelper::urlWith(['t' => null])); ?>"><?php echo Text::_('TPL_ACCESSIBILE_SEARCH_FILTERS_CLEAR'); ?></a>
            <?php endif; ?>
        </div>
    </div>
</aside>
