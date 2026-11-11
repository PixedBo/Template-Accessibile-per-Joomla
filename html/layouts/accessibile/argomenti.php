<?php
/**
 * Partial condiviso: blocco "Argomenti" (tag) dell'intestazione di pagina, reso come chip
 * di Bootstrap Italia.
 *
 * Uso:
 *   echo LayoutHelper::render('accessibile.argomenti', [
 *       'tags'        => $item->tags->itemTags,  // obbligatorio
 *       'dataElement' => 'service-topic',        // facoltativo: attributo data-element sui link
 *       'idSuffix'    => 'page',                 // facoltativo: per ID univoci
 *   ]);
 *
 * Restituisce una stringa vuota se l'utente non può vedere alcun tag.
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Tags\Site\Helper\RouteHelper;

/** @var array $displayData */

$tags        = $displayData['tags'] ?? [];
$dataElement = preg_replace('/[^a-z0-9-]/i', '', (string) ($displayData['dataElement'] ?? ''));
$suffix      = preg_replace('/[^a-z0-9_-]/i', '', (string) ($displayData['idSuffix'] ?? 'page')) ?: 'page';

if (!is_iterable($tags)) {
    return;
}

// Stesso filtro di accesso del layout core joomla.content.tags
$authorised = Factory::getApplication()->getIdentity()->getAuthorisedViewLevels();
$items      = [];

foreach ($tags as $tag) {
    if (!is_object($tag) || empty($tag->title)) {
        continue;
    }

    if (isset($tag->access) && !in_array($tag->access, $authorised)) {
        continue;
    }

    // itemTags espone tag_id; altri elenchi di tag usano id
    $tagId = (int) ($tag->tag_id ?? $tag->id ?? 0);

    if (!$tagId) {
        continue;
    }

    $items[] = [
        'title' => (string) $tag->title,
        'href'  => Route::_(RouteHelper::getComponentTagRoute($tagId . ':' . ($tag->alias ?? ''), $tag->language ?? null)),
    ];
}

if (!$items) {
    return;
}

$labelId = 'argomenti-label-' . $suffix;
?>
<div class="mt-4 mb-4">
    <span class="subtitle-small d-block mb-2 fw-semibold text-muted" id="<?php echo $labelId; ?>"><?php echo Text::_('TPL_ACCESSIBILE_TAGS'); ?></span>
    <ul class="d-flex flex-wrap gap-1 list-unstyled ps-0 mb-0" aria-labelledby="<?php echo $labelId; ?>">
        <?php foreach ($items as $item) : ?>
            <li>
                <a class="chip chip-simple chip-primary" href="<?php echo $item['href']; ?>"<?php echo $dataElement !== '' ? ' data-element="' . $dataElement . '"' : ''; ?>>
                    <span class="chip-label"><?php echo htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
