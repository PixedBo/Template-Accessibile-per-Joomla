<?php

/**
 * @package     Pixed.Plugin
 * @subpackage  SampleData.templateaccessibile
 *
 * @copyright   Copyright (C) 2025 Pixed Web Development. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Pixed\Plugin\SampleData\TemplateAccessibile\Extension;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Event\Plugin\AjaxEvent;
use Joomla\CMS\Event\SampleData\GetOverviewEvent;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Session\Session;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Event\SubscriberInterface;
use Joomla\Registry\Registry;
use Pixed\Plugin\SampleData\TemplateAccessibile\Installer\Engine;
use Pixed\Plugin\SampleData\TemplateAccessibile\Installer\Report;
use Pixed\Plugin\SampleData\TemplateAccessibile\Profile\Comuni\ComuniProfile;

\defined('_JEXEC') or die;

/**
 * Dati di esempio del Template Accessibile.
 *
 * Il modulo "Dati di esempio" mostra un pulsante per ogni voce restituita da
 * onSampledataGetOverview: questo plugin ne restituisce una per blocco, così l'amministratore
 * sceglie cosa installare. Tutti i blocchi sono rieseguibili (vedi Installer\Engine).
 */
final class TemplateAccessibile extends CMSPlugin implements SubscriberInterface
{
    use DatabaseAwareTrait;

    /** Numero massimo di passi di un blocco: un handler onAjaxSampledataApplyStepN per ciascuno. */
    private const MAX_STEPS = 2;

    protected $autoloadLanguage = true;

    public static function getSubscribedEvents(): array
    {
        return [
            'onSampledataGetOverview'    => 'onSampledataGetOverview',
            'onAjaxSampledataApplyStep1' => 'onAjaxSampledataApplyStep1',
            'onAjaxSampledataApplyStep2' => 'onAjaxSampledataApplyStep2',
        ];
    }

    /**
     * Profili disponibili. Oggi solo i Comuni; un nuovo ente si aggiunge qui.
     *
     * @return  ComuniProfile[]
     */
    private function profiles(): array
    {
        return [new ComuniProfile()];
    }

    public function onSampledataGetOverview(GetOverviewEvent $event): void
    {
        if (
            !$this->getApplication()->isClient('administrator')
            || !$this->getApplication()->getIdentity()->authorise('core.create', 'com_content')
        ) {
            return;
        }

        $runs =(array) $this->params->get('runs', []);

        foreach ($this->profiles() as $profile) {
            foreach ($profile->blocks() as $name => $block) {
                if (isset($block['component']) && !ComponentHelper::isEnabled($block['component'])) {
                    continue;
                }

                $key         = $profile->languageKey($name);
                $description = Text::_($key . '_DESC');

                $run = isset($runs[$name]) ? (array) $runs[$name] : [];

                if (!empty($run['date'])) {
                    // I messaggi dei passi restano a video pochi secondi: l'esito dell'ultima
                    // esecuzione viene riportato qui, avvisi compresi
                    $description .= '<br><strong>' . Text::sprintf(
                        'PLG_SAMPLEDATA_TEMPLATEACCESSIBILE_ALREADY_RUN',
                        HTMLHelper::_('date', $run['date'], Text::_('DATE_FORMAT_LC2')),
                        (int) ($run['created'] ?? 0)
                    ) . '</strong>';

                    foreach ((array) ($run['notes'] ?? []) as $note) {
                        $description .= '<br>' . htmlspecialchars((string) $note, ENT_QUOTES, 'UTF-8');
                    }
                }

                $data              = new \stdClass();
                $data->name        = $name;
                $data->title       = Text::_($key . '_TITLE');
                $data->description = $description;
                $data->icon        = $block['icon'];
                $data->steps       = \count($block['steps']);

                $event->addResult($data);
            }
        }
    }

    public function onAjaxSampledataApplyStep1(AjaxEvent $event): void
    {
        $this->applyStep($event, 1);
    }

