<?php

declare(strict_types=1);
/**
 * This file is part of heros-utils.
 *
 * @contact  mondagroup_php@163.com
 */

namespace Monda\Utils\Time;

/**
 * Class Time
 */
class Time
{
    /**
     * 格式化字符串
     *
     * @param  int  $timestamp
     * @return false|string
     */
    public static function pretty(int $timestamp)
    {
        $hTime = date('H:i', $timestamp);
        $dif = abs(time() - $timestamp);
        if ($dif < 10) {
            $return = '刚刚';
        } elseif ($dif < 3600) {
            $return = floor($dif / 60).'分钟前';
        } elseif ($dif < 10800) {
            $return = floor($dif / 3600).'小时前';
        } elseif (date('Y-m-d', $timestamp) === date('Y-m-d')) {
            $return = '今天 '.$hTime;
        } elseif (date('Y-m-d', $timestamp) === date('Y-m-d', strtotime('-1 day'))) {
            $return = '昨天 '.$hTime;
        } elseif (date('Y-m-d', $timestamp) === date('Y-m-d', strtotime('-2 day'))) {
            $return = '前天 '.$hTime;
        } elseif (date('Y', $timestamp) === date('Y')) {
            $return = date('m-d H:i', $timestamp);
        } else {
            $return = date('Y-m-d H:i', $timestamp);
        }

        return $return;
    }
}
