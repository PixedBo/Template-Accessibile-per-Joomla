<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_dpcalendar
 *
 * Sub-template carousel "In evidenza" per il layout lista eventi DPCalendar.
 * Caricato da eventi.php via $this->loadTemplate('evidenza').
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

/** @var \DigitalPeak\Component\DPCalendar\Site\View\List\HtmlView $this */

$featuredEvents  = $this->featuredEvents;
$monthNames      = $this->monthNames;
$mostraImmagine  = $this->mostraImmagine;
$mostraCategoria = $this->mostraCategoria;

// Mappa calId → titolo calendario (fallback per $event->category_title vuoto).
// Stessa logica di eventi_lista.php: la list view DPCalendar non popola category_title.
$calTitlesMap = [];
if (!empty($this->calendars)) {
    foreach ($this->calendars as $cal) {
        $calTitlesMap[(string) $cal->getId()] = $cal->getTitle();
    }
}

// Raggruppa in slide da 3 card (1 per slide su mobile, 3 su desktop via grid)
$chunks    = array_chunk($featuredEvents, 3);
$carouselId = 'eventi-evidenza-carousel';
?>

<section class="tpl-event-evidenza bg-primary py-5">
    <div class="container">

        <h2 class="title-xxlarge text-white mb-4">
            <?php echo Text::_('TPL_ACCESSIBILE_DPCALENDAR_EVIDENZA_HEADING'); ?>
        </h2>

        <div class="it-carousel-wrapper"
             role="region"
             aria-label="<?php echo $this->escape(Text::_('TPL_ACCESSIBILE_DPCALENDAR_EVIDENZA_HEADING')); ?>">

            <div class="carousel slide" id="<?php echo $carouselId; ?>" data-bs-ride="false">

                <div class="carousel-inner">
                    <?php foreach ($chunks as $chunkIndex => $chunk) : ?>
                        <div class="carousel-item <?php echo $chunkIndex === 0 ? 'active' : ''; ?>">
                            <div class="row g-4">
                                <?php foreach ($chunk as $event) :
                                    $images   = is_object($event->images) ? $event->images : json_decode($event->images ?? '{}');
                                    $imgSrc   = $images->image_intro ?? '';
                                    $imgAlt   = $images->image_intro_alt ?? $event->title;
                                    $eventUrl = Route::_('index.php?option=com_dpcalendar&view=event&id=' . $event->id . '&calid=' . $event->catid);

                                    if ((bool)$event->all_day) {
                                        $startDate  = Factory::getDate($event->start_date);
                                        $endDateObj = !empty($event->end_date) ? Factory::getDate($event->end_date) : null;
                                    } else {
                                        $tz         = new \DateTimeZone(Factory::getApplication()->get('offset', 'UTC'));
                                        $startDate  = Factory::getDate($event->start_date);
                                        $startDate->setTimezone($tz);
                                        $endDateObj = !empty($event->end_date) ? Factory::getDate($event->end_date) : null;
                                        if ($endDateObj) {
                                            $endDateObj->setTimezone($tz);
                                        }
                                    }
                                    $startDay   = $startDate->format('j', true);
                                    $startMonth = $monthNames[(int) $startDate->format('n', true)] ?? '';
                                    $startYear  = $startDate->format('Y', true);
                                    $isMultiDay = $endDateObj && $startDate->format('Y-m-d', true) !== $endDateObj->format('Y-m-d', true);
                                    $calTitle   = !empty($event->category_title)
                                        ? (string) $event->category_title
                                        : ($calTitlesMap[(string) ($event->catid ?? '')] ?? '');
                                ?>
                                    <div class="col-12 col-lg-4">
                                        <div class="card-wrapper h-100">
                                            <div class="card card-bg h-100 no-after">

                                                <?php if ($mostraImmagine && $imgSrc) : ?>
                                                    <!-- Caso A: immagine reale -->
                                                    <div class="img-responsive-wrapper">
                                                        <div class="img-responsive img-responsive-panoramic">
                                                            <figure class="img-wrapper">
                                                                <img src="<?php echo htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8'); ?>"
                                                                     loading="lazy"
                                                                     alt="<?php echo htmlspecialchars($imgAlt, ENT_QUOTES, 'UTF-8'); ?>">
                                                            </figure>
                                                        </div>
                                                    </div>
                                                <?php elseif ($mostraImmagine) : ?>
                                                    <!-- Caso B: immagine abilitata ma assente → placeholder bg-evidenza + data centrata -->
                                                    <div class="tpl-event-placeholder bg-evidenza rounded-top" aria-hidden="true">
                                                        <time class="card-calendar d-flex flex-column justify-content-center align-items-center"
                                                              datetime="<?php echo $startDate->format('Y-m-d', true); ?>">
                                                            <span class="card-date"><?php echo $startDay; ?></span>
                                                            <span class="card-day"><?php echo $startMonth; ?></span>
                                                        </time>
                                                    </div>
                                                <?php endif; ?>
                                                <!-- Caso C: $mostraImmagine=0 → nessun blocco immagine; data pill sotto il titolo -->

                                                <div class="card-body">

                                                    <?php if ($mostraCategoria && $calTitle !== '') : ?>
                                                        <div class="category-top mb-1">
                                                            <span class="category text-muted small">
                                                                <?php echo htmlspecialchars($calTitle, ENT_QUOTES, 'UTF-8'); ?>
                                                            </span>
                                                        </div>
                                                    <?php endif; ?>

                                                    <h3 class="card-title">
                                                        <a class="text-decoration-none"
                                                           href="<?php echo $eventUrl; ?>"
                                                           data-element="event-link">
                                                            <?php echo $this->escape($event->title); ?>
                                                        </a>
                                                    </h3>

                                                    <?php if ($mostraImmagine) : ?>
                                                    <!-- Casi A e B: data come testo sotto il titolo -->
                                                    <p class="card-text text-muted small mb-0">
                                                        <?php if ($isMultiDay) : ?>
                                                            <span class="visually-hidden"><?php echo Text::_('TPL_ACCESSIBILE_DPCALENDAR_FROM_DATE'); ?></span>
                                                            <?php echo $startDay . ' ' . $startMonth . ' ' . $startYear; ?>
                                                            &rarr;
                                                            <span class="visually-hidden"><?php echo Text::_('TPL_ACCESSIBILE_DPCALENDAR_TO_DATE'); ?></span>
                                                            <?php echo $endDateObj->format('j', true) . ' ' . ($monthNames[(int) $endDateObj->format('n', true)] ?? '') . ' ' . $endDateObj->format('Y', true); ?>
                                                        <?php else : ?>
                                                            <?php echo $startDay . ' ' . $startMonth . ' ' . $startYear; ?>
                                                        <?php endif; ?>
                                                    </p>
                                                    <?php else : ?>
                                                    <!-- Caso C: data pill -->
                                                    <time class="tpl-event-date-pill mt-1" datetime="<?php echo $startDate->format('Y-m-d', true); ?>">
                                                        <svg class="icon icon-sm" aria-hidden="true" style="fill: currentColor;">
                                                            <use href="<?php echo TplAccessibileHelper::spriteUrl('it-calendar'); ?>"></use>
                                                        </svg>
                                                        <span>
                                                            <?php if ($isMultiDay) : ?>
                                                                <?php echo $startDay . ' ' . $startMonth; ?> &rarr; <?php echo $endDateObj->format('j', true) . ' ' . ($monthNames[(int) $endDateObj->format('n', true)] ?? ''); ?>
                                                            <?php else : ?>
                                                                <?php echo $startDay . ' ' . $startMonth . ' ' . $startYear; ?>
                                                            <?php endif; ?>
                                                        </span>
                                                    </time>
                                                    <?php endif; ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if (count($chunks) > 1) : ?>
                    <div class="carousel-indicators position-static mt-3 justify-content-start">
                        <?php foreach ($chunks as $i => $unused) : ?>
                            <button type="button"
                                    data-bs-target="#<?php echo $carouselId; ?>"
                                    data-bs-slide-to="<?php echo $i; ?>"
                                    class="<?php echo $i === 0 ? 'active' : ''; ?>"
                                    aria-current="<?php echo $i === 0 ? 'true' : 'false'; ?>"
                                    aria-label="Slide <?php echo $i + 1; ?>">
                            </button>
                        <?php endforeach; ?>
                    </div>

                    <div class="d-flex gap-2 mt-2">
                        <button class="btn btn-outline-light btn-sm"
                                type="button"
                                data-bs-target="#<?php echo $carouselId; ?>"
                                data-bs-slide="prev"
                                aria-label="<?php echo $this->escape(Text::_('TPL_ACCESSIBILE_DPCALENDAR_CAROUSEL_PREV')); ?>">
                            <svg class="icon icon-white icon-sm" aria-hidden="true">
                                <use href="<?php echo TplAccessibileHelper::spriteUrl('it-chevron-left'); ?>"></use>
                            </svg>
                        </button>
                        <button class="btn btn-outline-light btn-sm"
                                type="button"
                                data-bs-target="#<?php echo $carouselId; ?>"
                                data-bs-slide="next"
                                aria-label="<?php echo $this->escape(Text::_('TPL_ACCESSIBILE_DPCALENDAR_CAROUSEL_NEXT')); ?>">
                            <svg class="icon icon-white icon-sm" aria-hidden="true">
                                <use href="<?php echo TplAccessibileHelper::spriteUrl('it-chevron-right'); ?>"></use>
                            </svg>
                        </button>
                    </div>
                <?php endif; ?>

            </div>
        </div>

    </div>
</section>
