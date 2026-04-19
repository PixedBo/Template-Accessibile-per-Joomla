<?php
/**
 * Helper per la mappatura categorie -> data-element richiesti dal Modello Comuni
 * (Designers Italia - App Valutazione Modelli).
 *
 * L'amministratore configura nelle opzioni del template (fieldset "Categorie Modello PA")
 * quale categoria Joomla corrisponde a ciascuna tipologia di contenuto:
 *   - cat_services  -> service-link
 *   - cat_news      -> news-link
 *   - cat_events    -> event-link
 *   - cat_documents -> document-link
 *
 * La risoluzione considera anche le sottocategorie (es. un articolo in "Servizi > Anagrafe"
 * riceve "service-link").
 */

defined('_JEXEC') or die;

use Joomla\CMS\Categories\Categories;
use Joomla\CMS\Factory;

final class ModelloPAHelper
{
    /** @var array<int,string> */
    private static array $cache = [];

    /** @var array<int,string>|null */
    private static ?array $map = null;

    private static function getMap(): array
    {
        if (self::$map !== null) {
            return self::$map;
        }

        $tplParams = Factory::getApplication()->getTemplate(true)->params;

        $raw = [
            (int) $tplParams->get('cat_services', 0)  => 'service-link',
            (int) $tplParams->get('cat_news', 0)      => 'news-link',
            (int) $tplParams->get('cat_events', 0)    => 'event-link',
            (int) $tplParams->get('cat_documents', 0) => 'document-link',
        ];
        unset($raw[0]);

        return self::$map = $raw;
    }

    public static function resolveDataElement(?int $catid): string
    {
        if (!$catid) {
            return '';
        }

        if (array_key_exists($catid, self::$cache)) {
            return self::$cache[$catid];
        }

        $map = self::getMap();

        if (empty($map)) {
            return self::$cache[$catid] = '';
        }

        if (isset($map[$catid])) {
            return self::$cache[$catid] = $map[$catid];
        }

        try {
            $categories = Categories::getInstance('Content');
            $node = $categories->get($catid);

            while ($node && (int) $node->id > 1) {
                $nodeId = (int) $node->id;
                if (isset($map[$nodeId])) {
                    return self::$cache[$catid] = $map[$nodeId];
                }
                $node = $node->getParent();
            }
        } catch (\Throwable $e) {
        }

        return self::$cache[$catid] = '';
    }

    public static function attributeFor(?int $catid): string
    {
        $de = self::resolveDataElement($catid);

        return $de !== '' ? ' data-element="' . $de . '"' : '';
    }
}
