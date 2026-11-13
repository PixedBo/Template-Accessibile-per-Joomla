<?php

/**
 * @package     Pixed.Plugin
 * @subpackage  SampleData.templateaccessibile
 *
 * @copyright   Copyright (C) 2025 Pixed Web Development. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Pixed\Plugin\SampleData\TemplateAccessibile\Installer;

use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Helper\LibraryHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\MenuType;
use Joomla\Database\DatabaseInterface;
use Joomla\Database\ParameterType;
use Joomla\Registry\Registry;

\defined('_JEXEC') or die;

/**
 * Motore "trova o crea".
 *
 * Regola unica per tutti i metodi ensure*(): l'elemento viene cercato con la stessa chiave che
 * Joomla usa per l'unicità, in qualunque stato. Se esiste non viene mai modificato: se è
 * utilizzabile se ne restituisce l'ID, se è nel cestino viene segnalato e si restituisce null.
 * Solo se non esiste viene creato, con i model del core.
 *
 * Nessun ID viene conservato tra una richiesta e l'altra: tutto si rilegge dal database, quindi
 * un'esecuzione interrotta si riprende rilanciandola.
 */
final class Engine
{
    /** Template a cui appartengono layout e parametri. */
    public const TEMPLATE = 'templateaccessibileperjoomla';

    private const TRASHED = -2;

    public readonly int $access;

    public readonly string $language;

    public readonly int $userId;

    public function __construct(
        public readonly CMSApplicationInterface $app,
        public readonly DatabaseInterface $db,
        public readonly Report $report
    ) {
        $this->access   = (int) $app->get('access', 1);
        $this->language = Multilanguage::isEnabled() ? $app->getLanguage()->getTag() : '*';
        $this->userId   = (int) $app->getIdentity()->id;

        // Evita che il debug della lingua aggiunga marcatori alle stringhe salvate
        $app->getLanguage()->setDebug(false);
    }

    public function can(string $action, string $asset): bool
    {
        return (bool) $this->app->getIdentity()->authorise($action, $asset);
    }

    public function componentEnabled(string $component): bool
    {
        return ComponentHelper::isEnabled($component);
    }

    private function componentId(string $component): int
    {
        return (int) ComponentHelper::getComponent($component)->id;
    }

    /**
     * Esegue il salvataggio con un model nuovo. Un model riusato conserva l'ID dell'ultimo
     * salvataggio nel proprio stato e, con id = 0 nei dati, aggiornerebbe quel record.
     *
     * @return  int  ID del record creato
     */
    private function save(string $component, string $model, array $data): int
    {
        $instance = $this->app->bootComponent($component)->getMVCFactory()
            ->createModel($model, 'Administrator', ['ignore_request' => true]);

        if (!$instance->save($data)) {
            $this->app->getLanguage()->load($component, JPATH_ADMINISTRATOR);

            throw new \RuntimeException(
                ($data['title'] ?? $data['name'] ?? $model) . ': ' . Text::_((string) $instance->getError())
            );
        }

        $id = (int) $instance->getState(strtolower($model) . '.id');

        if (!$id) {
            throw new \RuntimeException(($data['title'] ?? $model) . ': ID non restituito dal model ' . $model);
        }

        return $id;
    }

    /**
     * Esito comune della ricerca: restituisce [trovato, id utilizzabile o null].
     */
    private function found(?object $row, string $kind, string $label, string $stateColumn): ?array
    {
        if (!$row) {
            return null;
        }

        if ((int) $row->{$stateColumn} === self::TRASHED) {
            $this->report->note(Text::sprintf('PLG_SAMPLEDATA_TEMPLATEACCESSIBILE_NOTE_TRASHED', $label));

            return [true, null];
        }

        $this->report->count($kind, Report::EXISTING);

        return [true, (int) $row->id];
    }

    // ── Categorie ───────────────────────────────────────────────────────────

