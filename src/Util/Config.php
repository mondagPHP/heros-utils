<?php
declare(strict_types=1);
/**
 * This file is part of heros-utils.
 *
 * @contact  mondagroup_php@163.com
 *
 */
namespace Monda\Utils\Util;

/**
 * Class Config
 * @package Monda\Utils\Util
 */
class Config
{
    /**
     * @var array 配置文件
     */
    private static $config = [];

    /**
     * 加载配置文件.
     */
    public static function load(string $configPath, array $excludeFile = [])
    {
        try {
            $handler = opendir($configPath);
            while (($filename = readdir($handler)) !== false) {
                if ('.' != $filename && '..' != $filename) {
                    $basename = basename($filename, '.php');
                    if (in_array($basename, $excludeFile)) {
                        continue;
                    }
                    self::$config[$basename] = require_once $configPath . '/' . $filename;
                }
            }
        } finally {
            closedir($handler);
        }
    }

    /**
     * 获取配置文件.
     * @return array
     */
    public static function all(): array
    {
        return self::$config;
    }

    /**
     * 获取配置文件.
     * @param string $key 键
     * @param null $default 默认值
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $keyArray = explode('.', $key);
        $value = self::$config;
        foreach ($keyArray as $index) {
            if (! isset($value[$index])) {
                return $default;
            }
            $value = $value[$index];
        }
        return $value;
    }

    /**
     * 重新加载.
     * @param string $configPath
     * @param array $excludeFile
     */
    public static function reload(string $configPath, array $excludeFile = [])
    {
        self::$config = [];
        self::load($configPath, $excludeFile);
    }
}
