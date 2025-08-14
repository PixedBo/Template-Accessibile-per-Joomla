<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   (C) 2021 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$start = (int) $params->get('startLevel', 1);
?>

<nav aria-label="Principale">
  <ul class="navbar-nav <?php echo htmlspecialchars($class_sfx); ?>" data-element="main-navigation" <?php if ($tagId = $params->get('tag_id')) echo 'id="' . $tagId . '"'; ?>>
    <?php foreach ($list as $i => $item) :
      if (!$showAll && $item->level > $start) continue;

      $itemParams = $item->getParams();
      $hasChildren = $showAll && $item->deeper;

      $class = ['nav-item', 'item-' . $item->id, 'level-' . ($item->level - $start + 1)];
      if ($item->id == $default_id) $class[] = 'default';
      if ($item->id == $active_id || ($item->type === 'alias' && $itemParams->get('aliasoptions') == $active_id)) $class[] = 'current';
      if (in_array($item->id, $path)) $class[] = 'active';
      if ($hasChildren) $class[] = 'dropdown';

      echo '<li class="' . implode(' ', $class) . '">';

      // VOCE CON SOTTOMENU
      if ($hasChildren) {
        echo '<a class="nav-link dropdown-toggle focus--mouse" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true" data-focus-mouse="true">';
        echo '<span class="visually-hidden">Sottomenu di:</span>';
        echo '<span>' . $item->title . '</span>';
        echo '<svg class="icon icon-xs icon-white align-middle" aria-hidden="true"><use href="#it-expand"></use></svg>';
        echo '</a>';

        echo '<div class="dropdown-menu"><div class="row"><div class="col-12"><div class="link-list-wrapper"><ul class="link-list">';
      } else {
        // VOCE SEMPLICE
        echo '<a class="nav-link" href="' . $item->flink . '" data-element="' . htmlspecialchars($item->alias) . '">';
        echo '<span>' . $item->title . '</span>';
        echo '</a>';
      }

      // Se ha sottovoci, il contenuto arriva nei <ul> interni
      if ($hasChildren) {
        continue;
      } elseif ($item->shallower) {
        echo '</li>' . str_repeat('</ul></div></div></div></div></li>', $item->level_diff);
      } else {
        echo '</li>';
      }

    endforeach; ?>
  </ul>
</nav>
