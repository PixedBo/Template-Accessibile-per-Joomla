<?php

/**
 * @package     Pixed.Plugin
 * @subpackage  SampleData.templateaccessibile
 *
 * @copyright   Copyright (C) 2025 Pixed Web Development. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Pixed\Plugin\SampleData\TemplateAccessibile\Profile\Comuni;

use Joomla\CMS\Language\Text;
use Pixed\Plugin\SampleData\TemplateAccessibile\Installer\Engine;

\defined('_JEXEC') or die;

/**
 * Profilo "Sito di un Comune": i blocchi installabili e i loro passi.
 *
 * Ogni blocco è autosufficiente: crea (o ritrova) ciò di cui ha bisogno, quindi i blocchi
 * possono essere eseguiti in qualunque ordine e quante volte si vuole.
 *
 * Per aggiungere un altro tipo di ente (Scuole, ASL, ...) si crea un profilo gemello in
 * src/Profile/<Ente>/ e lo si aggiunge all'elenco dei profili del plugin: il motore
 * (src/Installer) non cambia.
 */
final class ComuniProfile
{
    private const PREFIX = 'tplpa_comuni_';

    /**
     * Blocchi del profilo.
     *
     * @return  array<string, array{icon: string, component?: string, steps: array<int, array{method: string, acl: array<int, string>}>}>
     */
    public function blocks(): array
    {
        return [
            self::PREFIX . 'struttura' => ['icon' => 'folder-open', 'steps' => [
                ['method' => 'stepCategories', 'acl' => ['com_content']],
                ['method' => 'stepArticleFields', 'acl' => ['com_content', 'com_fields']],
            ]],
            self::PREFIX . 'menu' => ['icon' => 'list', 'steps' => [
                ['method' => 'stepMenus', 'acl' => ['com_content', 'com_menus']],
                ['method' => 'stepModules', 'acl' => ['com_modules']],
            ]],
            self::PREFIX . 'pagine' => ['icon' => 'file-alt', 'steps' => [
                ['method' => 'stepPages', 'acl' => ['com_content']],
            ]],
            self::PREFIX . 'dpcalendar' => ['icon' => 'calendar', 'component' => 'com_dpcalendar', 'steps' => [
                ['method' => 'stepEventFields', 'acl' => ['com_fields']],
            ]],
            self::PREFIX . 'demo' => ['icon' => 'star', 'steps' => [
                ['method' => 'stepDemo', 'acl' => ['com_content', 'com_fields', 'com_tags']],
            ]],
        ];
    }

    /**
     * Chiave lingua di un blocco: PLG_SAMPLEDATA_TEMPLATEACCESSIBILE_COMUNI_<BLOCCO>_(TITLE|DESC).
     */
    public function languageKey(string $block): string
    {
        return 'PLG_SAMPLEDATA_TEMPLATEACCESSIBILE_COMUNI_' . strtoupper(substr($block, \strlen(self::PREFIX)));
    }

    public function run(string $block, int $step, Engine $engine): void
    {
        $definition = $this->blocks()[$block]['steps'][$step - 1];
        $component  = $this->blocks()[$block]['component'] ?? null;

        // Blocco legato a un componente facoltativo (es. DPCalendar): senza, non si crea nulla
        if ($component !== null && !$engine->componentEnabled($component)) {
            throw new \RuntimeException(Text::sprintf('PLG_SAMPLEDATA_TEMPLATEACCESSIBILE_ERROR_ACL', $component));
        }

        foreach ($definition['acl'] as $component) {
            if (!$engine->componentEnabled($component) || !$engine->can('core.create', $component)) {
                throw new \RuntimeException(Text::sprintf('PLG_SAMPLEDATA_TEMPLATEACCESSIBILE_ERROR_ACL', $component));
            }
        }

        $this->{$definition['method']}($engine);
    }

    // ── Struttura ───────────────────────────────────────────────────────────

    /**
     * @return  array<string, int>  percorso di alias => ID categoria
     */
    private function stepCategories(Engine $engine): array
    {
        return $engine->ensureCategoryTree('com_content', Data::categories());
    }

