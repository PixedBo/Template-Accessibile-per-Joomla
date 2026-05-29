<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_dpcalendar_upcoming
 *
 * Layout alternativo per Modello Comuni — card "Evento" (sezione 2.1.6)
 */

defined('_JEXEC') or die();

use DigitalPeak\Component\DPCalendar\Administrator\Helper\Booking;
use DigitalPeak\Component\DPCalendar\Administrator\Helper\DPCalendarHelper;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$_parentTpl = \Joomla\CMS\Factory::getApplication()->getTemplate(true)->parent
    ?: \Joomla\CMS\Factory::getApplication()->getTemplate();
require_once JPATH_SITE . '/templates/' . $_parentTpl . '/helpers/TplAccessibileHelper.php';

$app->getLanguage()->load('tpl_templateaccessibileperjoomla', JPATH_SITE);

if (!$events) {
	echo $translator->translate($params->get('no_events_text', 'MOD_DPCALENDAR_UPCOMING_NO_EVENT_TEXT'));

	return;
}

require ModuleHelper::getLayoutPath('mod_dpcalendar_upcoming', '_scripts');

$monthNames = [];
for ($m = 1; $m <= 12; $m++) {
	$monthNames[$m] = Text::_('TPL_ACCESSIBILE_MONTH_' . $m);
}
?>
<div class="mod-dpcalendar-upcoming mod-dpcalendar-upcoming-joomlaPA mod-dpcalendar-upcoming-<?php echo $module->id; ?> dp-locations"
	data-popup="<?php echo $params->get('show_as_popup', 0); ?>">

	<div class="mod-dpcalendar-upcoming-joomlaPA__custom-text">
		<?php echo HTMLHelper::_('content.prepare', $translator->translate($params->get('textbefore', ''))); ?>
	</div>

	<div class="row g-4">
		<?php foreach ($groupedEvents as $groupHeading => $events) { ?>
			<?php if ($groupHeading) { ?>
				<div class="col-12">
					<h2 class="h4 dp-group-heading"><?php echo htmlspecialchars((string)$groupHeading, ENT_QUOTES, 'UTF-8'); ?></h2>
				</div>
			<?php } ?>
			<?php foreach ($events as $event) { ?>
				<?php
					$displayData['event'] = $event;
					$startDate  = $dateHelper->getDate($event->start_date, $event->all_day);
					$dayNum     = $startDate->format('j');
					$monthNum   = (int)$startDate->format('n');
					$isoDate    = $startDate->format('Y-m-d');
					$monthLabel = $monthNames[$monthNum] ?? '';

					try {
						$calendar = $app->bootComponent('dpcalendar')
							->getMVCFactory()
							->createModel('Calendar', 'Administrator')
							->getCalendar($event->catid);
					} catch (\Throwable $e) {
						$calendar = null;
					}

					$imgSrc  = $event->images->image_intro ?? '';
					$imgAlt  = $event->images->image_intro_alt ?: htmlspecialchars($event->title, ENT_QUOTES, 'UTF-8');
					$showImg = (bool) $params->get('show_image', 1);
				?>
			<div class="col-lg-6 col-xl-4">
				<div class="card-wrapper shadow-sm rounded border border-light">
					<div class="card no-after rounded dp-event dp-event_<?php echo $event->ongoing_start_date ? ($event->ongoing_end_date ? 'started' : 'finished') : 'future'; ?>">

						<?php if ($showImg && $imgSrc) : ?>
						<?php /* Caso A: immagine reale */ ?>
						<div class="img-responsive-wrapper">
							<div class="img-responsive img-responsive-panoramic">
								<figure class="img-wrapper">
									<img src="<?php echo htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8'); ?>"
										 alt="<?php echo htmlspecialchars($imgAlt, ENT_QUOTES, 'UTF-8'); ?>"
										 loading="lazy"
										 class="img-fluid rounded-top">
								</figure>
							</div>
						</div>
						<time class="card-calendar d-flex flex-column justify-content-center"
							  datetime="<?php echo $isoDate; ?>">
							<span class="card-date"><?php echo $dayNum; ?></span>
							<span class="card-day"><?php echo htmlspecialchars($monthLabel, ENT_QUOTES, 'UTF-8'); ?></span>
						</time>

						<?php elseif ($showImg) : ?>
						<?php /* Caso B: immagine abilitata ma assente → placeholder bg-evidenza + data top-right */ ?>
						<div class="tpl-event-placeholder bg-evidenza rounded-top" aria-hidden="true"></div>
						<time class="card-calendar d-flex flex-column justify-content-center"
							  datetime="<?php echo $isoDate; ?>">
							<span class="card-date"><?php echo $dayNum; ?></span>
							<span class="card-day"><?php echo htmlspecialchars($monthLabel, ENT_QUOTES, 'UTF-8'); ?></span>
						</time>

						<?php endif; ?>
						<?php /* Caso C: $showImg=false → nessun blocco immagine; la data va nel card-body */ ?>

						<div class="card-body">

							<?php if (!$showImg) : ?>
							<?php /* Caso C: data pill nel corpo card */ ?>
							<time class="tpl-event-date-pill" datetime="<?php echo $isoDate; ?>">
								<svg class="icon icon-sm icon-primary" aria-hidden="true">
									<use href="<?php echo TplAccessibileHelper::spriteUrl('it-calendar'); ?>"></use>
								</svg>
								<span><?php echo $dayNum . ' ' . htmlspecialchars($monthLabel, ENT_QUOTES, 'UTF-8'); ?></span>
							</time>
							<?php endif; ?>

							<?php if ($params->get('show_display_events') && $event->displayEvent->afterDisplayTitle) { ?>
								<div class="dp-event-display-after-title"><?php echo $event->displayEvent->afterDisplayTitle; ?></div>
							<?php } ?>

							<?php if ($event->state == 3) { ?>
								<span class="badge bg-danger mb-2">
									<?php echo $translator->translate('MOD_DPCALENDAR_UPCOMING_CANCELED'); ?>
								</span>
							<?php } ?>

							<?php if ($calendar !== null) { ?>
							<div class="category-top">
								<a class="category text-decoration-none"
								   href="<?php echo htmlspecialchars($router->getCalendarRoute($event->catid), ENT_QUOTES, 'UTF-8'); ?>">
									<?php echo htmlspecialchars($calendar->getTitle(), ENT_QUOTES, 'UTF-8'); ?>
								</a>
							</div>
							<?php } ?>

							<?php if ($params->get('show_display_events') && $event->displayEvent->beforeDisplayContent) { ?>
								<div class="dp-event-display-before-content"><?php echo $event->displayEvent->beforeDisplayContent; ?></div>
							<?php } ?>

							<h3 class="card-title">
								<a class="text-decoration-none"
								   href="<?php echo htmlspecialchars($event->realUrl, ENT_QUOTES, 'UTF-8'); ?>"
								   data-element="live-category-link">
									<?php echo htmlspecialchars($event->title, ENT_QUOTES, 'UTF-8'); ?>
								</a>
							</h3>

							<?php
								$desc = $event->truncatedDescription ?? '';
								// Rimuove il link readmore generato da DPCalendar
								$desc = preg_replace('/<p[^>]*\bclass=["\'][^"\']*\breadmore\b[^"\']*["\'][^>]*>.*?<\/p>/is', '', $desc);
								// Rimuove paragrafi che contengono solo puntini
								$desc = preg_replace('/<p[^>]*>\s*\.{2,}\s*<\/p>/i', '', $desc);
								// Rimuove puntini rimasti orfani alla fine della stringa (fuori dai tag) o prima di chiusure tag
								$desc = preg_replace('/\.\.\.+(\s*(<\/div>|<\/p>|$))/i', '$1', $desc);
								$desc = trim($desc);
							?>
							<?php if ($desc !== '') { ?>
							<div class="card-text text-secondary pb-3">
								<?php echo $desc; ?>
							</div>
							<?php } ?>

							<div class="d-flex align-items-center gap-2 mb-2">
								<svg class="icon icon-sm icon-primary" aria-hidden="true">
									<use href="<?php echo TplAccessibileHelper::spriteUrl('it-clock'); ?>"></use>
								</svg>
								<span><?php echo $dateHelper->getDateStringFromEvent(
									$event,
									$params->get('date_format'),
									$params->get('time_format')
								); ?></span>
							</div>

							<?php if ($event->rrule) { ?>
							<div class="d-flex align-items-center gap-2 mb-2">
								<svg class="icon icon-sm icon-primary" aria-hidden="true">
									<use href="<?php echo TplAccessibileHelper::spriteUrl('it-refresh'); ?>"></use>
								</svg>
								<span><?php echo nl2br((string)$dateHelper->transformRRuleToString(
									$event->rrule,
									$event->start_date,
									$event->exdates
								)); ?></span>
							</div>
							<?php } ?>

							<?php if (($params->get('show_location') || $params->get('show_map')) && isset($event->locations) && $event->locations) { ?>
							<div class="mod-dpcalendar-upcoming-joomlaPA__location mb-2">
								<?php foreach ($event->locations as $location) { ?>
								<div class="dp-location<?php echo $params->get('show_location') ? '' : ' dp-location_hidden'; ?>">
									<div class="dp-location__details"
										 data-latitude="<?php echo $location->latitude; ?>"
										 data-longitude="<?php echo $location->longitude; ?>"
										 data-title="<?php echo $location->title; ?>"
										 data-color="<?php echo $event->color; ?>"></div>
									<?php if ($params->get('show_location')) { ?>
									<div class="d-flex align-items-center gap-2">
										<svg class="icon icon-sm icon-primary" aria-hidden="true">
											<use href="<?php echo TplAccessibileHelper::spriteUrl('it-pin'); ?>"></use>
										</svg>
										<a href="<?php echo htmlspecialchars($router->getLocationRoute($location), ENT_QUOTES, 'UTF-8'); ?>"
										   class="dp-location__url text-decoration-none">
											<span class="dp-location__title"><?php echo htmlspecialchars($location->title, ENT_QUOTES, 'UTF-8'); ?></span>
											<?php if (!empty($event->roomTitles[$location->id])) { ?>
												<span class="dp-location__rooms">
													[<?php echo htmlspecialchars(implode(', ', $event->roomTitles[$location->id]), ENT_QUOTES, 'UTF-8'); ?>]
												</span>
											<?php } ?>
										</a>
									</div>
									<?php } ?>
									<div class="dp-location__description">
									</div>
								</div>
								<?php } ?>
							</div>
							<?php } ?>

							<?php if ($params->get('show_price') && $event->prices) { ?>
								<?php foreach ($event->prices as $price) { ?>
									<?php $discounted = Booking::getPriceWithDiscount($price->value, $event); ?>
									<div class="mod-dpcalendar-upcoming-joomlaPA__price dp-event-price mb-2">
										<svg class="icon icon-sm icon-primary" aria-hidden="true">
											<use href="<?php echo TplAccessibileHelper::spriteUrl('it-card'); ?>"></use>
										</svg>
										<span class="dp-event-price__label">
											<?php echo $price->label ?: $translator->translate('MOD_DPCALENDAR_UPCOMING_PRICES'); ?>
										</span>
										<span class="dp-event-price__regular<?php echo $discounted != $price->value ? ' dp-event-price__regular_has-discount' : ''; ?>">
											<?php echo $price->value === '' ? '' : DPCalendarHelper::renderPrice($price->value); ?>
										</span>
										<?php if ($discounted != $price->value) { ?>
											<span class="dp-event-price__discount"><?php echo DPCalendarHelper::renderPrice($discounted); ?></span>
										<?php } ?>
										<span class="dp-event-price__description"><?php echo $price->description; ?></span>
									</div>
								<?php } ?>
							<?php } ?>

							<?php if ($params->get('show_booking', 1) && Booking::openForBooking($event)) { ?>
							<a class="btn btn-primary btn-sm mb-3"
							   href="<?php echo htmlspecialchars(
							       $router->getBookingFormRouteFromEvent($event, $return, true, $moduleParams->get('default_menu_item', 0)),
							       ENT_QUOTES, 'UTF-8'
							   ); ?>">
								<svg class="icon icon-white icon-sm me-1" aria-hidden="true">
									<use href="<?php echo TplAccessibileHelper::spriteUrl('it-calendar'); ?>"></use>
								</svg>
								<?php echo $translator->translate('MOD_DPCALENDAR_UPCOMING_BOOK'); ?>
							</a>
							<?php } ?>

							<?php if ($params->get('show_display_events') && $event->displayEvent->afterDisplayContent) { ?>
								<div class="dp-event-display-after-content"><?php echo $event->displayEvent->afterDisplayContent; ?></div>
							<?php } ?>

							<a class="read-more t-primary text-uppercase"
							   href="<?php echo htmlspecialchars($event->realUrl, ENT_QUOTES, 'UTF-8'); ?>"
							   aria-label="<?php echo htmlspecialchars(
							       Text::sprintf('TPL_ACCESSIBILE_JOOMLAPA_READMORE_ARIA', $event->title),
							       ENT_QUOTES, 'UTF-8'
							   ); ?>">
								<span class="text"><?php echo Text::_('TPL_ACCESSIBILE_JOOMLAPA_READMORE'); ?></span>
								<svg class="icon icon-primary icon-xs ms-1" aria-hidden="true">
									<use href="<?php echo TplAccessibileHelper::spriteUrl('it-arrow-right'); ?>"></use>
								</svg>
							</a>


						</div>
					</div>
				</div>
			</div>

			<?php } ?>
		<?php } ?>
	</div>

	<?php if ($params->get('show_map')) { ?>
		<div class="mod-dpcalendar-upcoming-joomlaPA__map dp-map"
			 style="width: <?php echo $params->get('map_width', '100%'); ?>; height: <?php echo $params->get('map_height', '350px'); ?>"
			 data-zoom="<?php echo $params->get('map_zoom', 4); ?>"
			 data-latitude="<?php echo $params->get('map_lat', 47); ?>"
			 data-longitude="<?php echo $params->get('map_long', 4); ?>"
			 data-ask-consent="<?php echo $params->get('map_ask_consent'); ?>">
		</div>
	<?php } ?>

	<div class="mod-dpcalendar-upcoming-joomlaPA__custom-text">
		<?php echo HTMLHelper::_('content.prepare', $translator->translate($params->get('textafter', ''))); ?>
	</div>
</div>
