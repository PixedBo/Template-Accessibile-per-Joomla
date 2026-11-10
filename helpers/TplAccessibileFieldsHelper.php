<?php
/**
 * Lettura dei campi personalizzati mappati nei parametri del template.
 *
 * Usato dai layout che associano un campo (scelto dall'amministratore nei parametri
 * del template) a una sezione fissa della pagina, es. la Scheda Evento.
 */
defined('_JEXEC') or die;

use Joomla\CMS\Helper\MediaHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\Registry\Registry;

class TplAccessibileFieldsHelper
{
    /**
     * Reindicizza i campi per ID, senza fidarsi delle chiavi dell'array di partenza
     * (com_content usa l'ID, altri componenti possono usare indici numerici o il nome).
     *
     * @param   iterable|null  $jcfields  $item->jcfields
     *
     * @return  array<int, object>
     */
    public static function index($jcfields): array
    {
        $index = [];

        if (!is_iterable($jcfields)) {
            return $index;
        }

        foreach ($jcfields as $field) {
            if (is_object($field) && isset($field->id)) {
                $index[(int) $field->id] = $field;
            }
        }

        return $index;
    }

    /**
     * Campo associato a un parametro del template, o null se non mappato / non presente.
     */
    public static function mapped(array $index, Registry $tplParams, string $param): ?object
    {
        $id = (int) $tplParams->get($param, 0);

        return ($id && isset($index[$id])) ? $index[$id] : null;
    }

    /**
     * Righe di un campo Subform.
     *
     * Ogni riga è la lista ordinata dei sotto-campi (oggetti con id, name, value, rawvalue),
     * nell'ordine in cui sono definiti nel Subform. Fonte primaria: $field->subform_rows,
     * valorizzato dal plugin fields/subform in onCustomFieldsPrepareField. Se manca, si
     * ripiega sul rawvalue JSON ({"row0": {"field12": "..."}}).
     *
     * @return  array<int, array<int, object>>
     */
    public static function rows(?object $field): array
    {
        if ($field === null) {
            return [];
        }

        if (!empty($field->subform_rows) && is_array($field->subform_rows)) {
            return array_values(array_map(
                static fn ($row): array => array_values(array_filter((array) $row, 'is_object')),
                $field->subform_rows
            ));
        }

        $raw = $field->rawvalue ?? '';
        $raw = is_string($raw) ? json_decode($raw, true) : $raw;

        if (!is_array($raw)) {
            return [];
        }

        $rows = [];

        foreach ($raw as $row) {
            if (!is_array($row)) {
                continue;
            }

            $cells = [];

            foreach ($row as $key => $value) {
                $cells[] = (object) [
                    'id'       => (int) preg_replace('/\D+/', '', (string) $key),
                    'name'     => (string) $key,
                    'value'    => $value,
                    'rawvalue' => $value,
                ];
            }

            $rows[] = $cells;
        }

        return $rows;
    }

    /**
     * Sotto-campo di una riga: per ID se la chiave è mappata nei parametri del template,
     * altrimenti per posizione (ordine dei sotto-campi nel Subform).
     */
    public static function cell(array $row, int $keyId, int $position): ?object
    {
        if ($keyId > 0) {
            foreach ($row as $cell) {
                if ((int) ($cell->id ?? 0) === $keyId) {
                    return $cell;
                }
            }

            return null;
        }

        return $row[$position] ?? null;
    }

    /**
     * Valore grezzo di un sotto-campo come testo semplice (stringa vuota se non scalare).
     */
    public static function cellText(array $row, int $keyId, int $position): string
    {
        $cell = self::cell($row, $keyId, $position);
        $raw  = $cell->rawvalue ?? '';

        return is_scalar($raw) ? trim((string) $raw) : '';
    }

    /**
     * Estrae URL e testo del link dal valore grezzo di un campo.
     *
     * Formati supportati (plg_fields_media + AccessiblemediaField):
     * - campo URL / testo: stringa, assoluta o relativa alla root del sito;
     * - Media "Documento" (Joomla 6.1+): {"file": "...", "linktext": "..."}, stringa JSON o array;
     * - Media immagine: {"imagefile": "...", "alt_text": "..."}, stringa JSON o array.
     *
     * Sono ammessi solo http, https, mailto, tel e percorsi relativi: il valore finisce in un href.
     *
     * @param   mixed  $raw  rawvalue del campo
     *
     * @return  array{url: string, text: string}
     */
    public static function safeLink($raw): array
    {
        $info = ['url' => '', 'text' => ''];

        if (is_object($raw)) {
            $raw = (array) $raw;
        }

        if (is_string($raw)) {
            $raw = trim($raw);

            if ($raw === '') {
                return $info;
            }

            $decoded = json_decode($raw, true);
            $raw     = is_array($decoded) ? $decoded : $raw;
        }

        if (is_array($raw)) {
            $url          = (string) ($raw['file'] ?? $raw['imagefile'] ?? '');
            $info['text'] = trim((string) ($raw['linktext'] ?? $raw['alt_text'] ?? ''));
        } elseif (is_string($raw)) {
            $url = $raw;
        } else {
            return $info;
        }

        // Rimuove il suffisso "#joomlaImage://..." aggiunto dal Media Manager
        $url = trim((string) MediaHelper::getCleanMediaFieldValue($url));

        // Un URL non contiene spazi né markup: scarta testo libero o HTML finiti in un campo mappato per errore
        if ($url === '' || preg_match('/[\s<>"]/', $url)) {
            return $info;
        }

        if (preg_match('#^[a-z][a-z0-9+.-]*:#i', $url)) {
            if (!preg_match('#^(?:https?://|mailto:|tel:)#i', $url)) {
                return $info;
            }
        } elseif (preg_match('#^/[/\\\\]#', $url)) {
            // "//host" e "/\host" non sono percorsi del sito: il browser li apre su un altro dominio
            return $info;
        } elseif ($url[0] !== '/') {
            $url = Uri::root() . ltrim($url, '/');
        }

        $info['url'] = $url;

        return $info;
    }
}