    public function onAjaxSampledataApplyStep2(AjaxEvent $event): void
    {
        $this->applyStep($event, 2);
    }

    private function applyStep(AjaxEvent $event, int $step): void
    {
        // com_ajax esiste anche lato sito: i dati di esempio si installano solo dall'amministrazione
        if (!$this->getApplication()->isClient('administrator')) {
            return;
        }

        $name =$this->getApplication()->getInput()->getCmd('type', '');

        // Gli stessi eventi arrivano a tutti i plugin sampledata: si risponde solo per i propri blocchi
        foreach ($this->profiles() as $profile) {
            $blocks = $profile->blocks();

            if (!isset($blocks[$name]) || $step > \count($blocks[$name]['steps']) || $step > self::MAX_STEPS) {
                continue;
            }

            $title = Text::_($profile->languageKey($name) . '_TITLE');

            if (!Session::checkToken('get')) {
                $event->addResult(['success' => false, 'message' => Text::_('JINVALID_TOKEN_NOTICE')]);

                return;
            }

            // core.create da solo spetta anche agli Author: serve l'accesso alla gestione dei contenuti
            if (!$this->getApplication()->getIdentity()->authorise('core.manage', 'com_content')) {
                $event->addResult(['success' => false, 'message' => Text::_('JERROR_ALERTNOAUTHOR')]);

                return;
            }

            $report = new Report();

            try {
                $profile->run($name, $step, new Engine($this->getApplication(), $this->getDatabase(), $report));
            } catch (\Throwable $e) {
                // Ciò che è stato creato prima dell'errore resta: rilanciando si riparte da lì
                $event->addResult([
                    'success' => false,
                    'message' => Text::sprintf('PLG_SAMPLEDATA_TEMPLATEACCESSIBILE_STEP_FAILED', $title, $step, htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'))
                        . '<br>' . $report->toHtml(),
                ]);

                return;
            }

            $this->recordRun($name, $step, $report);

            $event->addResult([
                'success' => true,
                'message' => Text::sprintf('PLG_SAMPLEDATA_TEMPLATEACCESSIBILE_STEP_DONE', $title, $step, \count($blocks[$name]['steps']))
                    . '<br>' . $report->toHtml(),
            ]);

            return;
        }
    }

    /**
     * Registra nei parametri del plugin l'esito dell'ultima esecuzione di un blocco (il passo 1
     * azzera, i successivi sommano). Serve solo a
     * informare l'amministratore nella descrizione della voce: non blocca una nuova esecuzione.
     */
    private function recordRun(string $block, int $step, Report $report): void
    {
        try {
            $db    = $this->getDatabase();
            $query = $db->getQuery(true)
                ->select($db->quoteName(['extension_id', 'params']))
                ->from($db->quoteName('#__extensions'))
                ->where($db->quoteName('type') . ' = ' . $db->quote('plugin'))
                ->where($db->quoteName('folder') . ' = ' . $db->quote('sampledata'))
                ->where($db->quoteName('element') . ' = ' . $db->quote('templateaccessibile'));

            $row = $db->setQuery($query)->loadObject();

            if (!$row) {
                return;
            }

            $params        = new Registry($row->params);
            $runs          = (array) $params->get('runs', []);
            $previous      = ($step > 1 && isset($runs[$block])) ? (array) $runs[$block] : [];
            $runs[$block]  = [
                'date'    => Factory::getDate()->toSql(),
                'created' => (int) ($previous['created'] ?? 0) + $report->totalCreated(),
                'notes'   => array_merge((array) ($previous['notes'] ?? []), $report->notes()),
            ];
            $params->set('runs', $runs);
            $row->params   = $params->toString();

            $db->updateObject('#__extensions', $row, 'extension_id');
        } catch (\Throwable $e) {
            // Informazione accessoria: un errore qui non deve far fallire l'installazione dei dati
        }
    }
}
