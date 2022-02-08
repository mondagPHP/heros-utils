<?php
declare(strict_types=1);
/**
 * This file is part of heros-util.
 *
 * @contact  mondagroup_php@163.com
 *
 */
namespace Monda\Utils\System;

final class Sys
{
    /**
     * Check is current OS Windows
     *
     * @return bool
     */
    public static function isWin(): bool
    {
        return \strncasecmp(\PHP_OS_FAMILY, 'WIN', 3) === 0 || \DIRECTORY_SEPARATOR === '\\';
    }

    /**
     * Alias fo ini_get function
     *
     * @param string $varName
     * @return string
     */
    public static function iniGet(string $varName): string
    {
        return (string)\ini_get($varName);
    }

    /**
     * Checks if function exists and callable
     *
     * @param string|\Closure $funcName
     * @return bool
     */
    public static function isFunc($funcName): bool
    {
        $isEnabled = true;
        if (\is_string($funcName)) {
            $isEnabled = \stripos(self::iniGet('disable_functions'), \strtolower(\trim($funcName))) === false;
        }

        return $isEnabled && (\is_callable($funcName) || (\is_string($funcName) && \function_exists($funcName)));
    }

    /**
     * Compares PHP versions
     *
     * @param string $version
     * @param string $current
     * @return bool
     */
    public static function isPHP(string $version, string $current = \PHP_VERSION): bool
    {
        $version = \trim($version, '.');
        return (bool)\preg_match('#^' . \preg_quote($version, '') . '#i', $current);
    }

    /**
     * Returns current PHP version
     *
     * @return string|null
     */
    public static function getVersion(): ?string
    {
        return \defined('PHP_VERSION') ? \PHP_VERSION : null;
    }
}