    public function findCategory(string $extension, int $parentId, string $alias): ?object
    {
        $query = $this->db->getQuery(true)
            ->select($this->db->quoteName(['id', 'published']))
            ->from($this->db->quoteName('#__categories'))
            ->where($this->db->quoteName('extension') . ' = :extension')
            ->where($this->db->quoteName('parent_id') . ' = :parent')
            ->where($this->db->quoteName('alias') . ' = :alias')
            ->bind(':extension', $extension)
            ->bind(':parent', $parentId, ParameterType::INTEGER)
            ->bind(':alias', $alias);

        return $this->db->setQuery($query)->loadObject() ?: null;
    }

    public function ensureCategory(string $extension, int $parentId, string $alias, string $title): ?int
    {
        if ($result = $this->found($this->findCategory($extension, $parentId, $alias), 'categories', $title, 'published')) {
            return $result[1];
        }

        // CategoryModel prende l'estensione dallo stato quando costruisce il contesto
        $id = $this->save('com_categories', 'Category', [
            'id'              => 0,
            'title'           => $title,
            'alias'           => $alias,
            'parent_id'       => $parentId,
            'extension'       => $extension,
            'published'       => 1,
            'access'          => $this->access,
            'created_user_id' => $this->userId,
            'language'        => $this->language,
            'description'     => '',
            'associations'    => [],
            'params'          => [],
        ]);

        $this->report->count('categories', Report::CREATED);

        return $id;
    }

    /**
     * Crea un albero di categorie.
     *
     * @param   array  $tree  [alias => ['title' => ..., 'children' => [alias => ...]]]
     *
     * @return  array<string, int>  Mappa "percorso di alias" => ID (es. "servizi/ambiente")
     */
    public function ensureCategoryTree(string $extension, array $tree, int $parentId = 1, string $prefix = ''): array
    {
        $map = [];

        foreach ($tree as $alias => $node) {
            $id = $this->ensureCategory($extension, $parentId, $alias, $node['title']);

            if ($id === null) {
                // Genitore nel cestino: il ramo viene saltato
                continue;
            }

            $map[$prefix . $alias] = $id;

            if (!empty($node['children'])) {
                $map += $this->ensureCategoryTree($extension, $node['children'], $id, $prefix . $alias . '/');
            }
        }

        return $map;
    }

    // ── Campi personalizzati ────────────────────────────────────────────────

    public function ensureFieldGroup(string $context, string $title): ?int
    {
        $query = $this->db->getQuery(true)
            ->select($this->db->quoteName(['id', 'state']))
            ->from($this->db->quoteName('#__fields_groups'))
            ->where($this->db->quoteName('context') . ' = :context')
            ->where($this->db->quoteName('title') . ' = :title')
            ->bind(':context', $context)
            ->bind(':title', $title);

        if ($result = $this->found($this->db->setQuery($query)->loadObject() ?: null, 'fieldgroups', $title, 'state')) {
            return $result[1];
        }

        $id = $this->save('com_fields', 'Group', [
            'id'              => 0,
            'title'           => $title,
            'context'         => $context,
            'state'           => 1,
            'ordering'        => 0,
            'note'            => '',
            'description'     => '',
            'access'          => $this->access,
            'created_user_id' => $this->userId,
            'language'        => $this->language,
            'params'          => ['display_readonly' => '1'],
        ]);

        $this->report->count('fieldgroups', Report::CREATED);

        return $id;
    }

    public function findField(string $name): ?object
    {
        // Il nome di un campo è unico in tutto il sito, non per contesto
        $query = $this->db->getQuery(true)
            ->select($this->db->quoteName(['id', 'state', 'context']))
            ->from($this->db->quoteName('#__fields'))
            ->where($this->db->quoteName('name') . ' = :name')
            ->bind(':name', $name);

        return $this->db->setQuery($query)->loadObject() ?: null;
    }

