<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

$app = Factory::getApplication();
$template = $app->getTemplate();
$homeUrl = Uri::base();
$bootstrapItaliaCss = Uri::root(true) . '/templates/' . $template . '/css/bootstrap-italia.min.css';
?><!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <title>Sito in manutenzione</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?php echo htmlspecialchars($bootstrapItaliaCss, ENT_QUOTES, 'UTF-8'); ?>">
  <style>body { padding: 3rem; text-align: center; }</style>
</head>
<body>
  <div class="container">
    <h1 class="text-warning">Sito in manutenzione</h1>
    <p>Stiamo lavorando per migliorare il servizio. Torna a trovarci presto.</p>
    <p><a class="btn btn-primary mt-3" href="<?php echo htmlspecialchars($homeUrl, ENT_QUOTES, 'UTF-8'); ?>"><?php echo Text::_('TPL_ACCESSIBILE_HOME'); ?></a></p>
  </div>
</body>
</html>