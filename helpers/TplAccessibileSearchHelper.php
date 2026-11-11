<?php
/**
 * Ricerca (Smart Search / com_finder): URL della pagina risultati, campi nascosti
 * dei form GET, suggerimenti durante la digitazione e gruppi di filtri.
 *
 * Usato da index.php (icona e modale nell'header), dal partial
 * layouts/accessibile/ricerca-modale.php e dall'override html/com_finder/search.
 *
 * I filtri sono nodi della tassonomia di Smart Search, passati alla vista come t[]:
 * nodi dello stesso ramo sono in OR, rami diversi in AND (SearchModel::getListQuery).
 */
defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Finder\Administrator\Indexer\Taxonomy;
use Joomla\Component\Finder\Site\Helper\RouteHelper;
use Joomla\Database\DatabaseInterface;

class TplAccessibileSearchHelper
{
    private static ?array $target = null;

    /**
     * Smart Search è installato e abilitato.
     */
    public static function isAvailable(): bool
    {
        return ComponentHelper::isEnabled('com_finder');
    }

    /**
     * Destinazione dell'icona di ricerca.
     *
     * @param   \Joomla\Registry\Registry  $params  parametri dello stile del template
     *
     * @return  array{url: string, finder: bool}  `url` già passato da Route (vuoto se non c'è
     *                                            una destinazione), `finder` vero se la pagina
     *                                            è la vista risultati di Smart Search: solo in
     *                                            quel caso si può inviarle un form con `q`.
     */
    public static function target($params): array
    {
        if (self::$target !== null) {
            return self::$target;
        }

        $app    = Factory::getApplication();
        $itemId = (int) $params->get('menuitem_ricerca', 0);

        if ($params->get('tipo_ricerca', 'smartsearch') === 'menuitem' && $itemId > 0) {
            $item     = $app->getMenu()->getItem($itemId);
            $isFinder = $item
                && $item->component === 'com_finder'
                && ($item->query['view'] ?? '') === 'search';

            if ($item && !$isFinder) {
                return self::$target = ['url' => Route::_('index.php?Itemid=' . $itemId), 'finder' => false];
            }

            if ($isFinder && self::isAvailable()) {
                return self::$target = [
                    'url'    => Route::_('index.php?option=com_finder&view=search&Itemid=' . $itemId),
                    'finder' => true,
                ];
            }
        }

        if (!self::isAvailable()) {
            return self::$target = ['url' => '', 'finder' => false];
        }

        return self::$target = ['url' => Route::_(RouteHelper::getSearchRoute()), 'finder' => true];
    }

    /**
     * Campi nascosti per un form GET verso `$url`: il browser scarta la query string
     * dell'attributo action, quindi option/view/Itemid (presenti quando gli URL SEF
     * sono disattivati) vanno ripetuti come campi.
     */
    public static function hiddenFields(string $url): string
    {
        $html = '';
        $uri  = new Uri(html_entity_decode($url, ENT_QUOTES, 'UTF-8'));

        foreach ($uri->getQuery(true) as $name => $value) {
            if ($name === 'q' || !is_scalar($value)) {
                continue;
            }

            $html .= '<input type="hidden" name="' . htmlspecialchars((string) $name, ENT_QUOTES, 'UTF-8')
                . '" value="' . htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8') . '">';
        }

        return $html;
    }

    /**
     * Carica l'autocompletamento di Smart Search (stessi asset di mod_finder) per i campi
     * con classe `js-finder-search-query` dentro un form `js-finder-searchform`.
     * I suggerimenti arrivano solo se l'opzione "Suggerimenti di ricerca" del componente è attiva.
     */
    public static function useSuggestions(): void
    {
        static $done = false;

        if ($done || !self::isAvailable()) {
            return;
        }

        $done = true;
        $app  = Factory::getApplication();
        $doc  = $app->getDocument();
        $wa   = $doc->getWebAssetManager();

        $app->getLanguage()->load('com_finder', JPATH_SITE);
        $wa->getRegistry()->addExtensionRegistryFile('com_finder');
        $wa->usePreset('awesomplete');

        $doc->addScriptOptions('finder-search', [
            'url' => Route::_('index.php?option=com_finder&task=suggestions.suggest&format=json&tmpl=component', false),
        ]);

        Text::script('COM_FINDER_SEARCH_FORM_LIST_LABEL');
        Text::script('JLIB_JS_AJAX_ERROR_OTHER');
        Text::script('JLIB_JS_AJAX_ERROR_PARSE');

        $wa->useScript('com_finder.finder');
    }

