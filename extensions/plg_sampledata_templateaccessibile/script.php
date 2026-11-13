<?php

/**
 * @package     Pixed.Plugin
 * @subpackage  SampleData.templateaccessibile
 *
 * @copyright   Copyright (C) 2025 Pixed Web Development. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *
 * Script di installazione del plugin "Dati di esempio" del Template Accessibile.
 */

declare(strict_types=1);

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\InstallerScriptInterface;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Version;
use Joomla\Database\DatabaseInterface;

return new class () implements InstallerScriptInterface {
    private const MIN_JOOMLA = '5.4.0';
    private const MIN_PHP    = '8.1.0';

    public function preflight(string $type, InstallerAdapter $adapter): bool
    {
        if ($type === 'uninstall') {
            return true;
        }

        // Le stringhe del plugin non sono ancora caricate in questa fase
        if (!(new Version())->isCompatible(self::MIN_JOOMLA)) {
            Factory::getApplication()->enqueueMessage(
                'Template Accessibile - Dati di esempio: richiede Joomla ' . self::MIN_JOOMLA . ' o superiore. / Requires Joomla ' . self::MIN_JOOMLA . ' or later.',
                'error'
            );

            return false;
        }

        if (version_compare(PHP_VERSION, self::MIN_PHP, '<')) {
            Factory::getApplication()->enqueueMessage(
                'Template Accessibile - Dati di esempio: richiede PHP ' . self::MIN_PHP . ' o superiore. / Requires PHP ' . self::MIN_PHP . ' or later.',
                'error'
            );

            return false;
        }

        return true;
    }

    public function install(InstallerAdapter $adapter): bool
    {
        return true;
    }

    public function update(InstallerAdapter $adapter): bool
    {
        return true;
    }

    public function uninstall(InstallerAdapter $adapter): bool
    {
        return true;
    }

    /**
     * Alla prima installazione abilita il plugin: Joomla installa i plugin disabilitati e un
     * plugin sampledata disabilitato non compare nel modulo "Dati di esempio".
     * In aggiornamento lo stato scelto dall'amministratore non viene toccato.
     */
    public function postflight(string $type, InstallerAdapter $adapter): bool
    {
        if ($type !== 'install') {
            return true;
        }

        try {
            $db    = Factory::getContainer()->get(DatabaseInterface::class);
            $query = $db->getQuery(true)
                ->update($db->quoteName('#__extensions'))
                ->set($db->quoteName('enabled') . ' = 1')
                ->where($db->quoteName('type') . ' = ' . $db->quote('plugin'))
                ->where($db->quoteName('folder') . ' = ' . $db->quote('sampledata'))
                ->where($db->quoteName('element') . ' = ' . $db->quote('templateaccessibile'));

            $db->setQuery($query)->execute();

            $app = Factory::getApplication();
            $app->getLanguage()->load('plg_sampledata_templateaccessibile', JPATH_PLUGINS . '/sampledata/templateaccessibile');
            $app->enqueueMessage(Text::_('PLG_SAMPLEDATA_TEMPLATEACCESSIBILE_POSTINSTALL'));
        } catch (\Throwable $e) {
            // Il plugin resta installato: basta abilitarlo a mano
        }

        return true;
    }
};
