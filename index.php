<?php

/**
 * @package     Joomla Template Accessibile per la PA
 * @subpackage  Templates.it_accessible
 * @copyright   Copyright (C) 2025 Pixed Web Development. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @link        https://www.pixed.it
 * 
 * This template is free software. You can redistribute it and/or modify it
 * under the terms of the GNU General Public License version 3 as published
 * by the Free Software Foundation.
 * 
 * This template is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;

$app       = Factory::getApplication();
$doc       = $app->getDocument();
$template  = $app->getTemplate();
$params    = $app->getTemplate(true)->params;

$colore    = $params->get('coloreprimario', '#0066CC');

// Mappiamo i colori esatti del tuo XML ai nomi dei file immagine
$mappaSfondi = [
    '#0066CC' => 'blu-default.jpg',
    '#007a52' => 'verde-comuni.png',
    '#d1344c' => 'rosso-scuola.jpg',
    '#07768d' => 'verde-acqua-asl.jpg',
    '#7d2670' => 'viola-musei.jpg'
];

// Se il colore scelto non è in mappa, usiamo il blu di default come fallback
$sfondoScelto = $mappaSfondi[$colore] ?? 'blu-default.jpg';
$urlSfondoEvidenza = $this->baseurl . '/templates/' . $this->template . '/images/' . $sfondoScelto;

$baseurl   = Uri::base();
$logo = $this->params->get('logotipo');
$logoUrl = '';

// Recupero parametri dei social dal template
$socialX   = $this->params->get('socialx');
$facebook  = $this->params->get('facebook');
$youtube   = $this->params->get('youtube');
$telegram  = $this->params->get('telegram');
$whatsapp  = $this->params->get('whatsapp');

// Costruzione lista social
$socialLinks = [];

if ($socialX) {
    $socialLinks[] = [
        'url' => $socialX,
        'icon' => 'it-twitter',
        'label' => 'X (Twitter)'
    ];
}
if ($facebook) {
    $socialLinks[] = [
        'url' => $facebook,
        'icon' => 'it-facebook',
        'label' => 'Facebook'
    ];
}
if ($youtube) {
    $socialLinks[] = [
        'url' => $youtube,
        'icon' => 'it-youtube',
        'label' => 'YouTube'
    ];
}
if ($telegram) {
    $socialLinks[] = [
        'url' => $telegram,
        'icon' => 'it-telegram',
        'label' => 'Telegram'
    ];
}
if ($whatsapp) {
    // Rende il link cliccabile via WhatsApp
    $url = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $whatsapp);
    $socialLinks[] = [
        'url' => $url,
        'icon' => 'it-whatsapp',
        'label' => 'Whatsapp'
    ];
}

if (!empty($logo)) {
  $logoData = HTMLHelper::cleanImageURL($logo);
  $logoUrl = $this->baseurl . '/' . ltrim($logoData->url, '/'); // ✅ URL assoluto corretto
}

// INSERIMENTO ASSET E FONT-AWESOME
$wa = $this->getWebAssetManager();

// Costruiamo il percorso corretto alla cartella del template
$tplPath = 'templates/' . $this->template;

// FontAwesome
if ($wa->assetExists('style', 'fontawesome')) {
    $wa->useStyle('fontawesome');
} else {
    $wa->registerAndUseStyle('fa-base', 'media/vendor/fontawesome-free/css/fontawesome.min.css');
}

// Registrazione e attivazione degli asset del template con percorsi esatti
$wa->registerAndUseStyle('template.styles', $tplPath . '/css/bootstrap-italia.min.css')
   ->registerAndUseStyle('template.comuni', $tplPath . '/css/bootstrap-italia-comuni.css', [], ['template.styles'])
   ->registerAndUseStyle('template.fonts', $tplPath . '/css/fonts.css')
   ->registerAndUseScript('template.scripts', $tplPath . '/js/bootstrap-italia.bundle.min.js', [], ['defer' => true]);
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <jdoc:include type="head" />
<style>
:root {
  /* Colore primario personalizzato */
  --bs-primary: <?php echo htmlspecialchars($colore); ?> !important;
  --bs-link-color: <?php echo htmlspecialchars($colore); ?> !important;
  --bs-link-hover-color: color-mix(in srgb, <?php echo htmlspecialchars($colore); ?> 85%, black) !important;
  
  /* Success e info con il colore primario */
  --bs-success: <?php echo htmlspecialchars($colore); ?> !important;
  --bs-info: <?php echo htmlspecialchars($colore); ?> !important;
  
  /* Variabili bottoni */
  --bs-btn-color: <?php echo htmlspecialchars($colore); ?> !important;
  --bs-btn-hover-color: color-mix(in srgb, <?php echo htmlspecialchars($colore); ?> 85%, black) !important;
  --bs-btn-active-color: color-mix(in srgb, <?php echo htmlspecialchars($colore); ?> 70%, black) !important;
  
  /* Colori RGB per trasparenze (se necessario) */
  <?php
  // Converte il colore hex in RGB
  $hex = ltrim($colore, '#');
  if (strlen($hex) == 6) {
      $r = hexdec(substr($hex, 0, 2));
      $g = hexdec(substr($hex, 2, 2));
      $b = hexdec(substr($hex, 4, 2));
      echo "--bs-primary-rgb: {$r}, {$g}, {$b} !important;";
      echo "--bs-success-rgb: {$r}, {$g}, {$b} !important;";
      echo "--bs-info-rgb: {$r}, {$g}, {$b} !important;";
  }
  ?>
}

