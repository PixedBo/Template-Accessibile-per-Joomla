<?php
/**
 * @package     Joomla.Site
 * @subpackage  Template.accessibile
 *
 * Pagina Offline (manutenzione)
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\AuthenticationHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

$app         = Factory::getApplication();
$doc         = $app->getDocument();
$params      = $app->getTemplate(true)->params;
$extraButtons = AuthenticationHelper::getLoginButtons('form-login');

$colore  = $params->get('coloreprimario', '#0066CC');
$baseurl = Uri::base();
$logo    = $params->get('logotipo');
$logoUrl = '';

$socialX   = $params->get('socialx');
$facebook  = $params->get('facebook');
$youtube   = $params->get('youtube');
$telegram  = $params->get('telegram');
$whatsapp  = $params->get('whatsapp');
$linkedin  = $params->get('linkedin');
$socialLinks = [];
if ($socialX)  $socialLinks[] = ['url' => $socialX, 'icon' => 'it-twitter', 'label' => 'X (Twitter)'];
if ($facebook) $socialLinks[] = ['url' => $facebook, 'icon' => 'it-facebook', 'label' => 'Facebook'];
if ($youtube)  $socialLinks[] = ['url' => $youtube, 'icon' => 'it-youtube', 'label' => 'YouTube'];
if ($telegram) $socialLinks[] = ['url' => $telegram, 'icon' => 'it-telegram', 'label' => 'Telegram'];
if ($whatsapp) $socialLinks[] = ['url' => 'https://wa.me/' . preg_replace('/[^0-9]/', '', $whatsapp), 'icon' => 'it-whatsapp', 'label' => 'Whatsapp'];
if ($linkedin) $socialLinks[] = ['url' => $linkedin, 'icon' => 'it-linkedin', 'label' => 'LinkedIn'];

if (!empty($logo)) {
    $logoData = HTMLHelper::cleanImageURL($logo);
    $logoUrl  = $baseurl . ltrim($logoData->url, '/');
}

// Favicon
$faviconSvg = $params->get('favicon_svg');
$faviconPng = $params->get('favicon_png');

if (!empty($faviconSvg)) {
    $favSvg = HTMLHelper::cleanImageURL($faviconSvg);
    $doc->addHeadLink($baseurl . ltrim($favSvg->url, '/'), 'icon', 'rel', ['type' => 'image/svg+xml']);
}

if (!empty($faviconPng)) {
    $favPng  = HTMLHelper::cleanImageURL($faviconPng);
    $doc->addHeadLink($baseurl . ltrim($favPng->url, '/'), 'apple-touch-icon', 'rel');
    $relType = empty($faviconSvg) ? 'icon' : 'alternate icon';
    $doc->addHeadLink($baseurl . ltrim($favPng->url, '/'), $relType, 'rel', ['type' => 'image/png']);
}

// INSERIMENTO ASSET E FONT-AWESOME
$wa = $this->getWebAssetManager();
$tplPath = 'templates/site/' . $this->template;
$mediaPath = 'media/templates/site/templateaccessibileperjoomla';
$baseMediaUrl = $this->baseurl . '/' . $mediaPath;

$wa->registerAndUseStyle('template.styles', 'bootstrap-italia.min.css')
   ->registerAndUseStyle('template.comuni', 'bootstrap-italia-comuni.css', [], [], ['template.styles'])
   ->registerAndUseStyle('template.fonts', 'fonts.css')
   ->registerAndUseScript('template.scripts', 'bootstrap-italia.bundle.min.js', [], ['defer' => true]);

// CSS variabili colore
$hex = ltrim($colore, '#');
$r   = hexdec(substr($hex, 0, 2));
$g   = hexdec(substr($hex, 2, 2));
$b   = hexdec(substr($hex, 4, 2));

$inlineCss = ":root {
  --bs-primary: {$colore} !important;
  --bs-link-color: {$colore} !important;
  --bs-link-hover-color: color-mix(in srgb, {$colore} 85%, black) !important;
  --bs-primary-rgb: {$r}, {$g}, {$b} !important;
}
.it-header-slim-wrapper {
  background-color: color-mix(in srgb, var(--bs-primary) 75%, black) !important;
}";
$wa->addInlineStyle($inlineCss);

$tplSvg      = $baseurl . 'templates/' . $app->getTemplate() . '/svg/sprites.svg';
$offlineImage = $app->get('offline_image');
$displayMsg   = (int) $app->get('display_offline_message', 1);
$offlineMsg   = $app->get('offline_message');
?><!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <jdoc:include type="head" />
</head>
<body>

<header id="header">
    <div class="it-header-center-wrapper bg-primary">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="it-header-center-content-wrapper py-3">
                        <div class="it-brand-wrapper">
                            <a href="<?php echo htmlspecialchars($baseurl, ENT_QUOTES, 'UTF-8'); ?>" class="text-decoration-none">
                                <?php if ($logoUrl) : ?>
                                <img src="<?php echo htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8'); ?>"
                                     alt="<?php echo htmlspecialchars($params->get('nomesito', ''), ENT_QUOTES, 'UTF-8'); ?>"
                                     aria-hidden="true"
                                     width="82" height="82"
                                     style="width:82px;height:82px;object-fit:contain;" />
                                <?php endif; ?>
                                <div class="it-brand-text">
                                    <div class="it-brand-title"><?php echo htmlspecialchars($params->get('nomesito', 'Il mio Ente')); ?></div>
                                    <?php if ($params->get('payoff')) : ?>
                                    <div class="it-brand-tagline d-none d-md-block"><?php echo htmlspecialchars($params->get('payoff', '')); ?></div>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<main class="container py-5" id="main-content">
    <div class="row justify-content-center">
        <div class="col-12 col-md-7 text-center">

            <h1 class="mb-3"><?php echo Text::_('TPL_ACCESSIBILE_OFFLINE_HEADING'); ?></h1>

            <?php if ($displayMsg == 1 && trim($offlineMsg) !== '') : ?>
            <p class="lead"><?php echo $offlineMsg; ?></p>
            <?php elseif ($displayMsg == 2) : ?>
            <p class="lead"><?php echo Text::_('JOFFLINE_MESSAGE'); ?></p>
            <?php endif; ?>

            <?php if ($offlineImage) : ?>
            <div class="my-4">
                <?php echo HTMLHelper::_('image', $offlineImage, Text::_('TPL_ACCESSIBILE_OFFLINE_HEADING'), ['class' => 'img-fluid', 'loading' => 'eager'], false, 0); ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($socialLinks)) : ?>
            <p class="mt-4 mb-2"><?php echo Text::_('TPL_ACCESSIBILE_OFFLINE_SOCIAL_TEXT'); ?></p>
            <ul class="d-flex flex-row gap-3 justify-content-center list-unstyled mb-5">
                <?php foreach ($socialLinks as $social) : ?>
                <li>
                    <a href="<?php echo htmlspecialchars($social['url'], ENT_QUOTES, 'UTF-8'); ?>"
                       target="_blank" rel="noopener noreferrer"
                       aria-label="<?php echo htmlspecialchars($social['label'], ENT_QUOTES, 'UTF-8'); ?> - <?php echo Text::_('TPL_ACCESSIBILE_NEW_WINDOW'); ?>">
                        <svg class="icon icon-lg icon-primary" aria-hidden="true">
                            <use href="<?php echo $tplSvg; ?>#<?php echo $social['icon']; ?>"></use>
                        </svg>
                        <span class="visually-hidden"><?php echo htmlspecialchars($social['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>

            <div class="text-start">
                <jdoc:include type="message" />
                <form action="<?php echo Route::_('index.php', true); ?>" method="post" id="form-login">
                    <div class="mb-3">
                        <label for="username" class="form-label"><?php echo Text::_('JGLOBAL_USERNAME'); ?></label>
                        <input name="username" class="form-control" id="username" type="text" autocomplete="username">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label"><?php echo Text::_('JGLOBAL_PASSWORD'); ?></label>
                        <input name="password" class="form-control" id="password" type="password" autocomplete="current-password">
                    </div>
                    <?php foreach ($extraButtons as $button) :
                        $dataAttrKeys = array_filter(array_keys($button), fn($k) => str_starts_with($k, 'data-'));
                    ?>
                    <div class="mb-2">
                        <button type="button"
                                class="btn btn-secondary w-100 <?php echo $button['class'] ?? ''; ?>"
                                <?php foreach ($dataAttrKeys as $key) : ?>
                                    <?php echo $key; ?>="<?php echo $button[$key]; ?>"
                                <?php endforeach; ?>
                                <?php if (!empty($button['onclick'])) : ?> onclick="<?php echo $button['onclick']; ?>"<?php endif; ?>
                                title="<?php echo Text::_($button['label']); ?>"
                                id="<?php echo $button['id']; ?>">
                            <?php if (!empty($button['icon'])) : ?><span class="<?php echo $button['icon']; ?>"></span><?php endif; ?>
                            <?php if (!empty($button['svg'])) : echo $button['svg']; endif; ?>
                            <?php echo Text::_($button['label']); ?>
                        </button>
                    </div>
                    <?php endforeach; ?>
                    <button type="submit" name="Submit" class="btn btn-primary w-100"><?php echo Text::_('JLOGIN'); ?></button>
                    <input type="hidden" name="option" value="com_users">
                    <input type="hidden" name="task" value="user.login">
                    <input type="hidden" name="return" value="<?php echo base64_encode(Uri::base()); ?>">
                    <?php echo HTMLHelper::_('form.token'); ?>
                </form>
            </div>

        </div>
    </div>
</main>

<footer class="it-footer" id="footer">
    <div class="it-footer-main">
        <div class="container">
            <div class="row">
                <div class="col-12 footer-items-wrapper logo-wrapper">
                    <?php if ($params->get('mostra_logo_ue', 1)) : ?>
                    <img class="ue-logo" src="<?php echo $baseurl; ?>templates/<?php echo $app->getTemplate(); ?>/images/logo-eu-inverted.svg" alt="<?php echo Text::_('TPL_ACCESSIBILE_EU_LOGO_ALT'); ?>">
                    <?php endif; ?>
                    <div class="it-brand-wrapper">
                        <a href="<?php echo htmlspecialchars($baseurl, ENT_QUOTES, 'UTF-8'); ?>">
                            <?php if ($logoUrl) : ?>
                                <svg class="icon" aria-hidden="true"><image xlink:href="<?php echo htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8'); ?>" /></svg>
                            <?php endif; ?>
                            <div class="it-brand-text">
                                <div class="it-brand-title"><?php echo htmlspecialchars($params->get('nomesito', 'Il mio Ente')); ?></div>
                                <div class="it-brand-tagline d-none d-md-block"><?php echo htmlspecialchars($params->get('payoff', '')); ?></div>
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
