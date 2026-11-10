<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_dpcalendar
 *
 * Scheda Evento per DPCalendar (tipologia "evento" del Modello Comuni).
 *
 * Le sezioni del corpo sono costruite una sola volta in $sections: indice di pagina e
 * contenuto iterano lo stesso elenco, quindi una voce d'indice esiste se e solo se esiste
 * la sua sezione.
 *
 * Le sezioni A chi è rivolto, Galleria, Video, Costi, Allegati, Contatti, Patrocinio e
 * Sponsor leggono campi personalizzati di DPCalendar scelti nei parametri del template
 * (fieldset "evento"). I campi non mappati restano sezioni a sé, come prima della 1.3.0.
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\MediaHelper;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

/** @var \DigitalPeak\Component\DPCalendar\Site\View\Event\HtmlView $this */

$app = Factory::getApplication();
$app->getLanguage()->load('tpl_templateaccessibileperjoomla', JPATH_SITE);

$_parentTpl = $app->getTemplate(true)->parent ?: $app->getTemplate();
require_once JPATH_SITE . '/templates/' . $_parentTpl . '/helpers/TplAccessibileHelper.php';
require_once JPATH_SITE . '/templates/' . $_parentTpl . '/helpers/TplAccessibileFieldsHelper.php';

$document  = $app->getDocument();
$params    = $this->params;
$event     = $this->event;
$tplParams = $app->getTemplate(true)->params;

$esc    = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$sprite = static fn (string $icon): string => TplAccessibileHelper::spriteUrl($icon);

// Embed video (YouTube / Vimeo). Restituisce stringa vuota se l'URL non è riconosciuto.
$videoTitle    = $esc(Text::sprintf('TPL_ACCESSIBILE_EVENT_VIDEO_TITLE', $event->title));
$getVideoEmbed = static function ($url) use ($videoTitle): string {
    if (!is_string($url) || trim($url) === '') {
        return '';
    }

    // YouTube
    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
        return '<div class="ratio ratio-16x9 mb-3"><iframe src="https://www.youtube.com/embed/' . htmlspecialchars($match[1], ENT_QUOTES, 'UTF-8') . '" title="' . $videoTitle . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe></div>';
    }

    // Vimeo
    if (preg_match('%vimeo\.com/(?:video/)?([0-9]+)%i', $url, $match)) {
        return '<div class="ratio ratio-16x9 mb-3"><iframe src="https://player.vimeo.com/video/' . $match[1] . '" title="' . $videoTitle . '" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe></div>';
    }

    return '';
};

// Risorse DPCalendar originali
if ($params->get('event_show_map', '1')
    && $event->locations
    && $params->get('event_show_location', '2') && $params->get('map_provider', 'openstreetmap') != 'none') {

    // Aumentiamo lo zoom di 8 livelli rispetto al parametro impostato
    $currentZoom = (int) $params->get('event_map_zoom', 4);
    $params->set('event_map_zoom', $currentZoom + 8);

    $this->layoutHelper->renderLayout('block.map', $this->displayData);
}

$this->dpdocument->loadStyleFile('dpcalendar/views/event/default.css');
$this->dpdocument->loadScriptFile('views/event/default.js');
$this->dpdocument->addStyle($params->get('event_custom_css', ''));

// Sotto-template DPCalendar (ognuno applica i propri parametri di visibilità)
$imageContent       = $this->loadTemplate('image_full');
$ctaContent         = $this->loadTemplate('cta');
$bookingFormContent = $this->loadTemplate('booking_form');
$bookingsContent    = $this->loadTemplate('bookings');
$seriesContent      = $this->loadTemplate('series');
$scheduleContent    = $this->loadTemplate('schedule');
$locationsContent   = $this->loadTemplate('locations');
$ticketsContent     = $this->loadTemplate('tickets');
$tagsContent        = $this->loadTemplate('tags');

// ── Date ────────────────────────────────────────────────────────────────────
$isAllDay = (bool) $event->all_day;
$startDate = Factory::getDate($event->start_date);
$endDate   = Factory::getDate($event->end_date);

if (!$isAllDay) {
    // Gli eventi "tutto il giorno" sono date pure: nessuna conversione di fuso
    $tz = new \DateTimeZone($app->get('offset', 'UTC'));
    $startDate->setTimezone($tz);
    $endDate->setTimezone($tz);
}

$isSameDay    = $startDate->format('Y-m-d', true) === $endDate->format('Y-m-d', true);
$showYear     = $startDate->format('Y', true) !== $endDate->format('Y', true);
$dateFormat   = Text::_('DATE_FORMAT_LC3');
$startDateStr = $startDate->format($dateFormat, true, true);
$endDateStr   = $endDate->format($dateFormat, true, true);
$startTime    = $startDate->format('H:i', true);
$endTime      = $endDate->format('H:i', true);

// ── URL "Aggiungi al calendario" (Google / file .ics) ───────────────────────
$googleUrl = '';
$icalUrl   = '';

