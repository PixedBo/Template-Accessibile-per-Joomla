<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * Layout alternativo "Note Legali" per articolo singolo.
 * Appende una sezione "Licenza dei contenuti" con la dicitura CC-BY 4.0 verbatim
 * richiesta dal criterio C.SI.3.4 del Modello Comuni (Designers Italia).
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

/** @var \Joomla\Component\Content\Site\View\Article\HtmlView $this */
?>
<main id="main-container" class="container my-5">
    <article class="it-single-article note-legali">
        <header>
            <h1><?php echo $this->escape($this->item->title); ?></h1>
        </header>

        <?php if (!empty($this->item->introtext)) : ?>
            <div class="article-intro">
                <?php echo HTMLHelper::_('content.prepare', $this->item->introtext); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($this->item->fulltext)) : ?>
            <div class="article-body">
                <?php echo HTMLHelper::_('content.prepare', $this->item->fulltext); ?>
            </div>
        <?php endif; ?>

        <section data-element="legal-notes" class="mt-5 pt-4 border-top">
            <h2><?php echo Text::_('TPL_ACCESSIBILE_NOTE_LEGALI_TITLE'); ?></h2>
            <p><?php echo Text::_('TPL_ACCESSIBILE_NOTE_LEGALI_CC_BY'); ?></p>
        </section>
    </article>
</main>
