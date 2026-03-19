<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

$app = Factory::getApplication();
$spritePath = Uri::base(true) . '/templates/' . $app->getTemplate() . '/svg/sprites.svg';

// 1. LA MAGIA: Disabilitiamo lo script nativo di Joomla per evitare conflitti con Bootstrap Italia!
$wa = $app->getDocument()->getWebAssetManager();
if ($wa->assetExists('script', 'mod_menu.menu')) {
    $wa->disableScript('mod_menu.menu');
}

$start = (int) $params->get('startLevel', 1);
$menuId = $params->get('tag_id') ? 'id="' . $params->get('tag_id') . '"' : '';
?>

<nav aria-label="Principale">
  <ul class="navbar-nav <?php echo htmlspecialchars($class_sfx); ?>" data-element="main-navigation" <?php echo $menuId; ?>>
    
    <?php foreach ($list as $i => &$item) :
      // Saltiamo i livelli che non dobbiamo mostrare
      if (!$showAll && $item->level > $start) continue;

      $itemParams = $item->getParams();
      $isCurrent = ($item->id == $active_id || ($item->type === 'alias' && $itemParams->get('aliasoptions') == $active_id));
      $isActive = in_array($item->id, $path);
      $isSubmenu = ($item->level > $start);

      // Generazione Classi LI
      $liClass = $isSubmenu ? '' : 'nav-item';
      if ($item->deeper) {
          $liClass .= ' dropdown';
      }

      // Generazione Classi LINK
      $linkClass = $isSubmenu ? 'list-item' : 'nav-link';
      if ($isCurrent || $isActive) {
          $linkClass .= ' active';
      }
      if ($item->deeper) {
          $linkClass .= ' dropdown-toggle';
      }

      $ariaCurrent = $isCurrent ? ' aria-current="page"' : '';

      // APERTURA DEL LIST-ITEM
      echo '<li class="' . trim($liClass) . '">';

      // GENERAZIONE DEL LINK
      if ($item->deeper) {
        
        // 2. LA SECONDA MAGIA: Forziamo l'href a "#" sulle voci padre per evitare cambi pagina
        $href = '#';
        
        echo '<a class="' . trim($linkClass) . '" href="' . $href . '" data-bs-toggle="dropdown" aria-expanded="false" id="menu-dropdown-' . $item->id . '"' . $ariaCurrent . '>';
        echo '<span>' . $item->title . '</span>';
        echo '<svg class="icon icon-xs ms-1" aria-hidden="true"><use href="' . $spritePath . '#it-expand"></use></svg>';
        echo '</a>';
        
        echo '<div class="dropdown-menu" aria-labelledby="menu-dropdown-' . $item->id . '">';
        echo '<div class="link-list-wrapper">';
        echo '<ul class="link-list">';
        
      } else {
        
        // VOCE FIGLIA (Semplice link)
        echo '<a class="' . trim($linkClass) . '" href="' . $item->flink . '" data-element="' . htmlspecialchars($item->alias) . '"' . $ariaCurrent . '>';
        echo '<span>' . $item->title . '</span>';
        echo '</a>';
        
      }

      // CHIUSURA DEI TAG
      if ($item->deeper) {
        // È una voce padre: non chiudo, lascio aperti i contenitori per i sottomenu
      } elseif ($item->shallower) {
        // È l'ultima voce di un sottomenu: chiudo se stesso e chiudo a ritroso tutti i livelli aperti
        echo '</li>';
        $diff = $item->level_diff;
        for ($j = 0; $j < $diff; $j++) {
            echo '</ul></div></div></li>';
        }
      } else {
        // Voce normale
        echo '</li>';
      }

    endforeach; ?>
    
  </ul>
</nav>