try {
    $gStart = $this->dateHelper->getDate($event->start_date, $event->all_day);
    $gEnd   = $this->dateHelper->getDate($event->end_date, $event->all_day);
    $gFmt   = $event->all_day ? 'Ymd' : 'Ymd\THis';

    if ($event->all_day) {
        $gEnd->modify('+1 day');
    }

    $gLocationParts = [];

    if (!empty($event->locations)) {
        foreach ($event->locations as $loc) {
            $gLocationParts = array_filter([
                $loc->title ?? '',
                trim(($loc->street ?? '') . ' ' . ($loc->number ?? '')),
                $loc->zip ?? '',
                $loc->city ?? '',
                $loc->province ?? '',
            ]);
            break; // primo luogo
        }
    }

    $gDetails = HTMLHelper::_('string.truncate', strip_tags((string) $event->description), 200);

    $googleUrl  = 'https://www.google.com/calendar/render?action=TEMPLATE&text=' . urlencode((string) $event->title);
    $googleUrl .= '&dates=' . $gStart->format($gFmt, true) . '%2F' . $gEnd->format($gFmt, true);
    $googleUrl .= '&location=' . urlencode(implode(', ', $gLocationParts));
    $googleUrl .= '&details=' . urlencode((string) $gDetails);
    $googleUrl .= '&sf=true&output=xml';

    // Il router può restituire l'URL già con &amp;: lo si riporta a "&" e si fa l'escape in output
    $icalUrl = str_replace('&amp;', '&', (string) $this->router->route(
        'index.php?option=com_dpcalendar&view=event&format=raw&id=' . $event->id . '&calid=' . $event->catid
    ));
} catch (\Throwable $e) {
    $googleUrl = '';
    $icalUrl   = '';
}

$showCalendarExport = $params->get('event_show_copy', '1') && ($googleUrl || $icalUrl);

// ── Collegamento esterno, organizzatori, autore ─────────────────────────────
// L'URL dell'evento può arrivare da invii dal frontend o da calendari esterni: solo schemi ammessi
$eventUrl   = TplAccessibileFieldsHelper::safeLink($event->url ?? '')['url'];
$showUrl    = $eventUrl !== '' && $params->get('event_show_url', '1');
$showHosts  = !empty($event->hosts) && $params->get('event_show_hosts', '1');
$showAuthor = !empty($this->authorName) && $params->get('event_show_author', '1');

$externalLinkLabel = Text::_('TPL_ACCESSIBILE_EVENT_LINK');
$externalLinkAria  = $externalLinkLabel . ' ' . Text::_('TPL_ACCESSIBILE_NEW_WINDOW');

// ── Categoria / calendario (non incluso nella query del model) ──────────────
$categoryTitle = '';

try {
    $cat = \Joomla\CMS\Categories\Categories::getInstance('dpcalendar');

    if ($cat) {
        $catItem       = $cat->get($event->catid);
        $categoryTitle = $catItem ? (string) $catItem->title : '';
    }
} catch (\Throwable $e) {
}

// ── Argomenti (tag) ─────────────────────────────────────────────────────────
// $tagsContent (sotto-template DPCalendar) resta il gate di visibilità e l'ultimo fallback.
$tagsHtml = '';

if ($tagsContent) {
    $itemTags = $event->tags->itemTags ?? null;

    if (empty($itemTags)) {
        try {
            $itemTags = (new TagsHelper())->getItemTags('com_dpcalendar.event', (int) $event->id);
        } catch (\Throwable $e) {
            $itemTags = null;
        }
    }

    if (!empty($itemTags)) {
        $tagsHtml = LayoutHelper::render('accessibile.argomenti', ['tags' => $itemTags, 'idSuffix' => 'evento']);
    }

    if (trim($tagsHtml) === '') {
        $tagsHtml = '<div class="mt-4 mb-4">'
            . '<span class="subtitle-small mb-2 d-block fw-semibold text-muted">' . Text::_('TPL_ACCESSIBILE_TAGS') . '</span>'
            . '<div class="comuni-tags">' . $tagsContent . '</div></div>';
    }
}

// ── Immagine principale ─────────────────────────────────────────────────────
$imageUrl = $imageAlt = $imageCaption = '';

if ($imageContent) {
    $images = $event->images ?? null;
    $images = is_string($images) ? json_decode($images) : $images;

    if (is_object($images) && !empty($images->image_full)) {
        $imageUrl = trim((string) MediaHelper::getCleanMediaFieldValue((string) $images->image_full));

        if ($imageUrl !== '' && !preg_match('#^(?:https?:)?//#i', $imageUrl) && $imageUrl[0] !== '/') {
            $imageUrl = Uri::root(true) . '/' . $imageUrl;
        }

        $imageAlt     = (string) ($images->image_full_alt ?? '');
        $imageCaption = (string) ($images->image_full_caption ?? '');
    }
}

// ── Campi personalizzati mappati ────────────────────────────────────────────
$fieldIndex = TplAccessibileFieldsHelper::index($event->jcfields ?? []);
$usedFields = [];

$cf = static function (string $param) use ($fieldIndex, $tplParams, &$usedFields): ?object {
    $field = TplAccessibileFieldsHelper::mapped($fieldIndex, $tplParams, $param);

    if ($field) {
        $usedFields[(int) $field->id] = true;
    }

    return $field;
};
$key = static fn (string $param): int => (int) $tplParams->get($param, 0);

// Immagini di un campo: Subform con un sotto-campo Media per riga, oppure singolo campo Media
$fieldImages = static function (?object $field): array {
    $images = [];

    if ($field === null) {
        return $images;
    }

    foreach (TplAccessibileFieldsHelper::rows($field) as $row) {
        foreach ($row as $cell) {
            $raw = $cell->rawvalue ?? '';
            $raw = is_string($raw) ? json_decode($raw, true) : (is_object($raw) ? (array) $raw : $raw);

            if (is_array($raw) && !empty($raw['imagefile'])) {
                $link = TplAccessibileFieldsHelper::safeLink($raw);

                if ($link['url'] !== '') {
                    $images[] = $link;
                }

                break;
            }
        }
    }

    return $images;
};

