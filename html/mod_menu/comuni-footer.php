<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * Layout "comuni-footer": elenco di link per le posizioni footer1 / footer2.
 *
 * Serve per i link istituzionali (note legali, privacy, accessibilità, FAQ, segnalazione
 * disservizio): emette gli stessi data-element di comuni-menu, ma come elenco semplice e
 * senza data-element="main-navigation", che deve comparire solo nell'intestazione.
 * Mostra un solo livello di voci (quello di partenza del modulo).
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

$_parentTpl = Factory::getApplication()->getTemplate(true)->parent
    ?: Factory::getApplication()->getTemplate();
require_once JPATH_SITE . '/templates/' . $_parentTpl . '/helpers/TplAccessibileHelper.php';

$esc            = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$start          = (int) $params->get('startLevel', 1);
$tagId          = $params->get('tag_id') ? ' id="' . $esc($params->get('tag_id')) . '"' : '';
$dataElementMap = TplAccessibileHelper::menuDataElements();
?>
<ul class="footer-list link-list list-unstyled ps-0 clearfix <?php echo $esc($class_sfx); ?>"<?php echo $tagId; ?>>
    <?php foreach ($list as $item) : ?>
        <?php
        if ((int) $item->level !== $start) {
            continue;
        }

        // flink è già passato da Route::_() con escape: niente doppia codifica
        $attributes = ' href="' . htmlspecialchars((string) $item->flink, ENT_COMPAT, 'UTF-8', false) . '"';

        if (isset($dataElementMap[(int) $item->id])) {
            $attributes .= ' data-element="' . $esc($dataElementMap[(int) $item->id]) . '"';
        }

        if ((int) $item->id === (int) $active_id) {
            $attributes .= ' aria-current="page"';
        }

        if ((int) $item->browserNav === 1) {
            $attributes .= ' target="_blank" rel="noopener noreferrer"';
        }
        ?>
        <li>
            <?php if (in_array($item->type, ['separator', 'heading'], true)) : ?>
                <span class="list-item"><?php echo $esc($item->title); ?></span>
            <?php else : ?>
                <a class="list-item"<?php echo $attributes; ?>><?php echo $esc($item->title); ?></a>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