/* Header specifici che non usano le variabili */
.it-header-center-wrapper {
    background-color: <?php echo htmlspecialchars($colore); ?> !important;
}

.it-header-slim-wrapper {
    background: #202a2e;
}

.navbar {
    background: <?php echo htmlspecialchars($colore); ?> !important;
}

@media (min-width: 992px) {
    .it-header-navbar-wrapper,
    .navbar {
        background: <?php echo htmlspecialchars($colore); ?> !important;
    }
}

/* Category link nelle card */
.card .card-body .category-top a.category {
    color: <?php echo htmlspecialchars($colore); ?> !important;
}

/* Bottone primario background */
.btn-primary {
    background-color: <?php echo htmlspecialchars($colore); ?> !important;
    border-color: <?php echo htmlspecialchars($colore); ?> !important;
}

/* Bottone info */
.btn-info {
    color: #fff !important;
    background-color: <?php echo htmlspecialchars($colore); ?> !important;
    border-color: <?php echo htmlspecialchars($colore); ?> !important;
}

/* Dropdown button */
.dropdown .btn-dropdown {
    --bs-btn-hover-color: color-mix(in srgb, <?php echo htmlspecialchars($colore); ?> 85%, black) !important;
}

.btn-dropdown {
    color: <?php echo htmlspecialchars($colore); ?> !important;
}

/* Hover per i bottoni con colore più scuro */
.btn-primary:hover,
.btn-primary:focus,
.btn-secondary:hover,
.btn-secondary:focus,
.btn-info:hover,
.btn-info:focus {
    background-color: color-mix(in srgb, <?php echo htmlspecialchars($colore); ?> 85%, black) !important;
    border-color: color-mix(in srgb, <?php echo htmlspecialchars($colore); ?> 85%, black) !important;
    color: #fff !important;
}

/* Icone primary */
.icon-primary {
    fill: <?php echo htmlspecialchars($colore); ?> !important;
}

/* Spaziatura list-inline-item */
.list-inline-item {
    margin-bottom: 10px;
}

/* Navscroll - Accordion button */
.cmp-navscroll .navbar.it-navscroll-wrapper .link-list-wrapper .accordion .accordion-header .accordion-button {
    color: <?php echo htmlspecialchars($colore); ?> !important;
}

/* Navscroll - Link attivi */
.navbar.it-navscroll-wrapper .link-list-wrapper ul li a.active span,
.link-list-wrapper ul li a.active span {
    color: <?php echo htmlspecialchars($colore); ?> !important;
}

@media (min-width: 992px) {
    .navbar.it-navscroll-wrapper .link-list-wrapper ul li a.active span {
        color: <?php echo htmlspecialchars($colore); ?> !important;
    }
}

/* Navscroll - Tutti i link span */
.link-list-wrapper ul li a span {
    color: <?php echo htmlspecialchars($colore); ?> !important;
}

/* Navscroll - Bordo sinistro link attivi */
aside .cmp-navscroll .navbar.it-navscroll-wrapper .link-list-wrapper ul li a.active {
    border-left: 2px solid <?php echo htmlspecialchars($colore); ?> !important;
}

/* Read more link */
a.read-more {
    color: <?php echo htmlspecialchars($colore); ?> !important;
}

a.read-more .icon {
    fill: <?php echo htmlspecialchars($colore); ?> !important;
}

