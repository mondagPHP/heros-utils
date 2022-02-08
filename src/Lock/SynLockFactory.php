<?php
declare(strict_types=1);
/**
 * This file is part of heros-util.
 *
 * @contact  mondagroup_php@163.com
 *
 */
namespace Monda\Utils\Lock;

/**
 * Class SynLockFactory
 * @package Monda\Utils\Lock
 */
class SynLockFactory
{
    private static $FILE_LOCK_POOL = []; //文件锁池

    /**
     * 获取文件锁
     * @param string $lockDir
     * @param string $key
     * @return ISynLock
     */
    public static function getFileSynLock(string $lockDir, string $key): ISynLock
    {
        if (! isset(self::$FILE_LOCK_POOL[$key])) {
            self::$FILE_LOCK_POOL[$key] = new FileSynLock($lockDir, $key);
        }
        return self::$FILE_LOCK_POOL[$key];
    }
}
