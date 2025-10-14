<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

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

// INSERIMENTO DI FONT-AWESOME

$wa = $this->getWebAssetManager();
if ($wa->assetExists('style', 'fontawesome')) {
	$wa->useStyle('fontawesome');
	} else {
		$wa->registerAndUseStyle('fa-base', 'media/vendor/fontawesome-free/css/fontawesome.min.css');
	}

?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <jdoc:include type="head" />
    <link rel="stylesheet" href="<?= $this->baseurl ?>/templates/<?= $this->template ?>/css/bootstrap-italia.min.css">
    <link rel="stylesheet" href="<?= $this->baseurl ?>/templates/<?= $this->template ?>/css/bootstrap-italia-comuni.css">
    <link rel="stylesheet" href="<?= $this->baseurl ?>/templates/<?= $this->template ?>/css/fonts.css">
	<style>
	:root {
	  --bs-primary: <?php echo htmlspecialchars($colore); ?> !important;
	  --bs-link-color: <?php echo htmlspecialchars($colore); ?> !important;
	}

	.it-header-center-wrapper {
		background-color:<?php echo htmlspecialchars($colore); ?> !important;
	}
	@media (min-width: 992px) {
    .it-header-navbar-wrapper {
        background: <?php echo htmlspecialchars($colore); ?> !important;
    }
	@media (min-width: 992px) {
    .navbar {
        background: <?php echo htmlspecialchars($colore); ?> !important;
    }
	.btn-primary {
    background-color: <?php echo htmlspecialchars($colore); ?> !important;
    border-color: <?php echo htmlspecialchars($colore); ?> !important;
	}
	.it-header-slim-wrapper {
    background: #202a2e;
	}
	.chip .chip-label {
		color: <?php echo htmlspecialchars($colore); ?> !important;
	}
	.chip:not(.chip-disabled) {
    border-color: <?php echo htmlspecialchars($colore); ?> !important;
	}
	a.read-more {
		color: <?php echo htmlspecialchars($colore); ?> !important;
	}
	a.read-more .icon {
    fill: <?php echo htmlspecialchars($colore); ?> !important;
	}
	.row-calendar .it-calendar-wrapper .card .card-text a {
		color: <?php echo htmlspecialchars($colore); ?> !important;
	}
	.it-calendar-wrapper .it-header-block-title {
    background-color: <?php echo htmlspecialchars($colore); ?> !important;
}
.evidence-section .list-item.active span, .useful-links-section .list-item.active span {
    color: <?php echo htmlspecialchars($colore); ?> !important;
}
.bg-primary {
    background-color: <?php echo htmlspecialchars($colore); ?> !important;
}
.icon-primary {
    fill: <?php echo htmlspecialchars($colore); ?> !important;
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
                          <div class="nav-item dropdown">
                            <button type="button" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-controls="languages" aria-haspopup="true">
                              <span class="visually-hidden">Lingua attiva:</span>
                              <span>ITA</span>
                              <svg class="icon">
                                <use href="<?= $this->baseurl ?>/templates/<?= $this->template ?>/svg/sprites.svg#it-expand"></use>
                              </svg>
                            </button>
                            <div class="dropdown-menu">
                              <div class="row">
                                <div class="col-12">
                                  <div class="link-list-wrapper">
                                    <ul class="link-list">
                                      <li><a class="dropdown-item list-item" href="#"><span>ITA <span class="visually-hidden">selezionata</span></span></a></li>
                                      <li><a class="dropdown-item list-item" href="#"><span>ENG</span></a></li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <a class="btn btn-primary btn-icon btn-full" href="../servizi/accesso-servizio.html" data-element="personal-area-login">
                            <span class="rounded-icon" aria-hidden="true">
                              <svg class="icon icon-primary">
                                <use xlink:href="<?= $this->baseurl ?>/templates/<?= $this->template ?>/svg/sprites.svg#it-user"></use>
                              </svg>
                            </span>
                            <span class="d-none d-lg-block">Accedi all'area personale</span>
                          </a>
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

							  <div class="it-search-wrapper">
								<span class="d-none d-md-block">Cerca</span>
								<button class="search-link rounded-icon" type="button" data-bs-toggle="modal" data-bs-target="#search-modal" aria-label="Cerca nel sito">
								  <svg class="icon">
									<use href="<?= $this->baseurl ?>/templates/<?= $this->template ?>/svg/sprites.svg#it-search"></use>
								  </svg>
								</button>
							  </div>
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
                              <a href="homepage.html" class="logo-hamburger">
                                <svg class="icon" aria-hidden="true">
                                  <use href="<?= $this->baseurl ?>/templates/<?= $this->template ?>/svg/sprites.svg#it-pa"></use>
                                </svg>
                                <div class="it-brand-text">
                                  <div class="it-brand-title">Nome del Comune</div>
                                </div>
                              </a>
							  <jdoc:include type="modules" name="menu-principale" />
                              <nav aria-label="Secondaria">
								<jdoc:include type="modules" name="menu-secondario" />
                              </nav>
                              <div class="it-socials">
                                <span>Seguici su</span>
                                <ul>
                                  <li>
                                    <a href="#" target="_blank">
                                      <svg class="icon icon-sm icon-white align-top">
                                        <use xlink:href="<?= $this->baseurl ?>/templates/<?= $this->template ?>/svg/sprites.svg#it-twitter"></use>
                                      </svg>
                                      <span class="visually-hidden">Twitter</span></a>
                                  </li>
                                  <li>
                                    <a href="#" target="_blank">
                                      <svg class="icon icon-sm icon-white align-top">
                                        <use xlink:href="<?= $this->baseurl ?>/templates/<?= $this->template ?>/svg/sprites.svg#it-facebook"></use>
                                      </svg>
                                      <span class="visually-hidden">Facebook</span></a>
                                  </li>
                                  <li>
                                    <a href="#" target="_blank">
                                      <svg class="icon icon-sm icon-white align-top">
                                        <use xlink:href="<?= $this->baseurl ?>/templates/<?= $this->template ?>/svg/sprites.svg#it-youtube"></use>
                                      </svg>
                                      <span class="visually-hidden">YouTube</span></a>
                                  </li>
                                  <li>
                                    <a href="#" target="_blank">
                                      <svg class="icon icon-sm icon-white align-top">
                                        <use xlink:href="<?= $this->baseurl ?>/templates/<?= $this->template ?>/svg/sprites.svg#it-telegram"></use>
                                      </svg>
                                      <span class="visually-hidden">Telegram</span></a>
                                  </li>
                                  <li>
                                    <a href="#" target="_blank">
                                      <svg class="icon icon-sm icon-white align-top">
                                        <use xlink:href="<?= $this->baseurl ?>/templates/<?= $this->template ?>/svg/sprites.svg#it-whatsapp"></use>
                                      </svg>
                                      <span class="visually-hidden">Whatsapp</span></a>
                                  </li>
                                  <li>
                                    <a href="#" target="_blank">
                                      <svg class="icon icon-sm icon-white align-top">
                                        <use xlink:href="<?= $this->baseurl ?>/templates/<?= $this->template ?>/svg/sprites.svg#it-rss"></use>
                                      </svg>
                                      <span class="visually-hidden">RSS</span></a>
                                  </li>
                                </ul>
                              </div>
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
			<jdoc:include type="modules" name="top" />
		</div>
      </section>
      <?php if ($this->countModules('calendario')) : ?>
	  <section id="calendario">
		  <div class="section section-muted pb-90 pb-lg-50 px-lg-5 pt-0">
			<div class="container">
			  <jdoc:include type="modules" name="calendario" />
			</div>
		  </div>
	  </section>
	   <?php endif; ?>
	   
	   <?php if ($this->countModules('evidenza')) : ?>
	   <section id="evidenza" class="evidence-section">
		<div class="section py-5 pb-lg-80 px-lg-5 position-relative bg-evidenza bg-evidenza-<?php echo $this->params->get('coloreprimario', 'blu'); ?>">
                
	<div class="container">
			<div class="row">
			<h2 class="text-white title-xlarge mb-3">Argomenti in evidenza</h2>
			</div>
	</div>
	<div class="container">
			<div class="row">
			 <jdoc:include type="modules" name="evidenza" />
			</div>
	</div>
                
            </div>
        </section>
	<?php endif; ?>
	<?php if ($this->countModules('top-muted')) : ?>
      <section class="useful-links-section">
        <div class="section section-muted p-0 py-5">
          <div class="container">
            <jdoc:include type="modules" name="top-muted" />
            </div>
          </div>
        </div>
      </section>
	  <?php endif; ?>
	  <main>
                    <div class="container" id="main-container">
					<jdoc:include type="component" />
					</div>
	</main>
	<?php if ($this->countModules('bottom')) : ?>
      <div class="bg-primary">
        <div class="container">
			<jdoc:include type="modules" name="bottom" />
        </div>
      </div>
    <?php endif; ?>
	<?php if ($this->countModules('bottom2')) : ?>
      <div class="bg-grey-card shadow-contacts">
        <div class="container">
			<jdoc:include type="modules" name="bottom2" />
        </div>
      </div>
	  <?php endif; ?>

<footer class="it-footer" id="footer">
      <div class="it-footer-main">
        <div class="container">
          <div class="row">
            <div class="col-12 footer-items-wrapper logo-wrapper">
              <img class="ue-logo" src="<?= $this->baseurl ?>/templates/<?= $this->template ?>/images/logo-eu-inverted.svg" alt="logo Unione Europea">
              <div class="it-brand-wrapper">
                <a href="#">
                  <svg class="icon" aria-hidden="true">
                    <use xlink:href="<?= $this->baseurl ?>/templates/<?= $this->template ?>/svg/sprites.svg#it-pa"></use>
                  </svg>
                  <div class="it-brand-text">
                    <h2 class="no_toc"><?php echo htmlspecialchars($this->params->get('nomesito', 'Il mio Comune')); ?></h2>
                  </div>
                </a>
              </div>
            </div>
          </div>
          <div class="row">
			<jdoc:include type="modules" name="footer1" />
          <div class="row">
			<jdoc:include type="modules" name="footer2" />
          </div>
        </div>
      </div>
    </footer>

    <?php if ($this->params->get('backTop') == 1) : ?>
        <a href="#top" id="back-top" class="back-to-top-link" aria-label="<?php echo Text::_('TPL_CASSIOPEIA_BACKTOTOP'); ?>">
            <span class="icon-arrow-up icon-fw" aria-hidden="true"></span>
        </a>
    <?php endif; ?>

    <jdoc:include type="modules" name="debug" style="none" />
	    <script src="<?= $this->baseurl ?>/templates/<?= $this->template ?>/js/bootstrap-italia.bundle.min.js"></script>
</body>

</html>
