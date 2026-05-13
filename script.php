<?php

/**
 * @package     Joomla.Site
 * @subpackage  Template.accessibile
 *
 * Script di installazione/aggiornamento del template.
 */

declare(strict_types=1);

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\InstallerScriptInterface;
use Joomla\CMS\Log\Log;
use Joomla\CMS\Version;
use Joomla\Database\DatabaseInterface;

return new class implements InstallerScriptInterface
{
    /**
     * Verifiche prerequisiti prima dell'installazione/aggiornamento.
     */
    public function preflight(string $type, InstallerAdapter $adapter): bool
    {
        if (!(new Version())->isCompatible('5.0.0')) {
            Log::add('Template Accessibile richiede Joomla 5.0.0 o superiore.', Log::ERROR, 'tpl_accessibile');

            return false;
        }

        if (version_compare(PHP_VERSION, '8.2.0', '<')) {
            Log::add('Template Accessibile richiede PHP 8.2.0 o superiore.', Log::ERROR, 'tpl_accessibile');

            return false;
        }

        return true;
    }

    /**
     * Operazioni post-installazione iniziale.
     */
    public function install(InstallerAdapter $adapter): bool
    {
        return true;
    }

    /**
     * Operazioni post-aggiornamento.
     *
     * Forza inheritable=1 in #__template_styles per tutti gli stili del template,
     * necessario per la corretta risoluzione dei path media/ da parte dell'HTMLHelper
     * (bug Joomla #39399).
     */
    public function update(InstallerAdapter $adapter): bool
    {
        try {
            $db = Factory::getContainer()->get(DatabaseInterface::class);

            $query = $db->getQuery(true)
                ->update($db->quoteName('#__template_styles'))
                ->set($db->quoteName('inheritable') . ' = 1')
                ->where([
                    $db->quoteName('template') . ' = ' . $db->quote('templateaccessibileperjoomla'),
                    $db->quoteName('client_id') . ' = 0',
                ]);

            $db->setQuery($query);
            $db->execute();
        } catch (\Exception $e) {
            Log::add(
                'Errore durante l\'aggiornamento di inheritable: ' . $e->getMessage(),
                Log::ERROR,
                'tpl_accessibile'
            );

            return false;
        }

        return true;
    }

    /**
     * Operazioni pre-disinstallazione.
     */
    public function uninstall(InstallerAdapter $adapter): bool
    {
        return true;
    }

    /**
     * Operazioni al termine dell'installazione/aggiornamento.
     */
    public function postflight(string $type, InstallerAdapter $adapter): bool
    {
        return true;
    }
};
