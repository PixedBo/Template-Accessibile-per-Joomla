<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * Layout alternativo "Scheda Servizio" per articolo singolo.
 * Implementa il criterio C.SI.1.3 del Modello Comuni (Designers Italia).
 *
 * Ogni sezione viene popolata da un Custom Field selezionato nei parametri
 * del template (fieldset "valutazione", blocco "Scheda Servizio").
 */

defined('_JEXEC') or die;

use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\MediaHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

/** @var \Joomla\Component\Content\Site\View\Article\HtmlView $this */

$app      = Factory::getApplication();
$app->getLanguage()->load('tpl_templateaccessibileperjoomla', JPATH_SITE);

$_parentTpl = \Joomla\CMS\Factory::getApplication()->getTemplate(true)->parent
    ?: \Joomla\CMS\Factory::getApplication()->getTemplate();
require_once JPATH_SITE . '/templates/' . $_parentTpl . '/helpers/TplAccessibileHelper.php';
$document = $app->getDocument();

// Click sulle voci dell'indice di pagina: vedi js/page-index.js
$document->getWebAssetManager()->registerAndUseScript(
    'template.page-index',
    TplAccessibileHelper::mediaUrl('js/page-index.js'),
    [],
    ['defer' => true]
);
$params   = $this->item->params;
$canEdit  = $params->get('access-edit');

$tplParams = $app->getTemplate(true)->params;
$jcfields  = $this->item->jcfields ?? [];

$cf = function (string $param) use ($tplParams, $jcfields): ?object {
    $id = (int) $tplParams->get($param, 0);
    return ($id && isset($jcfields[$id])) ? $jcfields[$id] : null;
};
$cfVal = function (string $param) use ($cf): string {
    $f = $cf($param);
    return $f ? (string) $f->value : '';
};
$cfRaw = function (string $param) use ($cf): string {
    $f = $cf($param);
    return $f ? (string) $f->rawvalue : '';
};
// Estrae URL e testo del link dal rawvalue di un custom field. Formati supportati
// (verificati nel sorgente di Joomla, plg_fields_media + AccessiblemediaField):
// - campo URL (Joomla 5.x / 6.0): stringa semplice, assoluta o relativa alla root;
// - campo Media "Documento" (media_document, Joomla 6.1+): JSON {"file": "...", "linktext": "..."};
// - campo Media immagine: JSON {"imagefile": "...", "alt_text": "..."}.
// Il suffisso "#joomlaImage://..." aggiunto dal Media Manager viene rimosso con
// MediaHelper::getCleanMediaFieldValue() (disponibile da Joomla 4.0): taglia dal primo "#",
// a differenza di HTMLHelper::cleanImageURL() che lo lascia se non c'è un "?".
$cfFile = function (string $param) use ($cfRaw): array {
    $info = ['url' => '', 'text' => ''];
    $raw  = trim($cfRaw($param));
    if ($raw === '') {
        return $info;
    }
    $url  = $raw;
    $data = json_decode($raw, true);
    if (is_array($data)) {
        $url          = (string) ($data['file'] ?? $data['imagefile'] ?? '');
        $info['text'] = trim((string) ($data['linktext'] ?? ''));
    }
    $url = MediaHelper::getCleanMediaFieldValue($url);
    if ($url === '') {
        return $info;
    }
    if (preg_match('#^[a-z][a-z0-9+.-]*:#i', $url)) {
        // URL con schema: ammessi solo quelli sicuri. Gli altri (javascript:, data:, ...) vengono
        // scartati perché il valore finisce in href e in location.href.
        if (!preg_match('#^(?:https?://|mailto:|tel:)#i', $url)) {
            return $info;
        }
    } elseif (preg_match('#^/[/\\\\]#', $url)) {
        // "//host" e "/\host" non sono percorsi del sito: il browser li apre su un altro dominio
        return $info;
    } elseif ($url[0] !== '/') {
        // Percorsi relativi (es. "files/doc.pdf") resi assoluti rispetto alla root del sito
        $url = Uri::root() . ltrim($url, '/');
    }
    $info['url'] = $url;
    return $info;
};
$cfUrl = fn (string $param): string => $cfFile($param)['url'];
// URL per un gestore onclick: prima stringa JavaScript (json_encode), poi escape per l'attributo HTML.
// Il solo escape HTML non basta: le entità vengono decodificate prima che il JS sia eseguito.
$jsUrl = fn (string $url): string => htmlspecialchars(json_encode($url, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG), ENT_QUOTES, 'UTF-8');

