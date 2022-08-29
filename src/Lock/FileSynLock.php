<?php

declare(strict_types=1);
/**
 * This file is part of heros-utils.
 *
 * @contact  mondagroup_php@163.com
 *
 */
namespace Monda\Utils\Lock;

use Monda\Utils\File\FileUtil;

/**
 * Class FileSynLock
 */
class FileSynLock implements ISynLock
{
    private $fileHandler;  //文件资源柄

    private $lockFile;

    /**
     * FileSynLock constructor.
     *
     * @param  string  $lockDir
     * @param  string  $key
     */
    public function __construct(string $lockDir, string $key)
    {
        $bool = FileUtil::makeFileDirs($lockDir);
        if (false === $bool) {
            throw new \RuntimeException("create path ({$lockDir}) error!!!");
        }
        $this->lockFile = $lockDir . md5($key) . '.lock';
        $this->fileHandler = fopen($this->lockFile, 'wb');
    }

    /**
     * 去除.
     */
    public function __destruct()
    {
        if (false !== $this->fileHandler) {
            fclose($this->fileHandler) && unlink($this->lockFile);
        }
    }

    /**
     * 尝试去获取锁，成功返回false并且一直阻塞.
     */
    public function tryLock(): bool
    {
        return ! (false === flock($this->fileHandler, LOCK_EX));
    }

    /**
     * 释放锁
     */
    public function unlock(): bool
    {
        return ! (false === flock($this->fileHandler, LOCK_UN));
    }
}
