<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

$app       = Factory::getApplication();
$doc       = $app->getDocument();
$template  = $app->getTemplate();
$params    = $app->getTemplate(true)->params;

$colore    = $params->get('coloreprimario', '#0066CC');
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

// Gestione Favicon
$faviconSvg = $params->get('favicon_svg');
$faviconPng = $params->get('favicon_png');

if (!empty($faviconSvg)) {
    $favSvg = HTMLHelper::cleanImageURL($faviconSvg);
    $doc->addHeadLink($this->baseurl . '/' . ltrim($favSvg->url, '/'), 'icon', 'rel', ['type' => 'image/svg+xml']);
}

if (!empty($faviconPng)) {
    $favPng = HTMLHelper::cleanImageURL($faviconPng);
    // Aggiunge l'icona per i dispositivi Apple (iPhone/iPad)
    $doc->addHeadLink($this->baseurl . '/' . ltrim($favPng->url, '/'), 'apple-touch-icon', 'rel');
    
    // Aggiunge il PNG come fallback. Se c'è già l'SVG, lo dichiariamo come "alternate"
    $relType = empty($faviconSvg) ? 'icon' : 'alternate icon';
    $doc->addHeadLink($this->baseurl . '/' . ltrim($favPng->url, '/'), $relType, 'rel', ['type' => 'image/png']);
}

// INSERIMENTO ASSET E FONT-AWESOME
$wa = $this->getWebAssetManager();

// Costruiamo il percorso corretto alla cartella del template
$tplPath = 'templates/' . $this->template;

if ($wa->assetExists('style', 'fontawesome')) {
	$wa->useStyle('fontawesome');
} else {
	$wa->registerAndUseStyle('fa-base', 'media/vendor/fontawesome-free/css/fontawesome.min.css');
}

$wa->registerAndUseStyle('template.styles', $tplPath . '/css/bootstrap-italia.min.css')
   ->registerAndUseStyle('template.comuni', $tplPath . '/css/bootstrap-italia-comuni.css', [], [], ['template.styles'])
   ->registerAndUseStyle('template.fonts', $tplPath . '/css/fonts.css')
   ->registerAndUseScript('template.scripts', $tplPath . '/js/bootstrap-italia.bundle.min.js', [], ['defer' => true]);

if ((int) $params->get('mostra_feedback', 0) === 1
    && $app->input->get('option') === 'com_content'
    && $app->input->get('view') === 'article') {
    $wa->registerAndUseScript('template.feedback', $tplPath . '/js/feedback-chiarezza.js', [], ['defer' => true]);
}

if (file_exists(JPATH_ROOT . '/templates/' . $this->template . '/css/custom.css')) {
    $wa->registerAndUseStyle('template.custom', $tplPath . '/css/custom.css', [], [], ['template.comuni']);
}
// Mappa degli sfondi in base al colore scelto
$mappaSfondi = [
    '#0066CC' => 'blu-default.jpg',
    '#007a52' => 'verde-comuni.png',
    '#d1344c' => 'rosso-scuola.jpg',
    '#07768d' => 'verde-acqua-asl.jpg',
    '#7d2670' => 'viola-musei.jpg'
];
$sfondoScelto = $mappaSfondi[$colore] ?? 'blu-default.jpg';
$urlSfondoEvidenza = $this->baseurl . '/templates/' . $this->template . '/images/' . $sfondoScelto;

// INIEZIONE VARIABILI CSS DINAMICHE
$hex = ltrim($colore, '#');
$r = hexdec(substr($hex, 0, 2));
$g = hexdec(substr($hex, 2, 2));
$b = hexdec(substr($hex, 4, 2));

