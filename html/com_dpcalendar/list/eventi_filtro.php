<?php
/**
 * Sub-template: form filtro eventi (POST + Bootstrap Italia).
 *
 * Il wrapper collapse è gestito da eventi.php (data-bs-toggle="collapse").
 * Questo file emette il <form> principale + un form separato per il reset.
 *
 * Nomi campo compatibili con il modello DPCalendar:
 *   filter[search]      — ricerca testo
 *   list[start-date]    — data inizio (YYYY-MM-DD, da HTML5 date input)
 *   list[end-date]      — data fine
 *   filter[calendars][] — ID calendario (assente = tutti i calendari visibili)
 *
 * Metodo POST (non GET):
 *   DPCalendar aggiorna lo stato sessione (getUserStateFromRequest) solo su POST.
 *   Con GET i parametri in URL non aggiornano la sessione → il filtro non si applica.
 *
 * Logica checkbox calendari:
 *   - nessun checkbox inviato = filter[calendars][] assente = DPCalendar mostra tutti ✅
 *   - uno o più selezionati = solo quegli eventi
 *
 * Reset (form separato POST, collegato via attributo form=""):
 *   Il pulsante Reimposta fa parte del form esterno #eventi-filtro-reset (attributo
 *   form="eventi-filtro-reset"), che invia filter[search]="" e list[start-date]="" via
 *   POST così DPCalendar aggiorna la sessione con valori vuoti. Struttura valida HTML5:
 *   nessun form annidato dentro un altro form.
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

/** @var \DigitalPeak\Component\DPCalendar\Site\View\List\HtmlView $this */

$app        = Factory::getApplication();
$activeMenu = $app->getMenu()->getActive();
$itemId     = $activeMenu ? (int) $activeMenu->id : 0;
$baseUrl    = Route::_('index.php' . ($itemId ? '?Itemid=' . $itemId : ''));

$filterInputArr = $app->getInput()->get('filter', [], 'array');
$listInputArr   = $app->getInput()->get('list', [], 'array');

// Legge la sessione DPCalendar con la chiave FISSA usata da List\HtmlView (com_dpcalendar.listview.*).
// HtmlView chiama getUserStateFromRequest su ogni request (GET e POST), quindi questa chiave
// contiene SEMPRE il dato più recente — sia l'invio POST appena effettuato sia il fallback
// sessione per le richieste GET successive (paginazione, link diretti, F5).
$dpFilterState = $app->getUserState('com_dpcalendar.listview.filter', []);
$dpListState   = $app->getUserState('com_dpcalendar.listview.list',   []);
$dpFilterState = is_array($dpFilterState) ? $dpFilterState : [];
$dpListState   = is_array($dpListState)   ? $dpListState   : [];

// Valori correnti: priorità → POST/GET corrente → sessione DPCalendar → stringa vuota
$filterSearch = (string) ($filterInputArr['search']    ?? $dpFilterState['search']      ?? '');
$filterFrom   = (string) ($listInputArr['start-date']  ?? $dpListState['start-date']    ?? '');
$filterTo     = (string) ($listInputArr['end-date']    ?? $dpListState['end-date']      ?? '');

// Calendari selezionati: 3 livelli di priorità
//   1. POST corrente: filter[calendars][] presente → usa quelli
//   2. Sessione DPCalendar (com_dpcalendar.listview.filter['calendars']) → ricariche GET
//   3. Nessun dato → null (checkbox tutte vuote = mostra tutti gli eventi)
// Confronto via strval() per neutralizzare mismatch int/string tra getId() e i valori del form.
if (isset($filterInputArr['calendars'])) {
    $filterCalendars = array_map('strval', (array) $filterInputArr['calendars']);
} elseif (!empty($dpFilterState['calendars'])) {
    $filterCalendars = array_map('strval', (array) $dpFilterState['calendars']);
} else {
    $filterCalendars = null;
}

$calendars = $this->calendars ?? [];
?>

