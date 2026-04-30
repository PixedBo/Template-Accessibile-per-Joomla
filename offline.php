<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

$app = Factory::getApplication();
$wa = $app->getDocument()->getWebAssetManager();
$tplPath = 'templates/' . $app->getTemplate();

$wa->registerAndUseStyle('template.styles', $tplPath . '/css/bootstrap-italia.min.css')
   ->registerAndUseStyle('template.comuni', $tplPath . '/css/bootstrap-italia-comuni.css', [], [], ['template.styles'])
   ->registerAndUseStyle('template.fonts', $tplPath . '/css/fonts.css')
   ->registerAndUseScript('template.scripts', $tplPath . '/js/bootstrap-italia.bundle.min.js', [], ['defer' => true]);

?><!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo Text::_('TPL_ACCESSIBILE_OFFLINE_TITLE'); ?></title>
  <jdoc:include type="head" />
  <style>body { padding: 3rem; text-align: center; }</style>
</head>
<body>
  <main class="container" id="main-content">
    <h1 class="text-warning"><?php echo Text::_('TPL_ACCESSIBILE_OFFLINE_HEADING'); ?></h1>
    <p><?php echo Text::_('TPL_ACCESSIBILE_OFFLINE_MESSAGE'); ?></p>
  </main>
</body>
</html>