$inlineCss = "
:root {
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
/* Sovrascrittura della testata alta (Header Slim) - La scuriamo del 25% rispetto al primario */
.it-header-slim-wrapper {
  background-color: color-mix(in srgb, var(--bs-primary) 75%, black) !important;
}
/* Sfondo della sezione Evidenza */
.bg-evidenza {
  background-image: url('{$urlSfondoEvidenza}') !important;
  background-size: cover !important;
  background-position: center !important;
  background-repeat: no-repeat !important;
}
.bg-evidenza::before, .bg-evidenza::after {
  display: none !important;
}
";

$wa->addInlineStyle($inlineCss);

?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <jdoc:include type="head" />
</head>
  <body>
    
    <div class="skiplinks visually-hidden-focusable">
        <a href="#main"><?php echo Text::_('TPL_ACCESSIBILE_SKIP_MAIN'); ?></a>
        <a href="#header-nav-wrapper"><?php echo Text::_('TPL_ACCESSIBILE_SKIP_MENU'); ?></a>
        <a href="#footer"><?php echo Text::_('TPL_ACCESSIBILE_SKIP_FOOTER'); ?></a>
    </div>

    <header class="it-header-wrapper" data-bs-target="#header-nav-wrapper">
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
								<a class="d-lg-block navbar-brand" target="_blank" href="<?php echo htmlspecialchars($linkRegione, ENT_QUOTES, 'UTF-8'); ?>" 
                  aria-label="<?php echo Text::sprintf('TPL_ACCESSIBILE_GO_TO_PORTAL', htmlspecialchars($nomeRegione)) . ' - ' . Text::_('TPL_ACCESSIBILE_EXTERNAL_LINK'); ?>" 
                  title="<?php echo Text::sprintf('TPL_ACCESSIBILE_GO_TO_PORTAL', htmlspecialchars($nomeRegione)); ?>">
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
          // Assegnazione diretta della costante lingua
          $loginText = Text::_('TPL_ACCESSIBILE_LOGIN');
      } else {
          // Utente loggato - mostra link profilo
          $loginUrl = Route::_('index.php?option=com_users&view=profile');
          // Assegnazione diretta della costante lingua
          $loginText = Text::_('TPL_ACCESSIBILE_PERSONAL_AREA');
      }
  ?>
  
  <a class="btn btn-primary btn-icon btn-full" href="<?php echo $loginUrl; ?>" data-element="personal-area-login" aria-label="<?php echo $loginText; ?>">
    <span class="rounded-icon" aria-hidden="true">
      <svg class="icon icon-primary">
        <use xlink:href="<?= $this->baseurl ?>/templates/<?= $this->template ?>/svg/sprites.svg#it-user"></use>
      </svg>
    </span>
    <span aria-hidden="true" class="d-none d-lg-block"><?php echo $loginText; ?></span>
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
      <span><?php echo Text::_('TPL_ACCESSIBILE_FOLLOW_US'); ?></span>
      <ul>
        <?php foreach ($socialLinks as $social) : ?>
          <li>
            <a href="<?php echo htmlspecialchars($social['url'], ENT_QUOTES, 'UTF-8'); ?>" 
               target="_blank" 
               rel="noopener noreferrer" 
               class="text-white" 
               aria-label="<?php echo $social['label']; ?> - <?php echo Text::_('TPL_ACCESSIBILE_NEW_WINDOW'); ?>">
              
              <svg class="icon icon-sm align-top" aria-hidden="true" style="fill: currentColor;">
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
          $ricercaUrl = Route::_('index.php?Itemid=' . $menuitemRicerca); // <-- MODIFICATO
      } else {
          $ricercaUrl = Route::_('index.php?option=com_finder&view=search'); // <-- MODIFICATO
      }
  ?>
  
  <div class="it-search-wrapper">
    <span class="d-none d-md-block"><?php echo Text::_('TPL_ACCESSIBILE_SEARCH'); ?></span>
    <a class="search-link rounded-icon" href="<?php echo $ricercaUrl; ?>" aria-label="<?php echo Text::_('TPL_ACCESSIBILE_SEARCH_SITE'); ?>">
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
                          <button class="custom-navbar-toggler" type="button" aria-controls="nav4" aria-expanded="false" aria-label="<?php echo Text::_('TPL_ACCESSIBILE_TOGGLE_NAV'); ?>" data-bs-target="#nav4" data-bs-toggle="navbarcollapsible">
                            <svg class="icon">
                              <use href="<?= $this->baseurl ?>/templates/<?= $this->template ?>/svg/sprites.svg#it-burger"></use>
                            </svg>
                          </button>
                          <div class="navbar-collapsable" id="nav4">
                            <div class="overlay" style="display: none;"></div>
                            <div class="close-div">
                              <button class="btn close-menu" type="button">
                                <span class="visually-hidden"><?php echo Text::_('TPL_ACCESSIBILE_HIDE_NAV'); ?></span>
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
								<span><?php echo Text::_('TPL_ACCESSIBILE_FOLLOW_US'); ?></span>
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
<h1 class="visually-hidden" id="main-title"><?php echo htmlspecialchars($this->params->get('nomesito', 'Il mio Comune')); ?></h1>
      <?php if ($this->countModules('percorso')) : ?>
      <nav aria-label="<?php echo Text::_('TPL_ACCESSIBILE_BREADCRUMB_NAV'); ?>" id="percorso-section" class="cmp-breadcrumbs">
        <div class="container">
          <jdoc:include type="modules" name="percorso" style="none" />
        </div>
      </nav>
      <?php endif; ?>
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
		<div class="section py-5 pb-lg-80 px-lg-5 position-relative bg-evidenza bg-evidenza-<?php echo $this->params->get('coloreprimario', 'blu'); ?>">
                
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
      </section>
	  <?php endif; ?>
	  <main id="main" class="main-section">
	  <div class="section py-1 pb-lg-80 px-lg-5 position-relative">
		<div class="container" id="main-container">
			<jdoc:include type="component" />
		</div>
	  </div>
	  </main>
	<?php // Widget Valutazione chiarezza pagina (C.SI.2.5) - solo su articoli e se abilitato ?>
	<?php if ((int) $params->get('mostra_feedback', 0) === 1
		&& $app->input->get('option') === 'com_content'
		&& $app->input->get('view') === 'article') : ?>
		<?php echo LayoutHelper::render('accessibile.feedback-chiarezza'); ?>
	<?php endif; ?>
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
              <?php if ($this->params->get('mostra_logo_ue', 1)) : ?>
              <img class="ue-logo" src="<?= $this->baseurl ?>/templates/<?= $this->template ?>/images/logo-eu-inverted.svg" alt="<?php echo Text::_('TPL_ACCESSIBILE_EU_LOGO_ALT'); ?>">
              <?php endif; ?>
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
