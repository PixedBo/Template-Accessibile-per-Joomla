<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_dpcalendar
 *
 * Sub-template lista verticale card a due colonne per il layout lista eventi DPCalendar.
 * Caricato da eventi.php via $this->loadTemplate('lista').
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

/** @var \DigitalPeak\Component\DPCalendar\Site\View\List\HtmlView $this */

$listaEvents     = $this->listaEvents;
$monthNames      = $this->monthNames;
$mostraImmagine  = $this->mostraImmagine;
$mostraCategoria = $this->mostraCategoria;
$mostraLuogo     = $this->mostraLuogo;
$troncaTesto     = $this->troncaTesto ?? 0;

// Mappa calId → titolo calendario.
// La DPCalendar list view non fa JOIN su #__categories, quindi $event->category_title
// è sempre vuoto. $this->calendars (usato anche nel filtro) ha getId()/getTitle().
$calTitlesMap = [];
if (!empty($this->calendars)) {
    foreach ($this->calendars as $cal) {
        $calTitlesMap[(string) $cal->getId()] = $cal->getTitle();
    }
}
?>

<div class="container mt-4 mb-5">

    <?php if (empty($listaEvents)) : ?>
        <p class="alert alert-info" role="status">
            <?php echo Text::_('TPL_ACCESSIBILE_DPCALENDAR_NO_EVENTS'); ?>
        </p>
    <?php else : ?>

        <ul class="tpl-event-list list-unstyled mb-4">

            <?php foreach ($listaEvents as $event) :
                $eventUrl = Route::_('index.php?option=com_dpcalendar&view=event&id=' . $event->id . '&calid=' . $event->catid);

                $images  = is_object($event->images) ? $event->images : json_decode($event->images ?? '{}');
                $imgSrc  = $images->image_intro ?? '';
                $imgAlt  = $images->image_intro_alt ?? $event->title;

                if ((bool)$event->all_day) {
                    $startDate = Factory::getDate($event->start_date);
                } else {
                    $tz        = new \DateTimeZone(Factory::getApplication()->get('offset', 'UTC'));
                    $startDate = Factory::getDate($event->start_date);
                    $startDate->setTimezone($tz);
                }
                $startDay   = $startDate->format('j', true);
                $startMonth = $monthNames[(int) $startDate->format('n', true)] ?? '';
                $startYear  = $startDate->format('Y', true);

                $firstLocation = !empty($event->locations) ? reset($event->locations) : null;
                $calTitle = !empty($event->category_title)
                    ? (string) $event->category_title
                    : ($calTitlesMap[(string) ($event->catid ?? '')] ?? '');
                if ($troncaTesto > 0) {
                    $truncated = HTMLHelper::_('string.truncate', strip_tags((string) ($event->description ?? '')), $troncaTesto);
                } else {
                    $truncated = $event->truncatedDescription ?? '';
                }
            ?>
                <li class="tpl-event-list__item border-bottom py-3">
                    <div class="card no-after shadow-sm rounded overflow-hidden tpl-event-card-row<?php echo !$mostraImmagine ? ' tpl-event-card-row--no-img' : ''; ?>">
                        <div class="row g-0">

                            <?php if ($mostraImmagine) : ?>
                            <!-- COLONNA SINISTRA: immagine o placeholder bg-evidenza + badge data -->
                            <div class="col-12 col-md-4 position-relative tpl-event-img-col<?php echo !$imgSrc ? ' tpl-event-img-col--placeholder' : ''; ?>">

                                <?php if ($imgSrc) : ?>
                                    <!-- Caso A: immagine reale -->
                                    <img src="<?php echo htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8'); ?>"
                                         loading="lazy"
                                         alt="<?php echo htmlspecialchars($imgAlt, ENT_QUOTES, 'UTF-8'); ?>"
                                         class="tpl-event-img-cover">
                                <?php else : ?>
                                    <!-- Caso B: placeholder bg-evidenza -->
                                    <div class="tpl-event-placeholder bg-evidenza w-100 h-100" aria-hidden="true"></div>
                                <?php endif; ?>

                                <!-- badge data: top-right su immagine; centrato su placeholder via CSS -->
                                <time class="card-calendar d-flex flex-column justify-content-center"
                                      datetime="<?php echo $startDate->format('Y-m-d'); ?>">
                                    <span class="card-date"><?php echo $startDay; ?></span>
                                    <span class="card-day"><?php echo $startMonth; ?></span>
                                </time>

                            </div>
                            <?php endif; ?>

                            <!-- COLONNA DESTRA: corpo card (full-width se immagine disabilitata) -->
                            <div class="col-12 <?php echo $mostraImmagine ? 'col-md-8' : ''; ?>">
                                <div class="card-body d-flex flex-column h-100 p-3 p-md-4">

                                    <?php if (!$mostraImmagine) : ?>
                                    <!-- Caso C: data pill (immagine disabilitata) -->
                                    <time class="tpl-event-date-pill" datetime="<?php echo $startDate->format('Y-m-d'); ?>">
                                        <svg class="icon icon-sm icon-primary" aria-hidden="true">
                                            <use href="<?php echo TplAccessibileHelper::spriteUrl('it-calendar'); ?>"></use>
                                        </svg>
                                        <span><?php echo $startDay . ' ' . $startMonth . ' ' . $startYear; ?></span>
                                    </time>
                                    <?php endif; ?>

                                    <?php if ($mostraCategoria && $calTitle !== '') : ?>
                                        <div class="mb-1">
                                            <span class="badge bg-light text-dark small fw-normal">
                                                <?php echo htmlspecialchars($calTitle, ENT_QUOTES, 'UTF-8'); ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>

                                    <h3 class="card-title mb-2">
                                        <a href="<?php echo $eventUrl; ?>"
                                           data-element="event-link"
                                           class="text-decoration-none">
                                            <?php echo $this->escape($event->title); ?>
                                        </a>
                                    </h3>

                                    <?php if ($truncated) : ?>
                                        <p class="card-text text-muted mb-2"><?php echo $truncated; ?></p>
                                    <?php endif; ?>

                                    <!-- META FOOTER: luogo, ricorrenza -->
                                    <ul class="list-inline mb-0 mt-auto small text-muted">
                                        <?php if ($mostraLuogo && $firstLocation) : ?>
                                            <li class="list-inline-item me-3">
                                                <svg class="icon icon-sm icon-muted me-1" aria-hidden="true">
                                                    <use href="<?php echo TplAccessibileHelper::spriteUrl('it-pin'); ?>"></use>
                                                </svg>
                                                <span><?php echo $this->escape($firstLocation->title); ?></span>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (!empty($event->rrule)) : ?>
                                            <li class="list-inline-item">
                                                <svg class="icon icon-sm icon-muted me-1" aria-hidden="true">
                                                    <use href="<?php echo TplAccessibileHelper::spriteUrl('it-refresh'); ?>"></use>
                                                </svg>
                                                <span><?php echo Text::_('TPL_ACCESSIBILE_DPCALENDAR_RECURRING_EVENT'); ?></span>
                                            </li>
                                        <?php endif; ?>
                                    </ul>

                                </div>
                            </div>

                        </div>
                    </div>
                </li>

            <?php endforeach; ?>

        </ul>

        <?php if (!empty($this->pagination)) : ?>
            <nav aria-label="<?php echo $this->escape(Text::_('JPAGER')); ?>" class="mt-4">
                <?php echo str_replace('<a ', '<a data-element="pager-link" ', $this->pagination->getListFooter()); ?>
            </nav>
        <?php endif; ?>

    <?php endif; ?>

</div>