    /**
     * Gruppi di filtri per la pagina risultati.
     *
     * - "type": i nodi del ramo Type (Articoli, Categorie, Tag, …).
     * - "category": i nodi del ramo Category. Un contenuto è collegato solo alla propria
     *   categoria, non alle categorie superiori: una categoria che non contiene
     *   direttamente contenuti indicizzati non è selezionabile (`selectable` falso) e
     *   compare solo come intestazione delle sue sottocategorie.
     *
     * @param   \Joomla\Registry\Registry  $params    parametri dello stile del template
     * @param   int[]                      $selected  id dei nodi attivi nella ricerca corrente
     *
     * @return  array<int, array{key: string, label: string, nodes: array<int, array{id: int, title: string, depth: int, selectable: bool, checked: bool}>}>
     */
    public static function filterGroups($params, array $selected): array
    {
        if (!self::isAvailable() || !(int) $params->get('ricerca_filtri', 1)) {
            return [];
        }

        $app      = Factory::getApplication();
        $language = $app->getLanguage();
        $levels   = $app->getIdentity()->getAuthorisedViewLevels();
        $selected = array_map('intval', $selected);
        $langs    = Multilanguage::isEnabled() ? [$language->getTag(), '*'] : null;
        $all      = Taxonomy::getTaxonomy();
        $groups   = [];

        $visible = static function ($node) use ($levels, $langs): bool {
            return (int) $node->state === 1
                && in_array((int) $node->access, $levels)
                && ($langs === null || in_array($node->language, $langs, true));
        };

        $branchNodes = static function (string $title) use ($all, $levels): array {
            $branch = Taxonomy::getBranch($title);

            if (!$branch || (int) $branch->state !== 1 || !in_array((int) $branch->access, $levels)) {
                return [];
            }

            // $all è ordinato per lft: i nodi escono già in ordine d'albero
            return array_filter(
                $all,
                static fn ($n) => $n->lft > $branch->lft && $n->rgt < $branch->rgt
            );
        };

        // Tipologia
        if ((int) $params->get('ricerca_filtri_tipo', 1)) {
            $nodes     = [];
            $typeNodes = array_filter($branchNodes('Type'), $visible);
            // Un tipo senza contenuti indicizzati (es. lasciato da un'estensione disinstallata) non si mostra
            $typeUsed  = self::nodesWithContent(array_keys($typeNodes));

            foreach ($typeNodes as $node) {
                if (!isset($typeUsed[(int) $node->id])) {
                    continue;
                }

                $nodes[] = [
                    'id'         => (int) $node->id,
                    'title'      => self::typeLabel($node->title, true),
                    'parent'     => '',
                    'depth'      => 0,
                    'selectable' => true,
                    'checked'    => in_array((int) $node->id, $selected, true),
                ];
            }

            if (count($nodes) > 1) {
                $groups[] = ['key' => 'type', 'label' => Text::_('TPL_ACCESSIBILE_SEARCH_FILTER_TYPE'), 'nodes' => $nodes];
            }
        }

        // Categorie
        if ((int) $params->get('ricerca_filtri_cat', 1)) {
            $candidates = array_filter($branchNodes('Category'), $visible);
            $allowed    = self::categoryTitles((array) $params->get('ricerca_filtri_categorie', []));

            if ($allowed !== null) {
                // Un nodo "corrisponde" se lui o un suo antenato (nel ramo) è tra le categorie scelte.
                // Normalmente si tengono i nodi che corrispondono; con "selezione inversa" si tolgono.
                $invert     = (int) $params->get('ricerca_filtri_categorie_inverti', 0) === 1;
                $candidates = array_filter($candidates, static function ($node) use ($all, $allowed, $invert): bool {
                    for ($n = $node; $n && $n->level >= 2; $n = $all[$n->parent_id] ?? null) {
                        if (isset($allowed[$n->title])) {
                            return !$invert;
                        }
                    }

                    return $invert;
                });
            }

            $withContent = self::nodesWithContent(array_keys($candidates));
            $nodes       = [];

            foreach ($candidates as $node) {
                $hasContent = isset($withContent[(int) $node->id]);

                if (!$hasContent) {
                    // Resta come intestazione solo se ha almeno un discendente selezionabile
                    $hasChild = false;

                    foreach ($candidates as $other) {
                        if ($other->lft > $node->lft && $other->rgt < $node->rgt && isset($withContent[(int) $other->id])) {
                            $hasChild = true;
                            break;
                        }
                    }

                    if (!$hasChild) {
                        continue;
                    }
                }

                $nodes[] = [
                    'id'         => (int) $node->id,
                    'title'      => $node->title,
                    // Categoria superiore: il rientro è solo visivo, il nome serve agli screen reader
                    'parent'     => ((int) $node->level > 2 && isset($all[$node->parent_id])) ? $all[$node->parent_id]->title : '',
                    'depth'      => max(0, (int) $node->level - 2),
                    'selectable' => $hasContent,
                    'checked'    => in_array((int) $node->id, $selected, true),
                ];
            }

            if ($nodes) {
                $groups[] = ['key' => 'category', 'label' => Text::_('TPL_ACCESSIBILE_SEARCH_FILTER_CATEGORY'), 'nodes' => $nodes];
            }
        }

        return $groups;
    }

