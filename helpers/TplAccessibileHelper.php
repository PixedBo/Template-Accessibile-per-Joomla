<?php
/**
 * Single source of truth for all media asset URLs in the template.
 * Do NOT replicate file_exists checks or build media/templates/site/... paths elsewhere.
 */
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

/**
 * Punto unico di accesso a tutti gli URL delle risorse media del template.
 *
 * La classe implementa una cache statica per-request ({@see self::$cache}):
 * ogni risorsa richiede al massimo una chiamata a file_exists() per pagina,
 * indipendentemente da quante volte viene referenziata (es. sprite SVG
 * richiamato 15+ volte per pagina). Qualsiasi refactoring che elimina
 * questa cache deve misurare l'impatto sulle I/O prima di procedere.
 *
 * ## Perché non usiamo HTMLHelper::mediaPath()
 *
 * Da Joomla 5.3 esiste HTMLHelper::mediaPath() che risolve i path verso il
 * media folder del template con supporto nativo ai child template — lo stesso
 * problema che questa classe risolve. Abbiamo scelto consapevolmente di NON
 * adottarla per quattro motivi:
 *
 * 1. **Cache assente.** `HTMLHelper::mediaPath()` non ha cache: ogni chiamata
 *    esegue I/O sul filesystem. La nostra implementazione cacha il risultato
 *    in `self::$cache`, riducendo le chiamate reali a una sola per risorsa.
 *
 * 2. **Incompatibilità di versione.** L'API ufficiale è disponibile solo da
 *    Joomla 5.3+. Il requisito minimo del template è Joomla 5.0+. Adottarla
 *    richiederebbe alzare il requisito oppure un hybrid con detect di versione
 *    — over-engineering con costo superiore al beneficio.
 *
 * 3. **Solo API pubblica.** Questa implementazione usa esclusivamente API
 *    pubbliche documentate di Joomla (`Factory::getApplication()->getTemplate()`,
 *    `Uri::root()`, `JPATH_ROOT`). Non dipende da internal del CMS, quindi
 *    non è soggetta a rotture per evoluzioni interne.
 *
 * 4. **Funzionalità superflue.** `mediaPath()` include browser detection e
 *    debug mode toggle non necessari per risorse statiche di template.
 *
 * Riferimento: https://forum.joomla.org/viewtopic.php?f=859&t=1023210
 *
 * La decisione sarà rivalutata se/quando: il requisito minimo sale a Joomla
 * 5.3+ per altri motivi; `mediaPath()` acquisisce caching nativo; Joomla
 * introduce un service provider per i template che permette di rimpiazzare
 * anche il bootstrap manuale dell'helper.
 *
 * @internal Non sostituire questa implementazione con una chiamata a
 *           HTMLHelper::mediaPath() senza una nuova analisi dei trade-off
 *           di cache e versione minima descritti sopra.
 */
class TplAccessibileHelper
{
    private static ?array $tplInfo = null;
    private static array $cache = [];

    private static function resolveTemplateInfo(): array
    {
        if (self::$tplInfo === null) {
            $tpl = Factory::getApplication()->getTemplate(true);
            self::$tplInfo = [(string) $tpl->template, (string) ($tpl->parent ?? '')];
        }
        return self::$tplInfo;
    }

    public static function mediaUrl(string $relativePath): string
    {
        $relativePath = ltrim($relativePath, '/');
        if (isset(self::$cache[$relativePath])) {
            return self::$cache[$relativePath];
        }
        [$active, $parent] = self::resolveTemplateInfo();
        $base = rtrim(Uri::base(), '/');
        if ($parent === '' || file_exists(JPATH_ROOT . '/media/templates/site/' . $active . '/' . $relativePath)) {
            $url = $base . '/media/templates/site/' . $active . '/' . $relativePath;
        } else {
            $url = $base . '/media/templates/site/' . $parent . '/' . $relativePath;
        }
        self::$cache[$relativePath] = $url;
        return $url;
    }

    public static function mediaExists(string $relativePath): bool
    {
        $relativePath = ltrim($relativePath, '/');
        [$active, $parent] = self::resolveTemplateInfo();
        if (file_exists(JPATH_ROOT . '/media/templates/site/' . $active . '/' . $relativePath)) {
            return true;
        }
        if ($parent !== '') {
            return file_exists(JPATH_ROOT . '/media/templates/site/' . $parent . '/' . $relativePath);
        }
        return false;
    }

    public static function spriteUrl(string $icon): string
    {
        return self::mediaUrl('svg/sprites.svg') . '#' . $icon;
    }
}