    /**
     * Gruppo e campi della Scheda Servizio, più l'associazione nei parametri del template.
     *
     * @return  array<string, int>  nome del campo => ID
     */
    private function stepArticleFields(Engine $engine): array
    {
        $servizi = $engine->ensureCategory('com_content', 1, 'servizi', Data::categories()['servizi']['title']);
        $group   = $engine->ensureFieldGroup(Data::ARTICLE_CONTEXT, Data::ARTICLE_GROUP);

        if ($servizi === null || $group === null) {
            return [];
        }

        $documentType = is_file(JPATH_PLUGINS . '/fields/media/params/document.xml');
        $common       = ['context' => Data::ARTICLE_CONTEXT, 'group_id' => $group];
        $ids          = [];
        $params       = [];

        foreach (Data::articleFields($documentType) as $field) {
            $id = $engine->ensureField($field + $common + ['assigned_cat_ids' => [$servizi]]);

            if ($id !== null) {
                $ids[$field['name']]     = $id;
                $params[$field['param']] = $id;
            }
        }

        // Lista Tempistiche: come da documentazione, il Subform è assegnato a tutte le categorie
        $deadlines         = Data::articleDeadlines();
        [$subformId, $sub] = $engine->ensureSubform($deadlines['field'] + $common, $deadlines['subfields']);

        if ($subformId !== null) {
            $ids[$deadlines['field']['name']]     = $subformId;
            $params[$deadlines['field']['param']] = $subformId;

            // I sotto-campi vanno associati per ID: il layout li cerca come "field{ID}"
            foreach ($deadlines['subfields'] as $subfield) {
                $ids[$subfield['name']]     = $sub[$subfield['name']];
                $params[$subfield['param']] = $sub[$subfield['name']];
            }
        }

        $engine->fillTemplateParams($params);

        return $ids;
    }

    // ── Menu e moduli ───────────────────────────────────────────────────────

    private function stepMenus(Engine $engine): void
    {
        $categories = $this->stepCategories($engine);
        $params     = [];

        // Menu principale
        $engine->ensureMenuType(Data::MENU_MAIN, 'Menu principale (Comune)');

        foreach (Data::mainMenu() as $item) {
            $catId = $categories[$item['category']] ?? null;

            if ($catId === null) {
                continue;
            }

            $itemParams = [];

            if ($item['layout'] === 'vivere') {
                $itemParams = [
                    'vivere_cat_eventi' => (string) ($categories['vivere-il-comune/eventi'] ?? ''),
                    'vivere_cat_luoghi' => (string) ($categories['vivere-il-comune/luoghi'] ?? ''),
                ];
            }

            $params[$item['param']] = $engine->ensureMenuItem([
                'menutype'  => Data::MENU_MAIN,
                'alias'     => $item['alias'],
                'title'     => $item['title'],
                'component' => 'com_content',
                'link'      => 'index.php?option=com_content&view=category&layout=' . Engine::TEMPLATE . ':' . $item['layout'] . '&id=' . $catId,
                'params'    => $itemParams,
            ]);
        }

        // Menu secondario
        $engine->ensureMenuType(Data::MENU_SECONDARY, 'Menu secondario (Comune)');

        if ($engine->componentEnabled('com_tags')) {
            $params['de_all_topics'] = $engine->ensureMenuItem([
                'menutype'  => Data::MENU_SECONDARY,
                'alias'     => 'argomenti',
                'title'     => 'Tutti gli argomenti',
                'component' => 'com_tags',
                'link'      => 'index.php?option=com_tags&view=tags',
            ]);
        }

        // Menu footer: una voce per ogni pagina istituzionale già presente
        $engine->ensureMenuType(Data::MENU_FOOTER, 'Menu footer (Comune)');

        $pagesCategory = $engine->findCategory('com_content', 1, Data::PAGES_CATEGORY);
        $missing       = 0;

        foreach (Data::pages() as $page) {
            $articleId = $pagesCategory ? $engine->articleId((int) $pagesCategory->id, $page['alias']) : null;

            if ($articleId === null) {
                $missing++;
                continue;
            }

            $itemId = $engine->ensureMenuItem([
                'menutype'  => Data::MENU_FOOTER,
                'alias'     => $page['alias'],
                'title'     => $page['title'],
                'component' => 'com_content',
                'link'      => 'index.php?option=com_content&view=article&id=' . $articleId,
            ]);

            foreach ($page['params'] as $param) {
                $params[$param] = $itemId;
            }
        }

        if ($missing > 0) {
            $engine->report->note(Text::sprintf('PLG_SAMPLEDATA_TEMPLATEACCESSIBILE_COMUNI_NOTE_PAGES_MISSING', $missing));
        }

        $engine->fillTemplateParams($params);
    }