$renderGallery = static function (array $images) use ($esc): string {
    if (!$images) {
        return '';
    }

    $html = '<div class="it-carousel-wrapper it-carousel-landscape-abstract-three-cols splide" data-bs-carousel-splide>'
        . '<div class="splide__track"><ul class="splide__list it-carousel-all">';

    foreach ($images as $image) {
        $html .= '<li class="splide__slide"><div class="it-single-slide-wrapper"><figure>'
            . '<img src="' . $esc($image['url']) . '" alt="' . $esc($image['text']) . '" class="img-fluid" loading="lazy">'
            . ($image['text'] !== '' ? '<figcaption class="figure-caption mt-2">' . $esc($image['text']) . '</figcaption>' : '')
            . '</figure></div></li>';
    }

    return $html . '</ul></div></div>';
};

// Elenco di link (nome + URL) da un Subform: usato da Allegati, Patrocinato da, Sponsor
$fieldLinks = static function (?object $field) use ($key): array {
    $links = [];

    if ($field === null) {
        return $links;
    }

    $kName = $key('cf_evento_link_key_nome');
    $kUrl  = $key('cf_evento_link_key_url');
    $rows  = TplAccessibileFieldsHelper::rows($field);

    foreach ($rows as $row) {
        $name    = TplAccessibileFieldsHelper::cellText($row, $kName, 0);
        $urlCell = TplAccessibileFieldsHelper::cell($row, $kUrl, 1);
        $link    = TplAccessibileFieldsHelper::safeLink($urlCell->rawvalue ?? '');
        $name    = $name !== '' ? $name : $link['text'];

        if ($name === '' && $link['url'] === '') {
            continue;
        }

        $links[] = ['name' => $name !== '' ? $name : basename((string) parse_url($link['url'], PHP_URL_PATH)), 'url' => $link['url']];
    }

    // Campo singolo (URL o Media "Documento") al posto del Subform
    if (!$rows) {
        $link = TplAccessibileFieldsHelper::safeLink($field->rawvalue ?? '');

        if ($link['url'] !== '') {
            $links[] = [
                'name' => $link['text'] !== '' ? $link['text'] : basename((string) parse_url($link['url'], PHP_URL_PATH)),
                'url'  => $link['url'],
            ];
        }
    }

    return $links;
};

$renderLinkList = static function (array $links) use ($esc): string {
    if (!$links) {
        return '';
    }

    $html = '<div class="link-list-wrapper mb-3"><ul class="link-list">';

    foreach ($links as $link) {
        $html .= $link['url'] !== ''
            ? '<li><a class="list-item px-0" href="' . $esc($link['url']) . '"><span>' . $esc($link['name']) . '</span></a></li>'
            : '<li><span class="list-item px-0 d-block">' . $esc($link['name']) . '</span></li>';
    }

    return $html . '</ul></div>';
};

// ── Sezioni del corpo ───────────────────────────────────────────────────────
$sections   = [];
$addSection = static function (string $id, string $labelHtml, string $html, string $class = '') use (&$sections): void {
    if (trim($html) !== '') {
        $sections[] = ['id' => $id, 'label' => $labelHtml, 'html' => $html, 'class' => $class];
    }
};
?>
<?php // ═══ 1. Cos'è (id "descrizione" invariato per i link già pubblicati) ═══ ?>
<?php ob_start(); ?>
    <?php if (!empty($event->description)) : ?>
        <div class="richtext-wrapper lora">
            <?php echo HTMLHelper::_('content.prepare', $event->description); ?>
        </div>
    <?php endif; ?>

    <?php $galleryHtml = $renderGallery($fieldImages($cf('cf_evento_galleria'))); ?>
    <?php if ($galleryHtml !== '') : ?>
        <h3 class="h4 mt-4 mb-3"><?php echo Text::_('TPL_ACCESSIBILE_EVENT_GALLERY'); ?></h3>
        <?php echo $galleryHtml; ?>
    <?php endif; ?>

    <?php
    $videoField = $cf('cf_evento_video');
    $videoRaw   = $videoField ? ($videoField->rawvalue ?? '') : '';
    $videoRaw   = is_string($videoRaw) ? trim($videoRaw) : '';
    $videoHtml  = $getVideoEmbed($videoRaw);

    if ($videoHtml === '' && $videoRaw !== '') {
        // URL non riconosciuto come YouTube/Vimeo: link semplice
        $videoLink = TplAccessibileFieldsHelper::safeLink($videoRaw);
        $videoHtml = $videoLink['url'] !== ''
            ? '<p><a href="' . $esc($videoLink['url']) . '">' . $esc($videoLink['url']) . '</a></p>'
            : '';
    }
    ?>
    <?php if ($videoHtml !== '') : ?>
        <h3 class="h4 mt-4 mb-3"><?php echo Text::_('TPL_ACCESSIBILE_EVENT_VIDEO'); ?></h3>
        <?php echo $videoHtml; ?>
    <?php endif; ?>
<?php $addSection('descrizione', Text::_('TPL_ACCESSIBILE_EVENT_WHAT_IS'), ob_get_clean()); ?>