    /**
     * @param   array  $def  name, title, type, context, group_id, fieldparams, default_value,
     *                       assigned_cat_ids (array di ID; vuoto = tutte), only_use_in_subform
     */
    public function ensureField(array $def): ?int
    {
        $row = $this->findField($def['name']);

        if ($row && $row->context !== $def['context']) {
            $this->report->note(Text::sprintf('PLG_SAMPLEDATA_TEMPLATEACCESSIBILE_NOTE_FIELD_CONFLICT', $def['title'], $def['name']));

            return null;
        }

        if ($result = $this->found($row, 'fields', $def['title'], 'state')) {
            return $result[1];
        }

        // FieldModel legge il contesto dalla richiesta per alcune verifiche
        $this->app->getInput()->set('context', $def['context']);

        $id = $this->save('com_fields', 'Field', [
            'id'                  => 0,
            'name'                => $def['name'],
            'title'               => $def['title'],
            'label'               => $def['title'],
            'type'                => $def['type'],
            'context'             => $def['context'],
            'group_id'            => (int) ($def['group_id'] ?? 0),
            'description'         => '',
            'note'                => '',
            'default_value'       => $def['default_value'] ?? '',
            'required'            => 0,
            'only_use_in_subform' => (int) ($def['only_use_in_subform'] ?? 0),
            'ordering'            => 0,
            'state'               => 1,
            'access'              => $this->access,
            'language'            => $this->language,
            'created_user_id'     => $this->userId,
            'assigned_cat_ids'    => $def['assigned_cat_ids'] ?? [],
            'fieldparams'         => $def['fieldparams'] ?? [],
            'params'              => [
                'hint'               => '',
                'class'              => '',
                'label_class'        => '',
                'show_on'            => '',
                'showon'             => '',
                'render_class'       => '',
                'value_render_class' => '',
                'showlabel'          => '1',
                'label_render_class' => '',
                // 0 = non mostrare automaticamente: i layout del template stampano il campo dove serve
                'display'            => '0',
                'prefix'             => '',
                'suffix'             => '',
                'layout'             => '',
                'display_readonly'   => '2',
            ],
        ]);

        $this->report->count('fields', Report::CREATED);

        return $id;
    }

    /**
     * Crea un campo Subform e i suoi sotto-campi.
     *
     * @param   array  $def        Definizione del campo padre (senza fieldparams)
     * @param   array  $subfields  Definizioni dei sotto-campi, nell'ordine in cui vanno letti
     *
     * @return  array{0: ?int, 1: array<string, int>}  ID del Subform e mappa nome => ID dei sotto-campi
     */
    public function ensureSubform(array $def, array $subfields): array
    {
        $ids     = [];
        $options = [];

        foreach ($subfields as $index => $sub) {
            $id = $this->ensureField($sub + [
                'context'             => $def['context'],
                'group_id'            => $def['group_id'] ?? 0,
                'only_use_in_subform' => 1,
            ]);

            if ($id === null) {
                // Senza un sotto-campo l'ordine non corrisponderebbe più a quello atteso dal template
                $this->report->note(Text::sprintf('PLG_SAMPLEDATA_TEMPLATEACCESSIBILE_NOTE_SUBFORM_SKIPPED', $def['title']));

                return [null, $ids];
            }

            $ids[$sub['name']]         = $id;
            $options['option' . $index] = ['customfield' => $id, 'render_values' => '1'];
        }

        $def['fieldparams'] = ['repeat' => '1', 'max_rows' => '', 'options' => $options];

        return [$this->ensureField($def), $ids];
    }

    // ── Menu ────────────────────────────────────────────────────────────────

    public function ensureMenuType(string $menutype, string $title): bool
    {
        $query = $this->db->getQuery(true)
            ->select('COUNT(*)')
            ->from($this->db->quoteName('#__menu_types'))
            ->where($this->db->quoteName('menutype') . ' = :menutype')
            ->bind(':menutype', $menutype);

        if ((int) $this->db->setQuery($query)->loadResult() > 0) {
            $this->report->count('menus', Report::EXISTING);

            return true;
        }

        $table = new MenuType($this->db);
        $data  = ['id' => 0, 'menutype' => $menutype, 'title' => $title, 'description' => '', 'client_id' => 0];

        if (!$table->bind($data) || !$table->check() || !$table->store()) {
            throw new \RuntimeException($title . ': ' . Text::_((string) $table->getError()));
        }

        $this->report->count('menus', Report::CREATED);

        return true;
    }