/* Testo bianco per heading diretti in bg-primary */
.bg-primary > .container > .moduletable > h1,
.bg-primary > .container > .moduletable > h2,
.bg-primary > .container > .moduletable > h3,
.bg-primary > .container > .moduletable > h4,
.bg-primary > .container > .moduletable > h5,
.bg-primary > .container > .moduletable > h6,
.bg-primary > .container > h1,
.bg-primary > .container > h2,
.bg-primary > .container > h3,
.bg-primary > .container > h4,
.bg-primary > .container > h5,
.bg-primary > .container > h6 {
    color: #fff !important;
}

/* Testo bianco per paragrafi diretti in bg-primary */
.bg-primary > .container > p,
.bg-primary > .container > .moduletable > p {
    color: #fff !important;
}

/* Menu mobile - sfondo e colori */
.navbar-collapsable .menu-wrapper {
    background-color: <?php echo htmlspecialchars($colore); ?> !important;
}

/* Overlay grigio scuro semitrasparente */
.navbar-collapsable .overlay {
    background-color: rgba(0, 0, 0, 0.7) !important;
}

/* Solo il menu wrapper con sfondo primario, non tutto il collapsable */
.navbar-collapsable {
    background-color: transparent !important;
}

/* Testo bianco nel menu mobile */
.navbar-collapsable .menu-wrapper .nav-link,
.navbar-collapsable .menu-wrapper .nav-link span,
.navbar-collapsable .it-socials span,
.navbar-collapsable .it-brand-title {
    color: #fff !important;
}

/* Logo hamburger link */
.logo-hamburger .it-brand-text .it-brand-title {
    color: #fff !important;
}

/* Hover sui link del menu mobile */
.navbar-collapsable .menu-wrapper .nav-link:hover,
.navbar-collapsable .menu-wrapper .nav-link:focus {
    background-color: color-mix(in srgb, <?php echo htmlspecialchars($colore); ?> 85%, black) !important;
    color: #fff !important;
}

/* Link attivo nel menu mobile */
.navbar-collapsable .menu-wrapper .nav-link.active,
.navbar-collapsable .menu-wrapper .nav-item.active .nav-link {
    background-color: color-mix(in srgb, <?php echo htmlspecialchars($colore); ?> 85%, black) !important;
    color: #fff !important;
}

.navbar-backdrop {
    z-index: 1;
}

/* Sfondo Dinamico Sezione Evidenza */
.bg-evidenza {
    background-image: url('<?php echo htmlspecialchars($urlSfondoEvidenza, ENT_QUOTES, 'UTF-8'); ?>') !important;
    background-size: cover !important;
    background-position: center !important;
    background-repeat: no-repeat !important;
}

/* Rimuovi eventuali pseudo-elementi esistenti di default in Bootstrap Italia */
.bg-evidenza::before,
.bg-evidenza::after {
    display: none !important;
}

</style>

  </head>
  <body>