    private function stepModules(Engine $engine): void
    {
        $menu = static fn (string $menutype, string $layout): array => [
            'menutype'        => $menutype,
            'base'            => '',
            'startLevel'      => 1,
            'endLevel'        => 0,
            'showAllChildren' => 1,
            'layout'          => Engine::TEMPLATE . ':' . $layout,
            'cache'           => 1,
        ];

        $engine->ensureModule([
            'module' => 'mod_menu', 'position' => 'menu-principale', 'title' => 'Menu principale (Comune)',
            'params' => $menu(Data::MENU_MAIN, 'comuni-menu'),
        ]);
        $engine->ensureModule([
            'module' => 'mod_menu', 'position' => 'menu-secondario', 'title' => 'Menu secondario (Comune)',
            'params' => $menu(Data::MENU_SECONDARY, 'comuni-menu'),
        ]);
        $engine->ensureModule([
            'module' => 'mod_menu', 'position' => 'footer1', 'title' => 'Informazioni', 'showtitle' => 1,
            'params' => $menu(Data::MENU_FOOTER, 'comuni-footer'),
        ]);
        $engine->ensureModule([
            'module' => 'mod_breadcrumbs', 'position' => 'percorso', 'title' => 'Percorso (Comune)',
            'params' => ['showHere' => 0, 'showHome' => 1, 'homeText' => '', 'showLast' => 1, 'layout' => '_:default'],
        ]);
    }

    // ── Pagine istituzionali ────────────────────────────────────────────────

    private function stepPages(Engine $engine): void
    {
        $catId = $engine->ensureCategory('com_content', 1, Data::PAGES_CATEGORY, 'Pagine istituzionali');

        if ($catId === null) {
            return;
        }

        foreach (Data::pages() as $page) {
            $engine->ensureArticle([
                'catid'     => $catId,
                'alias'     => $page['alias'],
                'title'     => $page['title'],
                'introtext' => Data::placeholderText($page['title']),
                'attribs'   => isset($page['layout']) ? ['article_layout' => Engine::TEMPLATE . ':' . $page['layout']] : [],
            ]);
        }
    }

    // ── Campi DPCalendar ────────────────────────────────────────────────────

    private function stepEventFields(Engine $engine): void
    {
        $group = $engine->ensureFieldGroup(Data::EVENT_CONTEXT, Data::EVENT_GROUP);

        if ($group === null) {
            return;
        }

        $common = ['context' => Data::EVENT_CONTEXT, 'group_id' => $group];
        $params = [];

        foreach (Data::eventFields() as $field) {
            $params[$field['param']] = $engine->ensureField($field + $common);
        }

        foreach (Data::eventSubforms() as $subform) {
            [$id]                               = $engine->ensureSubform($subform['field'] + $common, $subform['subfields']);
            $params[$subform['field']['param']] = $id;
        }

        $engine->fillTemplateParams($params);
    }

    // ── Contenuti dimostrativi ──────────────────────────────────────────────

    private function stepDemo(Engine $engine): void
    {
        $categories = $this->stepCategories($engine);
        $fields     = $this->stepArticleFields($engine);
        $tagId      = $engine->ensureTag('anagrafe', 'Anagrafe');

        // Scheda servizio compilata
        $service = Data::demoService();
        $catId   = $categories[$service['category']] ?? null;

        if ($catId !== null) {
            [$articleId, $created] = $engine->ensureArticle([
                'catid'     => $catId,
                'alias'     => $service['alias'],
                'title'     => $service['title'],
                'introtext' => $service['introtext'],
                'fulltext'  => $service['fulltext'],
                'featured'  => 1,
                'tags'      => $tagId ? [$tagId] : [],
                'attribs'   => ['article_layout' => Engine::TEMPLATE . ':scheda-servizio'],
            ]);

            // I valori dei campi si scrivono sull'articolo appena creato, oppure su uno esistente che
            // non ne ha nessuno (creazione interrotta prima di scriverli). Un articolo che ha già
            // dei valori può essere stato modificato dall'amministratore e non viene toccato.
            if ($articleId && ($created || !$engine->hasFieldValues($articleId))) {
                $values = [];

                foreach ($service['values'] as $name => $value) {
                    if (isset($fields[$name])) {
                        $values[$fields[$name]] = $value;
                    }
                }

                $subformName = Data::articleDeadlines()['field']['name'];

                if (isset($fields[$subformName])) {
                    $rows = [];

                    foreach ($service['deadlines'] as $index => $row) {
                        foreach ($row as $name => $value) {
                            if (isset($fields[$name])) {
                                $rows['row' . $index]['field' . $fields[$name]] = $value;
                            }
                        }
                    }

                    if ($rows) {
                        $values[$fields[$subformName]] = json_encode($rows, JSON_UNESCAPED_UNICODE);
                    }
                }

                $engine->addFieldValues($articleId, $values);
            }
        }

        // Notizie
        foreach (Data::demoNews() as $news) {
            $catId = $categories[$news['category']] ?? null;

            if ($catId !== null) {
                $engine->ensureArticle([
                    'catid'     => $catId,
                    'alias'     => $news['alias'],
                    'title'     => $news['title'],
                    'introtext' => $news['introtext'],
                    'fulltext'  => $news['fulltext'],
                    'featured'  => 1,
                ]);
            }
        }
    }
}