    /**
     * @param   array  $def  menutype, alias, title, link, component (es. com_content), params
     */
    public function ensureMenuItem(array $def): ?int
    {
        // Joomla impone l'unicità di alias + genitore + client + lingua, qualunque sia il menu
        $query = $this->db->getQuery(true)
            ->select($this->db->quoteName(['id', 'published', 'menutype']))
            ->from($this->db->quoteName('#__menu'))
            ->where($this->db->quoteName('client_id') . ' = 0')
            ->where($this->db->quoteName('parent_id') . ' = 1')
            ->where($this->db->quoteName('alias') . ' = :alias')
            ->bind(':alias', $def['alias']);

        $row = $this->db->setQuery($query)->loadObject() ?: null;

        if ($row && $row->menutype !== $def['menutype']) {
            $this->report->note(Text::sprintf('PLG_SAMPLEDATA_TEMPLATEACCESSIBILE_NOTE_MENUITEM_CONFLICT', $def['title'], $def['alias'], $row->menutype));

            return null;
        }

        if ($result = $this->found($row, 'menuitems', $def['title'], 'published')) {
            return $result[1];
        }

        $id = $this->save('com_menus', 'Item', [
            'id'                => 0,
            'menutype'          => $def['menutype'],
            'title'             => $def['title'],
            'alias'             => $def['alias'],
            'link'              => $def['link'],
            'type'              => 'component',
            'component_id'      => $this->componentId($def['component']),
            'params'            => $def['params'] ?? [],
            'parent_id'         => 1,
            'level'             => 1,
            'published'         => 1,
            'home'              => 0,
            'browserNav'        => 0,
            'client_id'         => 0,
            'template_style_id' => 0,
            'access'            => $this->access,
            'language'          => $this->language,
            'note'              => '',
            'img'               => '',
            'associations'      => [],
        ]);

        $this->report->count('menuitems', Report::CREATED);

        return $id;
    }

    // ── Moduli ──────────────────────────────────────────────────────────────

    /**
     * @param   array  $def  module, position, title, params, showtitle
     */
    public function ensureModule(array $def): ?int
    {
        $query = $this->db->getQuery(true)
            ->select($this->db->quoteName(['id', 'published']))
            ->from($this->db->quoteName('#__modules'))
            ->where($this->db->quoteName('client_id') . ' = 0')
            ->where($this->db->quoteName('module') . ' = :module')
            ->where($this->db->quoteName('position') . ' = :position')
            ->where($this->db->quoteName('title') . ' = :title')
            ->bind(':module', $def['module'])
            ->bind(':position', $def['position'])
            ->bind(':title', $def['title']);

        if ($result = $this->found($this->db->setQuery($query)->loadObject() ?: null, 'modules', $def['title'], 'published')) {
            return $result[1];
        }

        // Sito già avviato: se nella posizione è pubblicato un modulo dello stesso tipo, il nuovo
        // nasce sospeso. Affiancarlo mostrerebbe subito ai visitatori due menu (o due percorsi).
        $query = $this->db->getQuery(true)
            ->select('COUNT(*)')
            ->from($this->db->quoteName('#__modules'))
            ->where($this->db->quoteName('client_id') . ' = 0')
            ->where($this->db->quoteName('published') . ' = 1')
            ->where($this->db->quoteName('module') . ' = :module')
            ->where($this->db->quoteName('position') . ' = :position')
            ->bind(':module', $def['module'])
            ->bind(':position', $def['position']);

        $taken = (int) $this->db->setQuery($query)->loadResult() > 0;

        if ($taken) {
            $this->report->note(Text::sprintf('PLG_SAMPLEDATA_TEMPLATEACCESSIBILE_NOTE_MODULE_UNPUBLISHED', $def['title'], $def['position']));
        }

        $id = $this->save('com_modules', 'Module', [
            'id'         => 0,
            'asset_id'   => 0,
            'title'      => $def['title'],
            'module'     => $def['module'],
            'position'   => $def['position'],
            'params'     => $def['params'] ?? [],
            'showtitle'  => (int) ($def['showtitle'] ?? 0),
            'content'    => '',
            'note'       => '',
            'ordering'   => 1,
            'published'  => $taken ? 0 : 1,
            'client_id'  => 0,
            'access'     => $this->access,
            'language'   => $this->language,
            // 0 = su tutte le pagine
            'assignment' => 0,
        ]);

        $this->report->count('modules', Report::CREATED);

        return $id;
    }