<?php // ═══ 2. A chi è rivolto ═══ ?>
<?php ob_start(); ?>
    <?php $audienceField = $cf('cf_evento_a_chi_rivolto'); ?>
    <?php if ($audienceField && trim(strip_tags((string) $audienceField->value)) !== '') : ?>
        <div class="richtext-wrapper lora">
            <?php echo $audienceField->value; ?>
        </div>
    <?php endif; ?>
<?php $addSection('a-chi-e-rivolto', Text::_('TPL_ACCESSIBILE_EVENT_AUDIENCE'), ob_get_clean()); ?>

<?php // ═══ 3. Luogo ═══ ?>
<?php ob_start(); ?>
    <?php if (!empty($event->locations)) : ?>
        <?php foreach ($event->locations as $loc) : ?>
            <div class="card-wrapper card-teaser-wrapper">
                <div class="card shadow mt-3 rounded d-flex flex-row align-items-center">
                    <div class="card-teaser-icon p-3">
                        <svg class="icon icon-primary" aria-hidden="true">
                            <use href="<?php echo $sprite('it-pin'); ?>"></use>
                        </svg>
                    </div>
                    <div class="card-body ps-0">
                        <h3 class="card-title h5 mb-0">
                            <a class="text-decoration-none" href="<?php echo Route::_('index.php?option=com_dpcalendar&view=location&id=' . (int) $loc->id); ?>">
                                <?php echo $esc($loc->title); ?>
                            </a>
                        </h3>
                        <div class="card-text">
                            <p class="mb-0">
                                <?php
                                echo $esc(implode(', ', array_filter([
                                    trim(($loc->street ?? '') . ' ' . ($loc->number ?? '')),
                                    $loc->zip ?? '',
                                    $loc->city ?? '',
                                    $loc->province ?? '',
                                ])));
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if ($params->get('event_show_map', '1') && $locationsContent) : ?>
            <div class="map-wrapper map-column mt-4" role="region" aria-label="<?php echo $esc(Text::_('TPL_ACCESSIBILE_EVENT_MAP')); ?>">
                <div class="dp-locations-map-only">
                    <?php echo $locationsContent; ?>
                </div>
            </div>
            <script>
                /* Leaflet calcola le dimensioni all'init: dopo che il layout è a larghezza piena
                   forziamo un ricalcolo (invalidateSize via evento resize) così la mappa riempie il box. */
                (function () {
                    var refit = function () { window.dispatchEvent(new Event('resize')); };
                    var run = function () { setTimeout(refit, 100); setTimeout(refit, 500); };
                    if (document.readyState === 'complete') {
                        run();
                    } else {
                        window.addEventListener('load', run);
                    }
                })();
            </script>
        <?php endif; ?>
    <?php endif; ?>
<?php $addSection('luogo', Text::_('TPL_ACCESSIBILE_LOCATION'), ob_get_clean()); ?>

<?php // ═══ 4. Date e orari ═══ ?>
<?php
if ($isAllDay) {
    $points = $isSameDay
        ? [[$startDate, Text::_('TPL_ACCESSIBILE_EVENT_ALL_DAY')]]
        : [[$startDate, Text::_('TPL_ACCESSIBILE_EVENT_ALL_DAY')], [$endDate, Text::_('TPL_ACCESSIBILE_EVENT_ALL_DAY')]];
} elseif ($isSameDay) {
    $points = [[$startDate, Text::sprintf('TPL_ACCESSIBILE_EVENT_TIME_RANGE', '<strong>' . $startTime . '</strong>', '<strong>' . $endTime . '</strong>')]];
} else {
    $points = [
        [$startDate, Text::sprintf('TPL_ACCESSIBILE_EVENT_START_TIME', '<strong>' . $startTime . '</strong>')],
        [$endDate, Text::sprintf('TPL_ACCESSIBILE_EVENT_END_TIME', '<strong>' . $endTime . '</strong>')],
    ];
}

$rruleString = '';

