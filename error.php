<?php
/**
 * @package     Joomla.Site
 * @subpackage  Template.accessibile
 *
 * Pagina di Errore personalizzata (404, 500, ecc.)
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Language\Text;

$app       = Factory::getApplication();
$doc       = $app->getDocument();
$template  = $app->getTemplate();
$params    = $app->getTemplate(true)->params;

// Gestione Errori
$errorCode = $this->error->getCode();
$errorMessage = $this->error->getMessage();
if ($errorCode == '404') {
    $errorMessage = 'Not Found';
}

$colore    = $params->get('coloreprimario', '#0066CC');
$baseurl   = Uri::base();
$logo      = $params->get('logotipo');
$logoUrl   = '';

// Costruzione lista social (serve per header e footer)
$socialX   = $params->get('socialx');
$facebook  = $params->get('facebook');
$youtube   = $params->get('youtube');
$telegram  = $params->get('telegram');
$whatsapp  = $params->get('whatsapp');
$socialLinks = [];
if ($socialX)  $socialLinks[] = ['url' => $socialX, 'icon' => 'it-twitter', 'label' => 'X (Twitter)'];
if ($facebook) $socialLinks[] = ['url' => $facebook, 'icon' => 'it-facebook', 'label' => 'Facebook'];
if ($youtube)  $socialLinks[] = ['url' => $youtube, 'icon' => 'it-youtube', 'label' => 'YouTube'];
if ($telegram) $socialLinks[] = ['url' => $telegram, 'icon' => 'it-telegram', 'label' => 'Telegram'];
if ($whatsapp) $socialLinks[] = ['url' => 'https://wa.me/' . preg_replace('/[^0-9]/', '', $whatsapp), 'icon' => 'it-whatsapp', 'label' => 'Whatsapp'];

if (!empty($logo)) {
  $logoData = HTMLHelper::cleanImageURL($logo);
  $logoUrl = $this->baseurl . '/' . ltrim($logoData->url, '/');
}

// INSERIMENTO ASSET E FONT-AWESOME
$wa = $this->getWebAssetManager();
$tplPath = 'templates/' . $this->template;

if ($wa->assetExists('style', 'fontawesome')) {
    $wa->useStyle('fontawesome');
} else {
    $wa->registerAndUseStyle('fa-base', 'media/vendor/fontawesome-free/css/fontawesome.min.css');
}

$wa->registerAndUseStyle('template.styles', $tplPath . '/css/bootstrap-italia.min.css')
   ->registerAndUseStyle('template.comuni', $tplPath . '/css/bootstrap-italia-comuni.css', [], ['template.styles'])
   ->registerAndUseStyle('template.fonts', $tplPath . '/css/fonts.css')
   ->registerAndUseScript('template.scripts', $tplPath . '/js/bootstrap-italia.bundle.min.js', [], ['defer' => true]);

// INIEZIONE VARIABILI CSS DINAMICHE
$hex = ltrim($colore, '#');
$r = hexdec(substr($hex, 0, 2));
$g = hexdec(substr($hex, 2, 2));
$b = hexdec(substr($hex, 4, 2));

$inlineCss = ":root {
  --bs-primary: {$colore} !important;
  --bs-link-color: {$colore} !important;
  --bs-link-hover-color: color-mix(in srgb, {$colore} 85%, black) !important;
  --bs-success: {$colore} !important;
  --bs-info: {$colore} !important;
  --bs-btn-color: {$colore} !important;
  --bs-btn-hover-color: color-mix(in srgb, {$colore} 85%, black) !important;
  --bs-btn-active-color: color-mix(in srgb, {$colore} 70%, black) !important;
  --bs-primary-rgb: {$r}, {$g}, {$b} !important;
  --bs-success-rgb: {$r}, {$g}, {$b} !important;
  --bs-info-rgb: {$r}, {$g}, {$b} !important;
}
.it-header-slim-wrapper {
  background-color: color-mix(in srgb, var(--bs-primary) 75%, black) !important;
}";
$wa->addInlineStyle($inlineCss);

?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <jdoc:include type="head" />
  </head>
  <body>
    
    <header class="it-header-wrapper" data-bs-target="#header-nav-wrapper">
        <div class="it-header-slim-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="it-header-slim-wrapper-content">
                            <?php
                            $nomeRegione = $params->get('nomeregione');
                            $linkRegione = $params->get('linkregione');
                            if (!empty($nomeRegione)) :
                                if (!empty($linkRegione)) : ?>
                                    <a class="d-lg-block navbar-brand" target="_blank" href="<?php echo htmlspecialchars($linkRegione, ENT_QUOTES, 'UTF-8'); ?>" aria-label="Vai al portale <?php echo htmlspecialchars($nomeRegione); ?> - link esterno - apertura nuova scheda" title="Vai al portale <?php echo htmlspecialchars($nomeRegione); ?>">
                                       <?php echo htmlspecialchars($nomeRegione); ?>
                                    </a>
                                <?php else : ?>
                                    <span class="d-lg-block navbar-brand"><?php echo htmlspecialchars($nomeRegione); ?></span>
                                <?php endif;
                            endif; ?>
                            
                            <div class="it-header-slim-right-zone" role="navigation">
                                <jdoc:include type="modules" name="selezione-lingua" style="none" />
                                <?php
                                $mostraLogin = $params->get('mostra_login', 0);
                                if ($mostraLogin == 1) :
                                    $user = Factory::getUser();
                                    $loginText = $user->guest ? 'Accedi all\'area personale' : 'Area personale';
                                    // Semplificato per la pagina di errore
                                    $loginUrl = $this->baseurl . '/index.php?option=com_users&view=login';
                                ?>
                                <a class="btn btn-primary btn-icon btn-full" href="<?php echo $loginUrl; ?>" data-element="personal-area-login">
                                    <span class="rounded-icon" aria-hidden="true">
                                        <svg class="icon icon-primary"><use xlink:href="<?= $this->baseurl ?>/templates/<?= $this->template ?>/svg/sprites.svg#it-user"></use></svg>
                                    </span>
                                    <span class="d-none d-lg-block"><?php echo $loginText; ?></span>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="it-nav-wrapper">
            <div class="it-header-center-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="it-header-center-content-wrapper">
                                <div class="it-brand-wrapper">
                                    <a href="<?php echo $this->baseurl; ?>">
                                        <?php if ($logoUrl) : ?>
                                            <svg width="82" height="82" class="icon" aria-hidden="true">
                                                <image xlink:href="<?php echo htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8'); ?>" />
                                            </svg>
                                        <?php endif; ?>
                                        <div class="it-brand-text">
                                            <div class="it-brand-title"><?php echo htmlspecialchars($params->get('nomesito', 'Il mio Comune')); ?></div>
                                            <div class="it-brand-tagline d-none d-md-block"><?php echo htmlspecialchars($params->get('payoff', 'Un comune da vivere')); ?></div>
                                        </div>
                                    </a>
                                </div>
                                <div class="it-right-zone">
                                    <?php if (!empty($socialLinks)) : ?>
                                        <div class="it-socials d-none d-lg-flex">
                                            <span>Seguici su</span>
                                            <ul>
                                                <?php foreach ($socialLinks as $social) : ?>
                                                    <li>
                                                        <a href="<?php echo htmlspecialchars($social['url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">
                                                            <svg class="icon icon-sm icon-white align-top"><use xlink:href="<?= $this->baseurl ?>/templates/<?= $this->template ?>/svg/sprites.svg#<?php echo $social['icon']; ?>"></use></svg>
                                                            <span class="visually-hidden"><?php echo $social['label']; ?></span>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="it-header-navbar-wrapper" id="header-nav-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="navbar navbar-expand-lg has-megamenu">
                                <button class="custom-navbar-toggler" type="button" aria-controls="nav4" aria-expanded="false" aria-label="Mostra/Nascondi la navigazione" data-bs-target="#nav4" data-bs-toggle="navbarcollapsible">
                                    <svg class="icon"><use href="<?= $this->baseurl ?>/templates/<?= $this->template ?>/svg/sprites.svg#it-burger"></use></svg>
                                </button>
                                <div class="navbar-collapsable" id="nav4">
                                    <div class="overlay" style="display: none;"></div>
                                    <div class="close-div">
                                        <button class="btn close-menu" type="button">
                                            <span class="visually-hidden">Nascondi la navigazione</span>
                                            <svg class="icon"><use href="<?= $this->baseurl ?>/templates/<?= $this->template ?>/svg/sprites.svg#it-close-big"></use></svg>
                                        </button>
                                    </div>
                                    <div class="menu-wrapper">
                                        <jdoc:include type="modules" name="menu-principale" />
                                        <nav aria-label="Secondaria">
                                            <jdoc:include type="modules" name="menu-secondario" />
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main id="main" class="main-section pb-5">
        <div class="container mt-4 mb-5" id="main-container">
            <div class="row">
                <div class="col-12">
                    <nav class="breadcrumb-container mb-4" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="<?php echo $this->baseurl; ?>/"><?php echo Text::_('TPL_ACCESSIBILE_HOME'); ?></a><span class="separator">/</span>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <?php echo $errorCode; ?> <?php echo $errorMessage; ?>
                            </li>
                        </ol>
                    </nav>

                    <h1 class="display-1 fw-bold mb-4"><?php echo $errorCode; ?></h1>
                    <p class="lead mb-5">
                        <?php echo Text::_('TPL_ACCESSIBILE_ERROR_NOT_FOUND'); ?><br>
                        <a href="javascript:history.back()"><?php echo Text::_('TPL_ACCESSIBILE_GO_BACK'); ?></a> <?php echo Text::_('TPL_ACCESSIBILE_ERROR_NAVIGATE'); ?>
                    </p>
                </div>
            </div>
        </div>
    </main>

    <footer class="it-footer" id="footer">
        <div class="it-footer-main">
            <div class="container">
                <div class="row">
                    <div class="col-12 footer-items-wrapper logo-wrapper">
                        <img class="ue-logo" src="<?= $this->baseurl ?>/templates/<?= $this->template ?>/images/logo-eu-inverted.svg" alt="logo Unione Europea">
                        <div class="it-brand-wrapper">
                            <a href="<?php echo $this->baseurl; ?>">
                                <?php if ($logoUrl) : ?>
                                    <svg class="icon" aria-hidden="true"><image xlink:href="<?php echo htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8'); ?>" /></svg>
                                <?php endif; ?>
                                <div class="it-brand-text">
                                    <div class="it-brand-title"><?php echo htmlspecialchars($params->get('nomesito', 'Il mio Comune')); ?></div>
                                    <div class="it-brand-tagline d-none d-md-block"><?php echo htmlspecialchars($params->get('payoff', 'Un comune da vivere')); ?></div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <?php if ($this->countModules('footer1')) : ?>
                    <div class="row footer1"><jdoc:include type="modules" name="footer1" style="html5" /></div>
                <?php endif; ?>
                <?php if ($this->countModules('footer2')) : ?>
                    <div class="row footer2"><jdoc:include type="modules" name="footer2" style="html5" /></div>
                <?php endif; ?>
            </div>
        </div>
    </footer>

  </body>
</html>