<header class="it-header-wrapper" data-bs-target="#header-nav-wrapper" style="">
              <div class="it-header-slim-wrapper">
                <div class="container">
                  <div class="row">
                    <div class="col-12">
                      <div class="it-header-slim-wrapper-content">
					  
					 <?php
						$nomeRegione = $this->params->get('nomeregione');
						$linkRegione = $this->params->get('linkregione');

						if (!empty($nomeRegione)) :
							if (!empty($linkRegione)) : ?>
								<a class="d-lg-block navbar-brand" target="_blank"
								   href="<?php echo htmlspecialchars($linkRegione, ENT_QUOTES, 'UTF-8'); ?>"
								   aria-label="Vai al portale <?php echo htmlspecialchars($nomeRegione); ?> - link esterno - apertura nuova scheda"
								   title="Vai al portale <?php echo htmlspecialchars($nomeRegione); ?>">
								   <?php echo htmlspecialchars($nomeRegione); ?>
								</a>
							<?php else : ?>
								<span class="d-lg-block navbar-brand"><?php echo htmlspecialchars($nomeRegione); ?></span>
							<?php endif;
						endif; ?>


                        
						<div class="it-header-slim-right-zone" role="navigation">
  <!-- Posizione modulo per selezione lingua -->
  <jdoc:include type="modules" name="selezione-lingua" style="none" />
  
  <?php
  // Recupero parametri login dal template
  $mostraLogin = $this->params->get('mostra_login', 0);
  
  if ($mostraLogin == 1) :
      $tipoLogin = $this->params->get('tipo_login', 'standard');
      $menuitemLogin = $this->params->get('menuitem_login', 0);
      $user = Factory::getUser();
      
      if ($user->guest) {
          // Utente non loggato - mostra link login
          if ($tipoLogin == 'menuitem' && $menuitemLogin > 0) {
              // Login personalizzato tramite menu item
              $loginUrl = Route::_('index.php?Itemid=' . $menuitemLogin);
          } else {
              // Login standard di Joomla
              $loginUrl = Route::_('index.php?option=com_users&view=login');
          }
          $loginText = 'Accedi all\'area personale';
      } else {
          // Utente loggato - mostra link profilo
          $loginUrl = Route::_('index.php?option=com_users&view=profile');
          $loginText = 'Area personale';
      }
  ?>
  
  <a class="btn btn-primary btn-icon btn-full" href="<?php echo $loginUrl; ?>" data-element="personal-area-login">
    <span class="rounded-icon" aria-hidden="true">
      <svg class="icon icon-primary">
        <use xlink:href="<?= $this->baseurl ?>/templates/<?= $this->template ?>/svg/sprites.svg#it-user"></use>
      </svg>
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
								  <div class="it-brand-title"><?php echo htmlspecialchars($this->params->get('nomesito', 'Il mio Comune')); ?></div>
								  <div class="it-brand-tagline d-none d-md-block"><?php echo htmlspecialchars($this->params->get('payoff', 'Un comune da vivere')); ?></div>
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
              <svg class="icon icon-sm icon-white align-top">
                <use xlink:href="<?= $this->baseurl ?>/templates/<?= $this->template ?>/svg/sprites.svg#<?php echo $social['icon']; ?>"></use>
              </svg>
              <span class="visually-hidden"><?php echo $social['label']; ?></span>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php
  // Recupero parametri ricerca dal template
  $mostraRicerca = $this->params->get('mostra_ricerca', 0);
  
  if ($mostraRicerca == 1) :
      $tipoRicerca = $this->params->get('tipo_ricerca', 'smartsearch');
      $menuitemRicerca = $this->params->get('menuitem_ricerca', 0);
      
      if ($tipoRicerca == 'menuitem' && $menuitemRicerca > 0) {
          // Ricerca personalizzata tramite menu item
          $ricercaUrl = Route::_('index.php?Itemid=' . $menuitemRicerca);
      } else {
          // Ricerca Smart Search standard di Joomla
          $ricercaUrl = Route::_('index.php?option=com_finder&view=search');
      }
  ?>
  
  <div class="it-search-wrapper">
    <span class="d-none d-md-block">Cerca</span>
    <a class="search-link rounded-icon" href="<?php echo $ricercaUrl; ?>" aria-label="Cerca nel sito">
      <svg class="icon">
        <use href="<?= $this->baseurl ?>/templates/<?= $this->template ?>/svg/sprites.svg#it-search"></use>
      </svg>
    </a>
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
                        <!--start nav-->
                        <div class="navbar navbar-expand-lg has-megamenu">
                          <button class="custom-navbar-toggler" type="button" aria-controls="nav4" aria-expanded="false" aria-label="Mostra/Nascondi la navigazione" data-bs-target="#nav4" data-bs-toggle="navbarcollapsible">
                            <svg class="icon">
                              <use href="<?= $this->baseurl ?>/templates/<?= $this->template ?>/svg/sprites.svg#it-burger"></use>
                            </svg>
                          </button>
                          <div class="navbar-collapsable" id="nav4">
                            <div class="overlay" style="display: none;"></div>
                            <div class="close-div">
                              <button class="btn close-menu" type="button">
                                <span class="visually-hidden">Nascondi la navigazione</span>
                                <svg class="icon">
                                  <use href="<?= $this->baseurl ?>/templates/<?= $this->template ?>/svg/sprites.svg#it-close-big"></use>
                                </svg>
                              </button>
                            </div>
                            <div class="menu-wrapper">
							  <a href="<?php echo $this->baseurl; ?>" class="logo-hamburger">
								  <?php if ($logoUrl) : ?>
									<svg width="32" height="32" class="icon" aria-hidden="true">
									  <image xlink:href="<?php echo htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8'); ?>" width="32" height="32" />
									</svg>
								  <?php endif; ?>
								  <div class="it-brand-text">
									<div class="it-brand-title"><?php echo htmlspecialchars($this->params->get('nomesito', 'Il mio Comune')); ?></div>
								  </div>
								</a>
							  <jdoc:include type="modules" name="menu-principale" />
							  <nav aria-label="Secondaria">
								<jdoc:include type="modules" name="menu-secondario" />
							  </nav>
							  
							  <?php if (!empty($socialLinks)) : ?>
							  <div class="it-socials">
								<span>Seguici su</span>
								<ul>
								  <?php foreach ($socialLinks as $social) : ?>
									<li>
									  <a href="<?php echo htmlspecialchars($social['url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">
										<svg class="icon icon-sm icon-white align-top">
										  <use xlink:href="<?= $this->baseurl ?>/templates/<?= $this->template ?>/svg/sprites.svg#<?php echo $social['icon']; ?>"></use>
										</svg>
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
                </div>
              </div>
            </header>
      <h1 class="visually-hidden" id="main-container"><?php echo htmlspecialchars($this->params->get('nomesito', 'Il mio Comune')); ?></h1>
      <section id="head-section">
		<div class="container">
			<jdoc:include type="modules" name="top" style="html5" />
		</div>
      </section>
      <?php if ($this->countModules('calendario')) : ?>
	  <section id="calendario">
		  <div class="section section-muted pb-90 pb-lg-50 px-lg-5 pt-0">
			<div class="container">
			  <jdoc:include type="modules" name="calendario" style="html5" />
			</div>
		  </div>
	  </section>
	   <?php endif; ?>
	   
	   <?php if ($this->countModules('evidenza')) : ?>
	   <section id="evidenza" class="evidence-section">
		<div class="section py-5 pb-lg-80 px-lg-5 position-relative bg-evidenza">
                
	<div class="container">
			<div class="row">
			 <jdoc:include type="modules" name="evidenza" style="html5" />
			</div>
	</div>
                
            </div>
        </section>
	<?php endif; ?>
	<?php if ($this->countModules('top-muted')) : ?>
      <section class="useful-links-section">
        <div class="section section-muted p-0 py-5">
          <div class="container">
            <jdoc:include type="modules" name="top-muted" style="html5" />
            </div>
          </div>
        </div>
      </section>
	  <?php endif; ?>
	  <main id="main" class="main-section">
	  <div class="section py-5 pb-lg-80 px-lg-5 position-relative">
		<div class="container" id="main-container">
			<jdoc:include type="component" />
		</div>
	  </div>
	  </main>
	<?php if ($this->countModules('bottom')) : ?>
	<section class="bottom-section ">
      <div class="bg-primary py-5 pb-lg-80 px-lg-5 position-relative">
        <div class="container">
			<jdoc:include type="modules" name="bottom" style="html5" />
        </div>
      </div>
	 </section>
    <?php endif; ?>
	<?php if ($this->countModules('bottom2')) : ?>
	<section class="bottom2-section">
      <div class="bg-grey-card shadow-contacts py-5 pb-lg-80 px-lg-5 position-relative">
        <div class="container">
			<jdoc:include type="modules" name="bottom2" style="html5" />
        </div>
      </div>
	</section>
	  <?php endif; ?>