if (!empty($event->rrule) && method_exists($this->dateHelper, 'transformRRuleToString')) {
    try {
        $rruleString = (string) $this->dateHelper->transformRRuleToString($event->rrule, $event->start_date, $event->exdates ?? null);
    } catch (\Throwable $e) {
        $rruleString = '';
    }
}
?>
<?php ob_start(); ?>
    <div class="point-list-wrapper my-4">
        <?php foreach ($points as [$pointDate, $pointLabel]) : ?>
            <div class="point-list">
                <div class="point-list-aside point-list-primary fw-normal">
                    <span class="point-date font-monospace"><?php echo $pointDate->format('d', true); ?></span>
                    <span class="point-month font-monospace"><?php echo $esc($pointDate->format('M', true, true)); ?></span>
                    <?php if ($showYear) : ?>
                        <span class="small font-monospace"><?php echo $pointDate->format('Y', true); ?></span>
                    <?php endif; ?>
                </div>
                <div class="point-list-content">
                    <div class="card card-teaser shadow rounded">
                        <div class="card-body">
                            <p class="card-title h5 m-0 fw-normal"><?php echo $pointLabel; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($rruleString !== '') : ?>
        <p class="mb-0">
            <span class="fw-semibold"><?php echo Text::_('TPL_ACCESSIBILE_EVENT_RECURRENCE'); ?>:</span>
            <?php echo nl2br($esc($rruleString)); ?>
        </p>
    <?php endif; ?>

    <?php if ($showCalendarExport) : ?>
        <?php $calendarLabel = Text::_('TPL_ACCESSIBILE_ADD_TO_CALENDAR'); ?>
        <?php if ($googleUrl && $icalUrl) : ?>
            <div class="dropdown mt-4">
                <button class="btn btn-outline-primary btn-sm btn-icon dropdown-toggle" type="button"
                        id="addToCalendar-evento" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg class="icon icon-primary icon-sm" aria-hidden="true"><use href="<?php echo $sprite('it-plus-circle'); ?>"></use></svg>
                    <span><?php echo $calendarLabel; ?></span>
                </button>
                <div class="dropdown-menu shadow-lg" aria-labelledby="addToCalendar-evento">
                    <div class="link-list-wrapper">
                        <ul class="link-list">
                            <li>
                                <a class="dropdown-item list-item" href="<?php echo $esc($googleUrl); ?>" target="_blank" rel="noopener noreferrer">
                                    <span><?php echo Text::_('TPL_ACCESSIBILE_ADD_TO_CALENDAR_GOOGLE'); ?> <span class="visually-hidden"><?php echo Text::_('TPL_ACCESSIBILE_NEW_WINDOW'); ?></span></span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item list-item" href="<?php echo $esc($icalUrl); ?>">
                                    <span><?php echo Text::_('TPL_ACCESSIBILE_ADD_TO_CALENDAR_ICS'); ?></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <?php // Un solo formato disponibile: link diretto, con il formato nel nome accessibile ?>
            <div class="mt-4">
                <a class="btn btn-outline-primary btn-sm btn-icon" href="<?php echo $esc($googleUrl ?: $icalUrl); ?>"
                   <?php echo $googleUrl ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                    <svg class="icon icon-primary icon-sm" aria-hidden="true"><use href="<?php echo $sprite('it-plus-circle'); ?>"></use></svg>
                    <span><?php echo $calendarLabel; ?>
                        <span class="visually-hidden">
                            (<?php echo Text::_($googleUrl ? 'TPL_ACCESSIBILE_ADD_TO_CALENDAR_GOOGLE' : 'TPL_ACCESSIBILE_ADD_TO_CALENDAR_ICS'); ?>)
                            <?php echo $googleUrl ? Text::_('TPL_ACCESSIBILE_NEW_WINDOW') : ''; ?>
                        </span>
                    </span>
                </a>
            </div>
        <?php endif; ?>
    <?php endif; ?>
<?php $addSection('date-orari', Text::_('TPL_ACCESSIBILE_EVENT_DATE_ORARI'), ob_get_clean()); ?>

<?php // ═══ 5. Costi: prezzi nativi DPCalendar, poi righe del campo mappato ═══ ?>
<?php
$costs = [];

if ($params->get('event_show_price', '1') && !empty($event->prices) && is_iterable($event->prices)) {
    $dpHelper = '\\DigitalPeak\\Component\\DPCalendar\\Administrator\\Helper\\DPCalendarHelper';

    foreach ($event->prices as $price) {
        if (!is_object($price)) {
            continue;
        }

        $value = (string) ($price->value ?? '');

        if ($value !== '' && class_exists($dpHelper)) {
            try {
                // renderPrice restituisce l'importo già formattato con la valuta di DPCalendar
                $value = (string) $dpHelper::renderPrice($value);
            } catch (\Throwable $e) {
                $value = $esc($value);
            }
        } else {
            $value = $esc($value);
        }

        $costs[] = [
            'title'       => $esc($price->label ?? ''),
            'price'       => $value,
            'description' => (string) ($price->description ?? ''), // HTML dell'editor DPCalendar
        ];
    }
}

$costsField = $cf('cf_evento_costi');

foreach (TplAccessibileFieldsHelper::rows($costsField) as $row) {
    $cTitle = TplAccessibileFieldsHelper::cellText($row, $key('cf_evento_costi_key_titolo'), 0);
    $cPrice = TplAccessibileFieldsHelper::cellText($row, $key('cf_evento_costi_key_prezzo'), 1);
    $cDesc  = TplAccessibileFieldsHelper::cellText($row, $key('cf_evento_costi_key_descrizione'), 2);

    if ($cTitle === '' && $cPrice === '' && $cDesc === '') {
        continue;
    }

    $costs[] = ['title' => $esc($cTitle), 'price' => $esc($cPrice), 'description' => nl2br($esc($cDesc))];
}

// Campo mappato non Subform (testo / editor): si stampa il valore così com'è
$costsText = ($costsField && !TplAccessibileFieldsHelper::rows($costsField) && is_string($costsField->value))
    ? trim($costsField->value)
    : '';
?>
<?php ob_start(); ?>
    <?php foreach ($costs as $cost) : ?>
        <div class="card no-after border-start border-primary mt-3">
            <div class="card-body">
                <?php if ($cost['title'] !== '') : ?>
                    <h3 class="h5 mb-1"><?php echo $cost['title']; ?></h3>
                <?php endif; ?>
                <?php if ($cost['price'] !== '') : ?>
                    <p class="fw-semibold mb-1"><?php echo $cost['price']; ?></p>
                <?php endif; ?>
                <?php if (trim(strip_tags($cost['description'])) !== '') : ?>
                    <div class="text-paragraph-small mb-0"><?php echo $cost['description']; ?></div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if ($costsText !== '') : ?>
        <div class="richtext-wrapper lora"><?php echo $costsText; ?></div>
    <?php endif; ?>
<?php $addSection('costi', Text::_('TPL_ACCESSIBILE_EVENT_COSTS'), ob_get_clean()); ?>

