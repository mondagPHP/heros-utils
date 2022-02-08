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
 * Interface ISynLock
 * @package Monda\Utils\Lock
 */
interface ISynLock
{
    /**
     * 获取同步锁
     */
    public function tryLock(): bool;

    /**
     * 解锁
     */
    public function unLock(): bool;
}
