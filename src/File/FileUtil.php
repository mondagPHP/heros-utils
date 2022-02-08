<?php
declare(strict_types=1);
/**
 * This file is part of heros-util.
 *
 * @contact  mondagroup_php@163.com
 *
 */
namespace Monda\Utils\File;

/**
 * Class FileUtil
 * @package Monda\Utils\File
 */
class FileUtil
{
    /**
     * 创建多层文件目录.
     * @param string $path 需要创建路径
     * @return bool   成功时返回true，失败则返回false;
     */
    public static function makeFileDirs(string $path): bool
    {
        //必须考虑 "/" 和 "\" 两种目录分隔符
        $files = preg_split('/[\/|\\\]/s', $path);
        $dir = '';
        if (! $files) {
            return false;
        }
        foreach ($files as $value) {
            $dir .= $value . DIRECTORY_SEPARATOR;
            if (! file_exists($dir) && ! mkdir($dir, 0777) && ! is_dir($dir)) {
                return false;
            }
        }
        return true;
    }

    /**
     * 递归删除文件夹.
     * @param string $dir
     * @return bool
     */
    public static function removeDirs(string $dir): bool
    {
        $handle = opendir($dir);
        //删除文件夹下面的文件
        while ($file = readdir($handle)) {
            if ('.' !== $file && '..' !== $file) {
                $filename = $dir . '/' . $file;
                if (! is_dir($filename)) {
                    @unlink($filename);
                } else {
                    self::removeDirs($filename);
                }
            }
        }
        closedir($handle);
        //删除当前文件夹
        if (rmdir($dir)) {
            return true;
        }
        return false;
    }

    /**
     * 遍历目录，返回目录文件相对路径.
     * @param string $dir
     * @return array
     */
    public static function dirTraversal(string $dir): array
    {
        $files = [];
        self::getDirFiles($dir, '', $files);
        return $files;
    }

    /**
     * 获取目录文件.
     * @param string $absoluteDir
     * @param string $relativeDir
     * @param array $files
     */
    private static function getDirFiles(string $absoluteDir, string $relativeDir, array &$files): void
    {
        $handler = opendir($absoluteDir);
        if (false !== $handler) {
            while ($filename = readdir($handler)) {
                if ('.' !== $filename && '..' !== $filename) {
                    if (is_dir($absoluteDir . '/' . $filename)) {
                        self::getDirFiles($absoluteDir . '/' . $filename, $relativeDir . $filename . '/', $files);
                    } else {
                        $files[] = $relativeDir . $filename;
                    }
                }
            }
            closedir($handler);
        }
    }
}