    /**
     * Nome leggibile di un tipo di contenuto di Smart Search (nodo del ramo Type).
     *
     * Ordine: chiave del template TPL_ACCESSIBILE_SEARCH_TYPE_P_<TIPO> (plurale, per i filtri)
     * o _S_<TIPO> (singolare, sopra il titolo del risultato); poi la chiave del plugin
     * Smart Search (PLG_FINDER_QUERY_FILTER_BRANCH_…); infine il titolo del nodo così com'è.
     * Un'estensione che non traduce il proprio tipo (es. "Event") si rinomina quindi con una
     * chiave del template o con una sostituzione lingua di Joomla.
     */
    public static function typeLabel(string $title, bool $plural): string
    {
        $language = Factory::getApplication()->getLanguage();
        $suffix   = ($plural ? 'P_' : 'S_') . trim(preg_replace('/[^A-Z0-9]+/', '_', strtoupper($title)), '_');

        foreach (['TPL_ACCESSIBILE_SEARCH_TYPE_' . $suffix, 'PLG_FINDER_QUERY_FILTER_BRANCH_' . $suffix] as $key) {
            if ($language->hasKey($key)) {
                return Text::_($key);
            }
        }

        return $title;
    }

    /**
     * URL della pagina corrente con alcuni parametri cambiati, conservando tutti gli altri
     * (in particolare tutti i t[]: Query::toUri() del core ne conserva solo uno).
     * La paginazione riparte sempre dall'inizio.
     *
     * @param   array<string, mixed>  $changes  nome => valore; `null` rimuove il parametro
     *
     * @return  string  URL non ancora passato da htmlspecialchars
     */
    public static function urlWith(array $changes): string
    {
        $uri = clone Uri::getInstance();
        $uri->delVar('start');
        $uri->delVar('limitstart');

        foreach ($changes as $name => $value) {
            if ($value === null) {
                $uri->delVar($name);
            } else {
                $uri->setVar($name, $value);
            }
        }

        return $uri->toString(['path', 'query']);
    }

    /**
     * createQuery() esiste dal pacchetto database di Joomla 6; in 5.4 c'è solo getQuery(true),
     * deprecato in 6.
     */
    private static function newQuery(DatabaseInterface $db)
    {
        return method_exists($db, 'createQuery') ? $db->createQuery() : $db->getQuery(true);
    }

    /**
     * Titoli delle categorie di com_content scelte nei parametri, come insieme.
     * `null` = nessuna restrizione.
     *
     * L'abbinamento con i nodi di Smart Search è per titolo: gli id dei nodi cambiano
     * ogni volta che l'indice viene svuotato, quindi non si possono salvare nei parametri.
     *
     * @return  array<string, true>|null
     */
    private static function categoryTitles(array $ids): ?array
    {
        $ids = array_filter(array_map('intval', $ids));

        if (!$ids) {
            return null;
        }

        $db    = Factory::getContainer()->get(DatabaseInterface::class);
        $query = self::newQuery($db)
            ->select($db->quoteName('title'))
            ->from($db->quoteName('#__categories'))
            ->whereIn($db->quoteName('id'), $ids);

        return array_fill_keys($db->setQuery($query)->loadColumn(), true);
    }

    /**
     * Tra i nodi dati, quelli a cui è collegato almeno un contenuto indicizzato.
     *
     * @param   int[]  $nodeIds
     *
     * @return  array<int, true>
     */
    private static function nodesWithContent(array $nodeIds): array
    {
        if (!$nodeIds) {
            return [];
        }

        $db    = Factory::getContainer()->get(DatabaseInterface::class);
        $query = self::newQuery($db)
            ->select('DISTINCT ' . $db->quoteName('node_id'))
            ->from($db->quoteName('#__finder_taxonomy_map'))
            ->whereIn($db->quoteName('node_id'), array_map('intval', $nodeIds));

        return array_fill_keys(array_map('intval', $db->setQuery($query)->loadColumn()), true);
    }
}