// Con "Mostra link non autorizzati" attivo la view non blocca chi non ha accesso:
// tocca al layout mostrare solo titolo e introduzione (vedi HtmlView di com_content).
$canView = (bool) $params->get('access-view');

$menuItemContatti    = (int) $tplParams->get('menuitem_contatti', 0);
$menuItemArea        = (int) $tplParams->get('menuitem_contatti_area', 0);
$urlContatti         = $menuItemContatti ? Route::_('index.php?Itemid=' . $menuItemContatti) : '';

$menu         = $app->getMenu();
$menuAreaObj  = $menuItemArea ? $menu->getItem($menuItemArea) : null;
$urlArea      = $menuAreaObj  ? Route::_('index.php?Itemid=' . $menuItemArea) : '';
$titleArea    = $menuAreaObj  ? $menuAreaObj->title : '';

$hasRightColumn = $document->countModules('colonna-destra') > 0;
$centerColClass = $hasRightColumn ? 'col-lg-6' : 'col-lg-9';

// JSON-LD GovernmentService
$jsonLd = json_encode([
    '@context'    => 'https://schema.org',
    '@type'       => 'GovernmentService',
    'name'        => $this->item->title,
    'description' => strip_tags($this->item->introtext ?? ''),
    'serviceType' => $this->item->category_title ?? '',
    'areaServed'  => $tplParams->get('nomesito', ''),
    'url'         => Uri::current(),
// JSON_HEX_TAG/AMP: un "</script>" nel titolo o nel nome del sito non deve chiudere il tag
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP);
$document->addCustomTag('<script type="application/ld+json" data-element="metatag">' . $jsonLd . '</script>');
?>

<div class="container pt-4">
    <div class="row">
        <div class="col-lg-8 px-lg-4 py-lg-2 mb-3">

            <h1 class="mb-2" data-element="service-title">
                <?php echo $this->escape($this->item->title); ?>
            </h1>

			<?php
			$stato_raw = $canView ? $cfRaw('cf_stato') : '';
			$stato     = $canView ? $cfVal('cf_stato') : '';
			$isNo = (string)$stato_raw === '0';
			$motivazioni = $cfVal('cf_motivo_stato_servizio');

			if ($stato) : 
			?>
				<div data-element="service-status" class="mb-3">
					<span class="badge rounded-pill <?php echo $isNo ? 'bg-danger' : 'bg-success'; ?>">
						<?php echo $stato; ?>
					</span>
				</div>
				<?php if ($isNo && $motivazioni) : ?>
					<div data-element="service-reasons" class="mb-3 alert alert-warning">
						<?php echo nl2br(htmlspecialchars($motivazioni)); ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>

            <?php if ($this->item->introtext) : ?>
                <?php // <div>, non <p>: content.prepare restituisce già paragrafi e un <p> annidato è HTML invalido ?>
                <div class="lead mb-0" data-element="service-description">
                    <?php echo HTMLHelper::_('content.prepare', $this->item->introtext); ?>
                </div>
            <?php endif; ?>

            <?php
                $urlOnlineHeader   = $canView ? $cfUrl('cf_accedi_online') : '';
                $testoOnlineHeader = $cfVal('cf_accedi_online_testo');
                if ($urlOnlineHeader) :
            ?>
                <div class="mt-4">
                    <button type="button"
                            class="btn btn-primary mobile-full"
                            onclick="location.href=<?php echo $jsUrl($urlOnlineHeader); ?>;"
                            data-focus-mouse="false">
                        <span><?php echo $testoOnlineHeader ?: Text::_('TPL_ACCESSIBILE_SERVICE_ONLINE_ACCESS'); ?></span>
                    </button>
                </div>
            <?php endif; ?>

        </div>

        <div class="col-lg-3 offset-lg-1 header-laterale">

            <?php // Condividi + Vedi azioni (partial condiviso) ?>
            <?php echo LayoutHelper::render('accessibile.azioni-pagina', [
                'title'    => $this->item->title,
                'editHtml' => $canEdit ? LayoutHelper::render('joomla.content.icons', ['params' => $params, 'item' => $this->item]) : '',
            ]); ?>

            <?php // Argomenti con data-element="service-topic" (partial condiviso) ?>
            <?php if ($params->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
                <?php echo LayoutHelper::render('accessibile.argomenti', [
                    'tags'        => $this->item->tags->itemTags,
                    'dataElement' => 'service-topic',
                ]); ?>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php if (!$canView) : ?>
<div class="container">
    <div class="alert alert-warning mt-4" role="alert">
        <?php echo Text::_('JERROR_ALERTNOAUTHOR'); ?>
        <?php if ($app->getIdentity()->guest) : ?>
            <a href="<?php echo Route::_('index.php?option=com_users&view=login&return=' . base64_encode(Uri::getInstance()->toString())); ?>"><?php echo Text::_('JLOGIN'); ?></a>
        <?php endif; ?>
    </div>
</div>
<?php else : ?>
<div class="container">
    <div class="row border-top border-light row-column-border row-column-menu-left mt-4 mt-lg-80 pb-lg-80 pb-40">

        <?php // Sidebar sinistra — indice di pagina ?>
        <aside class="col-lg-3 mb-4 border-col">
            <div class="cmp-navscroll sticky-top" role="region" aria-labelledby="accordion-title-servizio">
                <nav class="navbar it-navscroll-wrapper navbar-expand-lg"
                     aria-label="<?php echo Text::_('TPL_ACCESSIBILE_PAGE_INDEX'); ?>"
                     data-bs-navscroll="">
                    <div class="navbar-custom" id="navbarNavServizio">
                        <div class="menu-wrapper">
                            <div class="link-list-wrapper">
                                <div class="accordion">
                                    <div class="accordion-item">
                                        <span class="accordion-header" id="accordion-title-servizio">
                                            <button class="accordion-button pb-10 px-3 text-uppercase" type="button"
                                                    aria-controls="collapse-servizio" aria-expanded="true"
                                                    data-bs-toggle="collapse" data-bs-target="#collapse-servizio">
                                                <?php echo Text::_('TPL_ACCESSIBILE_PAGE_INDEX'); ?>
                                            </button>
                                        </span>
                                        <div class="progress">
                                            <div class="progress-bar it-navscroll-progressbar" role="progressbar"
                                                 aria-label="<?php echo htmlspecialchars(Text::_('TPL_ACCESSIBILE_READING_PROGRESS'), ENT_QUOTES, 'UTF-8'); ?>"
                                                 aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                                 style="width: 0%;"></div>
                                        </div>
                                        <div id="collapse-servizio" class="accordion-collapse collapse show"
                                             role="region" aria-labelledby="accordion-title-servizio">
                                            <div class="accordion-body">
                                                <ul class="link-list" data-element="page-index">
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="#who-needs">
                                                            <span><?php echo Text::_('TPL_ACCESSIBILE_SERVICE_WHO_NEEDS'); ?></span>
                                                        </a>
                                                    </li>
                                                    <?php if (!empty($this->item->fulltext)) : ?>
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="#description">
                                                            <span><?php echo Text::_('TPL_ACCESSIBILE_SERVICE_DESCRIPTION'); ?></span>
                                                        </a>
                                                    </li>
                                                    <?php endif; ?>
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="#how-to">
                                                            <span><?php echo Text::_('TPL_ACCESSIBILE_SERVICE_HOW_TO'); ?></span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="#needed">
                                                            <span><?php echo Text::_('TPL_ACCESSIBILE_SERVICE_NEEDED'); ?></span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="#obtain">
                                                            <span><?php echo Text::_('TPL_ACCESSIBILE_SERVICE_ACHIEVED'); ?></span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="#deadlines">
                                                            <span><?php echo Text::_('TPL_ACCESSIBILE_SERVICE_DEADLINES'); ?></span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="#submit-request">
                                                            <span><?php echo Text::_('TPL_ACCESSIBILE_SERVICE_ACCESS'); ?></span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="#conditions">
                                                            <span><?php echo Text::_('TPL_ACCESSIBILE_SERVICE_CONDITIONS'); ?></span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" href="#contacts">
                                                            <span><?php echo Text::_('TPL_ACCESSIBILE_SERVICE_CONTACTS'); ?></span>
                                                        </a>
                                                    </li>
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

        <?php // Contenuto principale — sezioni in ordine fisso richiesto dal validatore ?>
        <div class="<?php echo $centerColClass; ?> it-page-sections-container border-light">

            <section class="it-page-section anchor-offset mb-30" id="who-needs">
                <h2 class="mb-3"><?php echo Text::_('TPL_ACCESSIBILE_SERVICE_WHO_NEEDS'); ?></h2>
                <div class="richtext-wrapper lora" data-element="service-addressed">
                    <?php echo $cfVal('cf_a_chi_rivolto'); ?>
                </div>
            </section>

            <?php if (!empty($this->item->fulltext)) : ?>
            <section class="it-page-section anchor-offset mb-30" id="description">
                <h2 class="mb-3"><?php echo Text::_('TPL_ACCESSIBILE_SERVICE_DESCRIPTION'); ?></h2>
                <div class="richtext-wrapper lora" data-element="service-extended-description">
                    <?php echo HTMLHelper::_('content.prepare', $this->item->fulltext, '', 'com_content.article'); ?>
                </div>
            </section>
            <?php endif; ?>

            <section class="it-page-section anchor-offset mb-30" id="how-to">
                <h2 class="mb-3"><?php echo Text::_('TPL_ACCESSIBILE_SERVICE_HOW_TO'); ?></h2>
                <div class="richtext-wrapper lora" data-element="service-how-to">
                    <?php echo $cfVal('cf_come_fare'); ?>
                </div>
            </section>

            <section class="it-page-section anchor-offset mb-30 has-bg-grey p-3" id="needed">
                <h2 class="mb-3"><?php echo Text::_('TPL_ACCESSIBILE_SERVICE_NEEDED'); ?></h2>
                <div class="richtext-wrapper lora" data-element="service-needed">
                    <?php echo $cfVal('cf_cosa_serve'); ?>
                </div>
            </section>

            <section class="it-page-section anchor-offset mb-30" id="obtain">
                <h2 class="mb-3"><?php echo Text::_('TPL_ACCESSIBILE_SERVICE_ACHIEVED'); ?></h2>
                <div class="richtext-wrapper lora" data-element="service-achieved">
                    <?php echo $cfVal('cf_cosa_si_ottiene'); ?>
                </div>
            </section>

            <section class="it-page-section anchor-offset mb-30" id="deadlines">
                <h2 class="mb-3"><?php echo Text::_('TPL_ACCESSIBILE_SERVICE_DEADLINES'); ?></h2>

                <?php $tempi = $cfVal('cf_tempi_scadenze'); if ($tempi) : ?>
                    <div class="richtext-wrapper lora mb-4" data-element="service-calendar-text">
                        <?php echo $tempi; ?>
                    </div>
                <?php endif; ?>

                <?php
                    $kTitolo      = (string) (int) $tplParams->get('cf_tempi_lista_key_titolo', 0);
                    $kData        = (string) (int) $tplParams->get('cf_tempi_lista_key_data', 0);
                    $kNumero      = (string) (int) $tplParams->get('cf_tempi_lista_key_numero', 0);
                    $kUnita       = (string) (int) $tplParams->get('cf_tempi_lista_key_unita', 0);
                    $kDescrizione = (string) (int) $tplParams->get('cf_tempi_lista_key_descrizione', 0);
                    $listaField  = $cf('cf_tempi_scadenze_lista');
                    $calendarItems = [];
                    if ($listaField && $listaField->rawvalue) {
                        $decoded = json_decode($listaField->rawvalue, true);
                        if (is_array($decoded)) {
                            $calendarItems = array_values($decoded);
                        }
                    }
                ?>
                <?php if ($calendarItems) : ?>
                    <div class="calendar-vertical mb-3" data-element="service-calendar-list">
                        <?php foreach ($calendarItems as $item) : ?>
                            <?php
                                $fTitolo      = 'field' . $kTitolo;
                                $fData        = 'field' . $kData;
                                $fNumero      = 'field' . $kNumero;
                                $fUnita       = 'field' . $kUnita;
                                $fDescrizione = 'field' . $kDescrizione;
                                $isDate = $kData && !empty($item[$fData]);
                                $anno = $giorno = $mese = '';
                                if ($isDate) {
                                    try {
                                        // Data "pura" (senza orario): la si legge e formatta in UTC per
                                        // evitare slittamenti di giorno. 'M' con translate=true restituisce
                                        // il mese abbreviato nella lingua del sito (costanti core *_SHORT).
                                        $dt     = new Date($item[$fData], new \DateTimeZone('UTC'));
                                        $anno   = $dt->format('Y', false, false);
                                        $giorno = $dt->format('d', false, false);
                                        $mese   = $dt->format('M', false, true);
                                    } catch (\Exception $e) {
                                        $isDate = false;
                                    }
                                }
                            ?>
                            <div class="calendar-date">
                                <div class="calendar-date-day">
                                    <?php if ($isDate) : ?>
                                        <small class="calendar-date-day__year"><?php echo $anno; ?></small>
                                        <span class="title-xxlarge-regular d-flex justify-content-center"><?php echo $giorno; ?></span>
                                        <small class="calendar-date-day__month"><?php echo $mese; ?></small>
                                    <?php else : ?>
                                        <span class="title-xxlarge-regular d-flex justify-content-center"><?php echo $kNumero ? $this->escape($item[$fNumero] ?? '') : ''; ?></span>
                                        <small class="calendar-date-day__month"><?php echo $kUnita ? $this->escape($item[$fUnita] ?? '') : ''; ?></small>
                                    <?php endif; ?>
                                </div>
                                <div class="calendar-date-description rounded">
                                    <div class="calendar-date-description-content">
                                        <h3 class="title-medium-2 mb-0"><?php echo $kTitolo ? $this->escape($item[$fTitolo] ?? '') : ''; ?></h3>
                                        <?php if ($kDescrizione && !empty($item[$fDescrizione])) : ?>
                                            <div class="info-text mt-1 mb-0"><?php echo nl2br($this->escape($item[$fDescrizione])); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </section>

            <?php $costo = $cfVal('cf_quanto_costa'); if ($costo) : ?>
                <section class="it-page-section anchor-offset mb-30" id="costs">
                    <h2 class="mb-3"><?php echo Text::_('TPL_ACCESSIBILE_SERVICE_COST'); ?></h2>
                    <div class="richtext-wrapper lora" data-element="service-cost">
                        <?php echo $costo; ?>
                    </div>
                </section>
            <?php endif; ?>

            <section class="it-page-section anchor-offset mb-30 has-bg-grey p-4">
                <h2 class="mb-3" id="submit-request"><?php echo Text::_('TPL_ACCESSIBILE_SERVICE_ACCESS'); ?></h2>
                <p class="text-paragraph lora mb-4" data-element="service-generic-access">
                    <?php echo Text::_('TPL_ACCESSIBILE_SERVICE_ONLINE_ACCESS_INTRO'); ?>
                </p>
                <?php $urlOnline = $cfUrl('cf_accedi_online'); if ($urlOnline) : ?>
                    <?php $testoOnline = $cfVal('cf_accedi_online_testo'); ?>
                    <button type="button"
                            class="btn btn-primary mobile-full me-3 mt-2"
                            onclick="location.href=<?php echo $jsUrl($urlOnline); ?>;"
                            data-element="service-online-access"
                            data-focus-mouse="false">
                        <span><?php echo $testoOnline ?: Text::_('TPL_ACCESSIBILE_SERVICE_ONLINE_ACCESS'); ?></span>
                    </button>
                <?php endif; ?>
                <?php $urlAppuntamento = $cfUrl('cf_accedi_appuntamento'); if ($urlAppuntamento) : ?>
                    <p class="text-paragraph lora mt-4" data-element="service-generic-access">
                        <?php echo Text::_('TPL_ACCESSIBILE_SERVICE_BOOK_APPOINTMENT_INTRO'); ?>
                    </p>
                <?php endif; ?>
                <?php if ($urlAppuntamento) : ?>
                    <button type="button"
                            class="btn btn-outline-primary bg-white t-primary mobile-full mt-2"
                            onclick="location.href=<?php echo $jsUrl($urlAppuntamento); ?>;"
                            data-element="service-booking-access"
                            data-focus-mouse="false">
                        <span><?php echo Text::_('TPL_ACCESSIBILE_SERVICE_BOOK_APPOINTMENT'); ?></span>
                    </button>
                <?php endif; ?>
            </section>

            <section class="it-page-section anchor-offset mb-30" id="conditions">
                <h2 class="mb-3"><?php echo Text::_('TPL_ACCESSIBILE_SERVICE_CONDITIONS'); ?></h2>
                <?php $condizioni = $cfVal('cf_condizioni'); if ($condizioni) : ?>
                    <div class="richtext-wrapper lora mb-3">
                        <?php echo $condizioni; ?>
                    </div>
                <?php endif; ?>
                <?php
                    $fileCondizioni = $cfFile('cf_condizioni_file');
                    if ($fileCondizioni['url']) :
                        // Testo del link: quello impostato nel campo Documento (Joomla 6.1+), con il
                        // segnaposto {filename} come nel plugin core, altrimenti la stringa del template.
                        $testoFile = $fileCondizioni['text'] ?: Text::_('TPL_ACCESSIBILE_SERVICE_CONDITIONS_FILE');
                        $testoFile = str_replace('{filename}', basename(parse_url($fileCondizioni['url'], PHP_URL_PATH) ?: ''), $testoFile);
                ?>
                    <a href="<?php echo $this->escape($fileCondizioni['url']); ?>"
                       data-element="service-file"
                       class="d-inline-flex align-items-center gap-2">
                        <svg class="icon icon-sm" aria-hidden="true">
                            <use href="<?php echo TplAccessibileHelper::spriteUrl('it-clip'); ?>"></use>
                        </svg>
                        <?php echo $this->escape($testoFile); ?>
                    </a>
                <?php endif; ?>
            </section>

            <section class="it-page-section anchor-offset" id="contacts">
                <h2 class="mb-3"><?php echo Text::_('TPL_ACCESSIBILE_SERVICE_CONTACTS'); ?></h2>

                <?php if ($urlArea) : ?>
                    <div data-element="service-area" class="card-wrapper rounded mb-4">
                        <div class="card card-teaser card-teaser-info rounded shadow-sm p-3">
                            <div class="card-body">
                                <a href="<?php echo $urlArea; ?>"
                                   class="d-inline-flex align-items-center gap-2 fw-semibold">
                                    <svg class="icon icon-sm icon-primary" aria-hidden="true">
                                        <use href="<?php echo TplAccessibileHelper::spriteUrl('it-pa'); ?>"></use>
                                    </svg>
                                    <?php echo $this->escape($titleArea); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php // Data ultimo aggiornamento ?>
                <?php if ($params->get('show_modify_date') && !empty($this->item->modified)) : ?>
                    <p class="text-paragraph-small text-muted">
                        <?php echo Text::_('TPL_ACCESSIBILE_SERVICE_UPDATED'); ?>
                        <?php echo HTMLHelper::_('date', $this->item->modified, Text::_('DATE_FORMAT_LC3')); ?>
                    </p>
                <?php endif; ?>

                <?php // Blocco "Contatta il Comune" ?>
                <?php $urlAppointmentBooking = $cfUrl('cf_accedi_appuntamento'); ?>
                <?php if ($urlContatti || $urlAppointmentBooking) : ?>
                    <div class="mt-4 pt-4 border-top">
                        <h3 class="h4"><?php echo Text::_('TPL_ACCESSIBILE_SERVICE_MORE_INFO'); ?></h3>
                        <ul class="list-unstyled mt-3">
                            <?php if ($urlContatti) : ?>
                                <li class="mb-2">
                                    <a href="<?php echo $urlContatti; ?>" data-element="contacts"
                                       class="d-inline-flex align-items-center gap-2">
                                        <svg class="icon icon-sm icon-primary" aria-hidden="true">
                                            <use href="<?php echo TplAccessibileHelper::spriteUrl('it-mail'); ?>"></use>
                                        </svg>
                                        <?php echo Text::_('TPL_ACCESSIBILE_SERVICE_CONTACT_LINK'); ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if ($urlAppointmentBooking) : ?>
                                <li>
                                    <a href="<?php echo $this->escape($urlAppointmentBooking); ?>"
                                       data-element="appointment-booking"
                                       class="d-inline-flex align-items-center gap-2">
                                        <svg class="icon icon-sm icon-primary" aria-hidden="true">
                                            <use href="<?php echo TplAccessibileHelper::spriteUrl('it-calendar'); ?>"></use>
                                        </svg>
                                        <?php echo Text::_('TPL_ACCESSIBILE_SERVICE_APPOINTMENT_LINK'); ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?>

            </section>

        </div>

        <?php // Sidebar destra (solo se ci sono moduli) ?>
        <?php if ($hasRightColumn) : ?>
            <aside class="col-lg-3">
                <?php echo HTMLHelper::_('content.prepare', '{loadposition colonna-destra}'); ?>
            </aside>
        <?php endif; ?>

    </div>
</div>
<?php endif; ?>


