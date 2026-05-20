<?php
/**
 * Single source of truth for all media asset URLs in the template.
 * Do NOT replicate file_exists checks or build media/templates/site/... paths elsewhere.
 */
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

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
