<?php
declare(strict_types=1);
/**
 * This file is part of heros-utils.
 *
 * @contact  mondagroup_php@163.com
 *
 */
namespace Monda\Utils\String;

use Monda\Utils\Exception\HeroException;
use Monda\Utils\Lock\SynLockFactory;

/**
 * Class StringUtil
 * @package Monda\Utils\String
 */
class StringUtil
{
    public const UUID_LOCK_KEY = 'hero_util_uuid_lock_key';

    /**
     * 生成一个唯一分布式UUID,根据机器不同生成. 长度为18位。
     * 机器码(2位) + 时间(12位，精确到微秒).
     * @return string
     */
    public static function genGlobalUid(): string
    {
        $lockDir = __DIR__ . '/lock/';
        if (defined('RUNTIME_PATH')) {
            $lockDir = RUNTIME_PATH . '/lock';
        }
        $lock = SynLockFactory::getFileSynLock($lockDir, self::UUID_LOCK_KEY);
        $lock->tryLock();
        usleep(2);
        //获取服务器时间，精确到毫秒
        $tArr = explode(' ', microtime());
        $tSec = $tArr[1];
        $mSec = $tArr[0];
        if (($sIdx = strpos($mSec, '.')) !== false) {
            $mSec = substr($mSec, $sIdx + 1);
        }
        //获取服务器节点信息
        if (! defined('SERVER_NODE')) {
            $node = 0x01;
        } else {
            $node = SERVER_NODE;
        }
        $lock->unlock();
        return sprintf('%02x%08x%08x', $node, $tSec, $mSec);
    }

    /**
     * 下划线转驼峰.
     * @param string $str
     * @return string
     */
    public static function underline2hump(string $str): string
    {
        $str = trim($str);
        if (false === strpos($str, '_')) {
            return $str;
        }
        $arr = explode('_', $str);
        $str = $arr[0];
        for ($i = 1, $iMax = count($arr); $i < $iMax; ++$i) {
            $str .= ucfirst($arr[$i]);
        }
        return $str;
    }

    /**
     * 驼峰转下划线
     * @param string $str
     * @return mixed
     */
    public static function hump2Underline(string $str): string
    {
        $arr = [];
        for ($i = 0, $iMax = strlen($str); $i < $iMax; ++$i) {
            if (ord($str[$i]) > 64 && ord($str[$i]) < 91) {
                $arr[] = '_' . strtolower($str[$i]);
            } else {
                $arr[] = $str[$i];
            }
        }
        return implode('', $arr);
    }

    /**
     * 将中文数组json编码
     * @param array $array
     * @return string
     */
    public static function jsonEncode(array $array): string
    {
        if (! extension_loaded('json')) {
            throw new HeroException('please install json extension.');
        }
        return json_encode($array, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 中文 json 数据解码
     * @param string $string
     * @return mixed
     */
    public static function jsonDecode(string $string)
    {
        if (! extension_loaded('json')) {
            throw new HeroException('please install json extension.');
        }
        $string = trim($string, "\xEF\xBB\xBF");
        return json_decode($string, true);
    }
}