<?php // ═══ 6. Prenotazione ═══ ?>
<?php ob_start(); ?>
    <?php if ($bookingFormContent || $ctaContent || $bookingsContent) : ?>
        <div class="richtext-wrapper lora">
            <?php echo $ctaContent; ?>
            <?php echo $bookingFormContent; ?>
            <?php echo $bookingsContent; ?>
        </div>
    <?php endif; ?>
<?php $addSection('prenotazione', Text::_('TPL_ACCESSIBILE_EVENT_BOOKING'), ob_get_clean(), 'has-bg-grey p-4'); ?>

<?php // ═══ 7. Allegati ═══ ?>
<?php ob_start(); ?>
    <?php foreach ($fieldLinks($cf('cf_evento_allegati')) as $attachment) : ?>
        <?php if ($attachment['url'] === '') { continue; } ?>
        <?php $extension = strtoupper(pathinfo((string) parse_url($attachment['url'], PHP_URL_PATH), PATHINFO_EXTENSION)); ?>
        <div class="card card-teaser shadow mt-3 rounded">
            <svg class="icon icon-primary" aria-hidden="true"><use href="<?php echo $sprite('it-clip'); ?>"></use></svg>
            <div class="card-body">
                <h3 class="card-title h5 m-0">
                    <a class="text-decoration-none" href="<?php echo $esc($attachment['url']); ?>">
                        <?php echo $esc($attachment['name']); ?><?php echo ($extension !== '' && strlen($extension) <= 5) ? ' (' . $esc($extension) . ')' : ''; ?>
                    </a>
                </h3>
            </div>
        </div>
    <?php endforeach; ?>
<?php $addSection('allegati', Text::_('TPL_ACCESSIBILE_EVENT_ATTACHMENTS'), ob_get_clean()); ?>

<?php // ═══ 8. Contatti: punti di contatto (campo mappato), organizzatori e autore (DPCalendar) ═══ ?>
<?php ob_start(); ?>
    <?php foreach (TplAccessibileFieldsHelper::rows($cf('cf_evento_contatti')) as $row) : ?>
        <?php
        $contactName  = TplAccessibileFieldsHelper::cellText($row, $key('cf_evento_contatti_key_nome'), 0);
        $contactPhone = TplAccessibileFieldsHelper::cellText($row, $key('cf_evento_contatti_key_telefono'), 1);
        $contactEmail = TplAccessibileFieldsHelper::cellText($row, $key('cf_evento_contatti_key_email'), 2);
        $phoneHref    = preg_replace('/[^0-9+]/', '', $contactPhone);
        $emailValid   = $contactEmail !== '' && filter_var($contactEmail, FILTER_VALIDATE_EMAIL);

        if ($contactName === '' && $contactPhone === '' && $contactEmail === '') {
            continue;
        }
        ?>
        <div class="card card-teaser shadow mt-3 rounded">
            <svg class="icon icon-primary" aria-hidden="true"><use href="<?php echo $sprite('it-telephone'); ?>"></use></svg>
            <div class="card-body">
                <?php if ($contactName !== '') : ?>
                    <h3 class="card-title h5"><?php echo $esc($contactName); ?></h3>
                <?php endif; ?>
                <div class="card-text">
                    <?php if ($contactPhone !== '') : ?>
                        <p class="mb-1">
                            <?php if ($phoneHref !== '') : ?>
                                <a href="tel:<?php echo $esc($phoneHref); ?>"><?php echo $esc($contactPhone); ?></a>
                            <?php else : ?>
                                <?php echo $esc($contactPhone); ?>
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($contactEmail !== '') : ?>
                        <p class="mb-0">
                            <?php if ($emailValid) : ?>
                                <?php // Codifica dell'indirizzo: "?" e "&" aggiungerebbero parametri (cc, bcc) al mailto ?>
                                <a href="mailto:<?php echo $esc(str_replace('%40', '@', rawurlencode($contactEmail))); ?>"><?php echo $esc($contactEmail); ?></a>
                            <?php else : ?>
                                <?php echo $esc($contactEmail); ?>
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if ($showHosts) : ?>
        <h3 class="h5 mt-4"><?php echo Text::_('TPL_ACCESSIBILE_EVENT_SUPPORTED_BY'); ?></h3>
        <?php foreach ($event->hosts as $host) : ?>
            <div class="card card-teaser shadow mt-3 rounded">
                <svg class="icon icon-primary" aria-hidden="true"><use href="<?php echo $sprite('it-pa'); ?>"></use></svg>
                <div class="card-body">
                    <p class="card-title h5 m-0">
                        <?php $hostLink = TplAccessibileFieldsHelper::safeLink($host->link ?? '')['url']; ?>
                        <?php if ($hostLink !== '') : ?>
                            <a class="text-decoration-none" href="<?php echo $esc($hostLink); ?>"><?php echo $esc($host->name ?? ''); ?></a>
                        <?php else : ?>
                            <?php echo $esc($host->name ?? ''); ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if ($showAuthor) : ?>
        <h3 class="h5 mt-4"><?php echo Text::_('TPL_ACCESSIBILE_EVENT_AUTHOR'); ?>:</h3>
        <div class="card card-teaser shadow mt-3 rounded">
            <svg class="icon icon-primary" aria-hidden="true"><use href="<?php echo $sprite('it-user'); ?>"></use></svg>
            <div class="card-body">
                <p class="card-title h5 m-0">
                    <?php // authorName e avatar sono HTML prodotto dalla view di DPCalendar ?>
                    <?php $contactLink = TplAccessibileFieldsHelper::safeLink($event->contact_link ?? '')['url']; ?>
                    <?php if ($contactLink !== '') : ?>
                        <a class="text-decoration-none" href="<?php echo $esc($contactLink); ?>"><?php echo $this->authorName . ($this->avatar ?? ''); ?></a>
                    <?php else : ?>
                        <?php echo $this->authorName . ($this->avatar ?? ''); ?>
                    <?php endif; ?>
                </p>
            </div>
        </div>
    <?php endif; ?>
