<?php

/**
 * @package     Pixed.Plugin
 * @subpackage  SampleData.templateaccessibile
 *
 * @copyright   Copyright (C) 2025 Pixed Web Development. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Database\DatabaseInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Pixed\Plugin\SampleData\TemplateAccessibile\Extension\TemplateAccessibile;

return new class () implements ServiceProviderInterface {
    public function register(Container $container): void
    {
        $container->set(
            PluginInterface::class,
            $container->lazy(TemplateAccessibile::class, function (Container $container) {
                $plugin = new TemplateAccessibile(
                    (array) PluginHelper::getPlugin('sampledata', 'templateaccessibile')
                );
                $plugin->setApplication(Factory::getApplication());
                $plugin->setDatabase($container->get(DatabaseInterface::class));

                return $plugin;
            })
        );
    }
};
