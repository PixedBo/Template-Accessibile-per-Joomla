<?php

/**
 * @package     Pixed.Plugin
 * @subpackage  SampleData.templateaccessibile
 *
 * @copyright   Copyright (C) 2025 Pixed Web Development. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Pixed\Plugin\SampleData\TemplateAccessibile\Installer;

use Joomla\CMS\Language\Text;

\defined('_JEXEC') or die;

/**
 * Resoconto di un passo: quanti elementi sono stati creati, quanti erano già presenti
 * e quali sono stati saltati (e perché).
 */
final class Report
{
    public const CREATED  = 'created';
    public const EXISTING = 'existing';

    /** @var array<string, array{created: int, existing: int}> */
    private array $counts = [];

    /** @var string[] */
    private array $notes = [];

    /**
     * @param   string  $kind    Chiave del tipo di oggetto (categories, fields, menuitems, ...)
     * @param   string  $status  self::CREATED | self::EXISTING
     */
    public function count(string $kind, string $status): void
    {
        $this->counts[$kind] ??= [self::CREATED => 0, self::EXISTING => 0];
        $this->counts[$kind][$status]++;
    }

    /**
     * Avviso mostrato in fondo al resoconto (elemento saltato, conflitto, permesso mancante).
     */
    public function note(string $text): void
    {
        $this->notes[] = $text;
    }

    /**
     * @return  string[]
     */
    public function notes(): array
    {
        return $this->notes;
    }

    public function created(string $kind): int
    {
        return $this->counts[$kind][self::CREATED] ?? 0;
    }

    public function totalCreated(): int
    {
        return array_sum(array_column($this->counts, self::CREATED));
    }

    /**
     * Resoconto in HTML (il modulo "Dati di esempio" lo inserisce come markup).
     */
    public function toHtml(): string
    {
        $lines = [];

        foreach ($this->counts as $kind => $count) {
            $lines[] = Text::sprintf(
                'PLG_SAMPLEDATA_TEMPLATEACCESSIBILE_REPORT_LINE',
                Text::_('PLG_SAMPLEDATA_TEMPLATEACCESSIBILE_KIND_' . strtoupper($kind)),
                $count[self::CREATED],
                $count[self::EXISTING]
            );
        }

        if (!$lines) {
            $lines[] = Text::_('PLG_SAMPLEDATA_TEMPLATEACCESSIBILE_REPORT_NOTHING');
        }

        foreach ($this->notes as $note) {
            $lines[] = htmlspecialchars($note, ENT_QUOTES, 'UTF-8');
        }

        return implode('<br>', $lines);
    }
}