<?php $addSection('contatti', Text::_('TPL_ACCESSIBILE_EVENT_CONTACTS'), ob_get_clean()); ?>

<?php // ═══ 10 (preparata prima della 9). Ulteriori informazioni: registra i campi mappati come usati ═══ ?>
<?php ob_start(); ?>
    <?php $patronageHtml = $renderLinkList($fieldLinks($cf('cf_evento_patrocinato'))); ?>
    <?php if ($patronageHtml !== '') : ?>
        <h3 class="h5"><?php echo Text::_('TPL_ACCESSIBILE_EVENT_PATRONAGE'); ?></h3>
        <?php echo $patronageHtml; ?>
    <?php endif; ?>

    <?php $sponsorHtml = $renderLinkList($fieldLinks($cf('cf_evento_sponsor'))); ?>
    <?php if ($sponsorHtml !== '') : ?>
        <h3 class="h5"><?php echo Text::_('TPL_ACCESSIBILE_EVENT_SPONSOR'); ?></h3>
        <?php echo $sponsorHtml; ?>
    <?php endif; ?>

    <?php $moreInfoField = $cf('cf_evento_ulteriori_info'); ?>
    <?php if ($moreInfoField && trim(strip_tags((string) $moreInfoField->value)) !== '') : ?>
        <div class="richtext-wrapper lora mb-3">
            <?php echo $moreInfoField->value; ?>
        </div>
    <?php endif; ?>

    <?php if ($showUrl) : ?>
        <p class="mb-3"><?php echo Text::_('TPL_ACCESSIBILE_EVENT_EXTERNAL_LINK_INTRO'); ?></p>
        <p class="mb-3">
            <a class="btn btn-outline-primary btn-sm btn-icon" href="<?php echo $esc($eventUrl); ?>"
               target="_blank" rel="noopener noreferrer nofollow" aria-label="<?php echo $esc($externalLinkAria); ?>">
                <span><?php echo $externalLinkLabel; ?></span>
                <svg class="icon icon-primary icon-xs ms-2" aria-hidden="true"><use href="<?php echo $sprite('it-external-link'); ?>"></use></svg>
            </a>
        </p>
    <?php endif; ?>

    <?php if ($seriesContent || $scheduleContent || $ticketsContent) : ?>
        <div class="richtext-wrapper lora">
            <?php echo $seriesContent; ?>
            <?php echo $scheduleContent; ?>
            <?php echo $ticketsContent; ?>
        </div>
    <?php endif; ?>
<?php $moreInfoSectionHtml = ob_get_clean(); ?>

<?php // ═══ 9. Campi personalizzati non mappati: una sezione ciascuno, come prima della 1.3.0 ═══ ?>
<?php if ($tplParams->get('evento_campi_non_mappati', 'sezioni') !== 'nascondi') : ?>
    <?php foreach ($fieldIndex as $fieldId => $field) : ?>
        <?php
        if (isset($usedFields[$fieldId]) || empty($field->value)) {
            continue;
        }

        $fieldHtml = $field->type === 'subform' ? $renderGallery($fieldImages($field)) : '';

        if ($fieldHtml === '' && is_string($field->value)) {
            $embed     = $getVideoEmbed($field->rawvalue ?? '');
            $fieldHtml = '<div class="richtext-wrapper lora">' . ($embed !== '' ? $embed : $field->value) . '</div>';
        }

        $addSection('field-' . (int) $fieldId, $esc($field->title ?? ''), $fieldHtml);
        ?>
    <?php endforeach; ?>
<?php endif; ?>

<?php $addSection('informazioni', Text::_('TPL_ACCESSIBILE_SERVICE_INFO'), $moreInfoSectionHtml); ?>