<!-- ===== Form filtro principale ===== -->
<form method="post" action="<?php echo $baseUrl; ?>"
      class="row g-3 align-items-end" id="eventi-filtro-form">
    <?php if ($itemId) : ?>
        <input type="hidden" name="Itemid" value="<?php echo $itemId; ?>">
    <?php endif; ?>

    <?php if (!empty($calendars)) : ?>
        <!-- Filtro per calendario: checkbox con layout flex (niente .form-check float) -->
        <div class="col-12">
            <fieldset>
                <legend class="form-label fw-semibold small text-uppercase text-muted mb-2">
                    <?php echo Text::_('TPL_ACCESSIBILE_DPCALENDAR_EVENT_CATEGORY'); ?>
                </legend>
                <div class="d-flex flex-wrap gap-3">
                    <?php foreach ($calendars as $cal) : ?>
                        <?php
                        $calId     = $cal->getId();
                        $calTitle  = $this->escape($cal->getTitle());
                        // Checked solo se c'è un filtro attivo E questo calendario è incluso.
                        // null = nessun filtro → tutti i checkbox vuoti (mostra tutti gli eventi).
                        // Confronto via strval: getId() può restituire int o string.
                        $isChecked = $filterCalendars !== null
                                  && in_array((string) $calId, $filterCalendars);
                        ?>
                        <div class="d-flex align-items-center gap-2">
                            <input class="form-check-input flex-shrink-0" type="checkbox"
                                   name="filter[calendars][]" value="<?php echo $calId; ?>"
                                   id="cal-<?php echo $calId; ?>"
                                   <?php echo $isChecked ? 'checked' : ''; ?>>
                            <label class="form-check-label mb-0" for="cal-<?php echo $calId; ?>">
                                <?php echo $calTitle; ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </fieldset>
        </div>
    <?php endif; ?>

    <!-- Ricerca testo -->
    <div class="col-12 col-md-4">
        <label for="filter_search" class="form-label fw-semibold">
            <?php echo Text::_('TPL_ACCESSIBILE_DPCALENDAR_FILTER_SEARCH_LABEL'); ?>
        </label>
        <input type="text" class="form-control" id="filter_search"
               name="filter[search]"
               value="<?php echo $this->escape($filterSearch); ?>"
               placeholder="<?php echo Text::_('TPL_ACCESSIBILE_DPCALENDAR_FILTER_SEARCH_LABEL'); ?>">
    </div>

    <!-- Data inizio -->
    <div class="col-12 col-sm-6 col-md-4">
        <label for="list_start_date" class="form-label fw-semibold">
            <?php echo Text::_('TPL_ACCESSIBILE_DPCALENDAR_FILTER_DATE_FROM'); ?>
        </label>
        <input type="date" class="form-control" id="list_start_date"
               name="list[start-date]"
               value="<?php echo $this->escape($filterFrom); ?>">
    </div>

    <!-- Data fine -->
    <div class="col-12 col-sm-6 col-md-4">
        <label for="list_end_date" class="form-label fw-semibold">
            <?php echo Text::_('TPL_ACCESSIBILE_DPCALENDAR_FILTER_DATE_TO'); ?>
        </label>
        <input type="date" class="form-control" id="list_end_date"
               name="list[end-date]"
               value="<?php echo $this->escape($filterTo); ?>">
    </div>

    <!-- Pulsante Applica -->
    <div class="col-12 d-flex flex-wrap align-items-center gap-2">
        <button type="submit" class="btn btn-primary">
            <?php echo Text::_('TPL_ACCESSIBILE_DPCALENDAR_FILTER_APPLY'); ?>
        </button>

        <!-- Il pulsante Reimposta è associato al form esterno tramite form="eventi-filtro-reset".
             HTML5 valido: nessun form annidato dentro un altro form. -->
        <button type="submit" form="eventi-filtro-reset" class="btn btn-outline-secondary">
            <?php echo Text::_('TPL_ACCESSIBILE_DPCALENDAR_FILTER_RESET'); ?>
        </button>
    </div>

</form>

<!-- Form reset separato (fuori dal form principale — HTML5 valido).
     Invia valori esplicitamente vuoti via POST così DPCalendar aggiorna la sessione. -->
<form method="post" action="<?php echo $baseUrl; ?>" id="eventi-filtro-reset" class="d-none">
    <?php if ($itemId) : ?>
        <input type="hidden" name="Itemid" value="<?php echo $itemId; ?>">
    <?php endif; ?>
    <input type="hidden" name="filter[search]" value="">
    <input type="hidden" name="list[start-date]" value="">
    <input type="hidden" name="list[end-date]" value="">
</form>