    // ── Articoli e tag ──────────────────────────────────────────────────────

    public function findArticle(int $catId, string $alias): ?object
    {
        $query = $this->db->getQuery(true)
            ->select($this->db->quoteName(['id', 'state']))
            ->from($this->db->quoteName('#__content'))
            ->where($this->db->quoteName('catid') . ' = :catid')
            ->where($this->db->quoteName('alias') . ' = :alias')
            ->bind(':catid', $catId, ParameterType::INTEGER)
            ->bind(':alias', $alias);

        return $this->db->setQuery($query)->loadObject() ?: null;
    }

    /**
     * ID di un articolo esistente e utilizzabile, senza crearlo e senza toccare il resoconto.
     */
    public function articleId(int $catId, string $alias): ?int
    {
        $row = $this->findArticle($catId, $alias);

        return ($row && (int) $row->state !== self::TRASHED) ? (int) $row->id : null;
    }

    /**
     * @param   array  $def  catid, alias, title, introtext, fulltext, featured, attribs, tags
     *
     * @return  array{0: ?int, 1: bool}  ID e "creato in questa esecuzione"
     */
    public function ensureArticle(array $def): array
    {
        if ($result = $this->found($this->findArticle((int) $def['catid'], $def['alias']), 'articles', $def['title'], 'state')) {
            return [$result[1], false];
        }

        $id = $this->save('com_content', 'Article', [
            'id'               => 0,
            'catid'            => (int) $def['catid'],
            'title'            => $def['title'],
            'alias'            => $def['alias'],
            'introtext'        => $def['introtext'] ?? '',
            'fulltext'         => $def['fulltext'] ?? '',
            'state'            => 1,
            'featured'         => (int) ($def['featured'] ?? 0),
            'attribs'          => $def['attribs'] ?? [],
            // Stringhe: il comportamento Taggable distingue così gli ID dai tag nuovi ("#new#...")
            'tags'             => array_map('strval', $def['tags'] ?? []),
            'images'           => [],
            'urls'             => [],
            'metadata'         => [],
            'metakey'          => '',
            'metadesc'         => '',
            'ordering'         => 0,
            'created_user_id'  => $this->userId,
            'created_by_alias' => '',
            'access'           => $this->access,
            'language'         => $this->language,
            'associations'     => [],
        ]);

        $this->report->count('articles', Report::CREATED);

        return [$id, true];
    }

    public function ensureTag(string $alias, string $title): ?int
    {
        $query = $this->db->getQuery(true)
            ->select($this->db->quoteName(['id', 'published']))
            ->from($this->db->quoteName('#__tags'))
            ->where($this->db->quoteName('alias') . ' = :alias')
            ->bind(':alias', $alias);

        if ($result = $this->found($this->db->setQuery($query)->loadObject() ?: null, 'tags', $title, 'published')) {
            return $result[1];
        }

        $id = $this->save('com_tags', 'Tag', [
            'id'              => 0,
            'title'           => $title,
            'alias'           => $alias,
            'parent_id'       => 1,
            'published'       => 1,
            'access'          => $this->access,
            'created_user_id' => $this->userId,
            'language'        => $this->language,
            'description'     => '',
        ]);

        $this->report->count('tags', Report::CREATED);

        return $id;
    }