<div class="container pt-4">
    <div class="row">
        <div class="col-lg-8 px-lg-4 py-lg-2 mb-3">

            <?php if ($categoryTitle !== '') : ?>
                <p class="category-top mb-1 small">
                    <span class="category text-primary fw-semibold text-uppercase"><?php echo $esc($categoryTitle); ?></span>
                </p>
            <?php endif; ?>

            <h1 class="mb-2" data-element="event-title">
                <?php echo $esc($event->title); ?>
            </h1>

            <?php if ($event->state == 3) : ?>
                <div class="mb-3">
                    <span class="badge rounded-pill bg-danger">
                        <?php echo Text::_('TPL_ACCESSIBILE_EVENT_CANCELED'); ?>
                    </span>
                </div>
            <?php endif; ?>

            <h2 class="visually-hidden"><?php echo Text::_('TPL_ACCESSIBILE_EVENT_DETAILS'); ?></h2>

            <p class="h4 py-2">
                <?php
                echo $startDateStr === $endDateStr
                    ? Text::sprintf('TPL_ACCESSIBILE_EVENT_ON_DATE', $esc($startDateStr))
                    : Text::sprintf('TPL_ACCESSIBILE_EVENT_FROM_TO', $esc($startDateStr), $esc($endDateStr));
                ?>
            </p>

            <?php // Collegamento esterno: pulsante compatto sotto la data ?>
            <?php if ($showUrl) : ?>
                <div class="mb-3">
                    <a class="btn btn-outline-primary btn-sm d-inline-flex align-items-center"
                       href="<?php echo $esc($eventUrl); ?>"
                       target="_blank" rel="noopener noreferrer nofollow"
                       aria-label="<?php echo $esc($externalLinkAria); ?>">
                        <svg class="icon icon-primary icon-xs me-2" aria-hidden="true"><use href="<?php echo $sprite('it-external-link'); ?>"></use></svg>
                        <span><?php echo $externalLinkLabel; ?></span>
                    </a>
                </div>
            <?php endif; ?>

            <?php if (!empty($event->introText)) : ?>
                <div class="lead">
                    <?php echo $event->introText; ?>
                </div>
            <?php endif; ?>

        </div>

        <div class="col-lg-3 offset-lg-1 header-laterale">

            <?php // Condividi + Vedi azioni (partial condiviso) ?>
            <?php echo LayoutHelper::render('accessibile.azioni-pagina', [
                'title'     => $event->title,
                'showPrint' => (bool) $params->get('event_show_print', 1),
                'idSuffix'  => 'evento',
            ]); ?>

            <?php // Argomenti (chip; fallback sul sotto-template DPCalendar) ?>
            <?php echo $tagsHtml; ?>

        </div>
    </div>
</div>

<?php if ($imageUrl !== '') : ?>
<div class="container-fluid my-3">
    <div class="row">
        <figure class="figure px-0 img-full">
            <img src="<?php echo $esc($imageUrl); ?>" class="figure-img img-fluid" alt="<?php echo $esc($imageAlt); ?>">
            <?php if ($imageCaption !== '') : ?>
                <figcaption class="figure-caption px-3"><?php echo $esc($imageCaption); ?></figcaption>
            <?php endif; ?>
        </figure>
    </div>
</div>
<?php elseif ($imageContent) : ?>
<?php // URL dell'immagine non ricavabile dai dati dell'evento: resa originale di DPCalendar ?>
<div class="container mb-4">
    <div class="row">
        <div class="col-12">
            <div class="it-page-section">
                <?php echo $imageContent; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="container">
    <div class="row border-top border-light row-column-border row-column-menu-left mt-4 mt-lg-80 pb-lg-80 pb-40">

        <?php // Sidebar sinistra — indice di pagina ?>
        <aside class="col-lg-3 mb-4 border-col">
            <div class="cmp-navscroll sticky-top" role="region" aria-labelledby="accordion-title-evento">
                <nav class="navbar it-navscroll-wrapper navbar-expand-lg"
                     aria-label="<?php echo Text::_('TPL_ACCESSIBILE_PAGE_INDEX'); ?>"
                     data-bs-navscroll="">
                    <div class="navbar-custom" id="navbarNavEvento">
                        <div class="menu-wrapper">
                            <div class="link-list-wrapper">
                                <div class="accordion">
                                    <div class="accordion-item">
                                        <span class="accordion-header" id="accordion-title-evento">
                                            <button class="accordion-button pb-10 px-3 text-uppercase" type="button"
                                                    aria-controls="collapse-evento" aria-expanded="true"
                                                    data-bs-toggle="collapse" data-bs-target="#collapse-evento">
                                                <?php echo Text::_('TPL_ACCESSIBILE_PAGE_INDEX'); ?>
                                            </button>
                                        </span>
                                        <div class="progress">
                                            <div class="progress-bar it-navscroll-progressbar" role="progressbar"
                                                 aria-label="<?php echo $esc(Text::_('TPL_ACCESSIBILE_READING_PROGRESS')); ?>"
                                                 aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                                 style="width: 0%;"></div>
                                        </div>
                                        <div id="collapse-evento" class="accordion-collapse collapse show"
                                             role="region" aria-labelledby="accordion-title-evento">
                                            <div class="accordion-body">
                                                <ul class="link-list" data-element="page-index">
                                                    <?php foreach ($sections as $section) : ?>
                                                        <li class="nav-item">
                                                            <a class="nav-link" href="#<?php echo $section['id']; ?>">
                                                                <span><?php echo $section['label']; ?></span>
                                                            </a>
                                                        </li>
                                                    <?php endforeach; ?>
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
        </aside>

        <?php // Contenuto principale ?>
        <div class="col-lg-9 it-page-sections-container border-light">

            <?php foreach ($sections as $section) : ?>
                <section class="it-page-section anchor-offset mb-30<?php echo $section['class'] !== '' ? ' ' . $section['class'] : ''; ?>" id="<?php echo $section['id']; ?>">
                    <h2 class="mb-3"><?php echo $section['label']; ?></h2>
                    <?php echo $section['html']; ?>
                </section>
            <?php endforeach; ?>

            <?php // Data ultimo aggiornamento ?>
            <?php if ($params->get('event_show_modify_date', 1) && !empty($event->modified)) : ?>
                <div class="mt-5 pt-4 border-top">
                    <p class="text-paragraph-small text-muted">
                        <?php echo Text::_('TPL_ACCESSIBILE_SERVICE_UPDATED'); ?>
                        <?php echo HTMLHelper::_('date', $event->modified, Text::_('DATE_FORMAT_LC3')); ?>
                    </p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>