<footer class="it-footer" id="footer">
      <div class="it-footer-main">
        <div class="container">
          <div class="row">
            <div class="col-12 footer-items-wrapper logo-wrapper">
              <img class="ue-logo" src="<?= $this->baseurl ?>/templates/<?= $this->template ?>/images/logo-eu-inverted.svg" alt="logo Unione Europea">
				<div class="it-brand-wrapper">
				  <a href="<?php echo $this->baseurl; ?>">
					<?php if ($logoUrl) : ?>
					  <svg class="icon" aria-hidden="true">
						<image xlink:href="<?php echo htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8'); ?>" />
					  </svg>
					<?php endif; ?>
					<div class="it-brand-text">
					  <div class="it-brand-title"><?php echo htmlspecialchars($this->params->get('nomesito', 'Il mio Comune')); ?></div>
					  <div class="it-brand-tagline d-none d-md-block"><?php echo htmlspecialchars($this->params->get('payoff', 'Un comune da vivere')); ?></div>
					</div>
				  </a>
				</div>
            </div>
          </div>
		  <?php if ($this->countModules('footer1')) : ?>
			  <div class="row footer1">
					<jdoc:include type="modules" name="footer1" style="html5" />
			  </div>
		  <?php endif; ?>
		  <?php if ($this->countModules('footer2')) : ?>
			  <div class="row footer2">
				<jdoc:include type="modules" name="footer2" style="html5" />
			  </div>
		  <?php endif; ?>
        </div>
      </div>
    </footer>

    <?php if ($this->params->get('backTop') == 1) : ?>
        <a href="#top" id="back-top" class="back-to-top-link" aria-label="<?php echo Text::_('TPL_CASSIOPEIA_BACKTOTOP'); ?>">
            <span class="icon-arrow-up icon-fw" aria-hidden="true"></span>
        </a>
    <?php endif; ?>

    <jdoc:include type="modules" name="debug" style="none" />
</body>

</html>