    /**
     * true se l'elemento ha almeno un valore salvato in un campo personalizzato.
     */
    public function hasFieldValues(int $itemId): bool
    {
        $item  = (string) $itemId;
        $query = $this->db->getQuery(true)
            ->select('COUNT(*)')
            ->from($this->db->quoteName('#__fields_values'))
            ->where($this->db->quoteName('item_id') . ' = :item')
            ->bind(':item', $item);

        return (int) $this->db->setQuery($query)->loadResult() > 0;
    }

    /**
     * Scrive i valori dei campi di un articolo appena creato. Non sovrascrive valori esistenti.
     *
     * @param   array<int, string>  $values  ID campo => valore
     */
    public function addFieldValues(int $itemId, array $values): void
    {
        foreach ($values as $fieldId => $value) {
            $fieldId = (int) $fieldId;
            $item    = (string) $itemId;

            $query = $this->db->getQuery(true)
                ->select('COUNT(*)')
                ->from($this->db->quoteName('#__fields_values'))
                ->where($this->db->quoteName('field_id') . ' = :field')
                ->where($this->db->quoteName('item_id') . ' = :item')
                ->bind(':field', $fieldId, ParameterType::INTEGER)
                ->bind(':item', $item);

            if (!$fieldId || (int) $this->db->setQuery($query)->loadResult() > 0) {
                continue;
            }

            $row = (object) ['field_id' => $fieldId, 'item_id' => $item, 'value' => $value];
            $this->db->insertObject('#__fields_values', $row);
        }
    }

    // ── Parametri del template ──────────────────────────────────────────────

    /**
     * Scrive nei parametri degli stili del template (e dei template figli) gli ID indicati,
     * solo dove il parametro non è ancora valorizzato con un ID.
     *
     * @param   array<string, ?int>  $values  parametro => ID (i null vengono ignorati)
     */
    public function fillTemplateParams(array $values): void
    {
        $values = array_filter($values, static fn ($id) => (int) $id > 0);

        if (!$values) {
            return;
        }

        if (!$this->can('core.manage', 'com_templates') || !$this->can('core.edit', 'com_templates')) {
            $this->report->note(Text::_('PLG_SAMPLEDATA_TEMPLATEACCESSIBILE_NOTE_TEMPLATE_ACL'));

            return;
        }

        $template = self::TEMPLATE;
        $query    = $this->db->getQuery(true)
            ->select($this->db->quoteName(['id', 'params']))
            ->from($this->db->quoteName('#__template_styles'))
            ->where($this->db->quoteName('client_id') . ' = 0')
            ->extendWhere('AND', [
                $this->db->quoteName('template') . ' = :template',
                $this->db->quoteName('parent') . ' = :parent',
            ], 'OR')
            ->bind(':template', $template)
            ->bind(':parent', $template);

        $styles = $this->db->setQuery($query)->loadObjectList();

        if (!$styles) {
            $this->report->note(Text::_('PLG_SAMPLEDATA_TEMPLATEACCESSIBILE_NOTE_TEMPLATE_MISSING'));

            return;
        }

        foreach ($styles as $style) {
            $params  = new Registry($style->params);
            $changed = false;

            foreach ($values as $name => $id) {
                // Un parametro conta come vuoto se non contiene un ID: copre anche i vecchi
                // valori predefiniti testuali ("titolo", "data", ...)
                if ((int) $params->get($name, 0) > 0) {
                    $this->report->count('templateparams', Report::EXISTING);
                    continue;
                }

                $params->set($name, (string) $id);
                $this->report->count('templateparams', Report::CREATED);
                $changed = true;
            }

            if ($changed) {
                $row = (object) ['id' => (int) $style->id, 'params' => $params->toString()];
                $this->db->updateObject('#__template_styles', $row, 'id');
            }
        }
    }
}
