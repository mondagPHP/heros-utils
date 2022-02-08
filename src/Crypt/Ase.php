<?php
declare(strict_types=1);
/**
 * This file is part of heros-utils.
 *
 * @contact  mondagroup_php@163.com
 *
 */
namespace Monda\Utils\Crypt;

use Monda\Utils\Exception\HeroException;

/**
 * Class Ase
 * @package Monda\Utils\Crypt
 */
class Ase
{
    /**
     * 加密.
     * @param string $str 要加密的数据
     * @param string $aseKey 要加密的数据
     * @return string 加密后的数据
     */
    public static function encrypt(string $str, string $aseKey): string
    {
        if (! extension_loaded('openssl')) {
            throw new HeroException('please install openssl extension.');
        }
        $data = openssl_encrypt($str, 'AES-128-ECB', $aseKey, OPENSSL_RAW_DATA);
        return base64_encode($data);
    }

    /**
     * 解密.
     * @param string $str 要解密的数据
     * @param string $aseKey 要解密的数据
     * @return string 解密后的数据
     */
    public static function decrypt(string $str, string $aseKey): string
    {
        if (! extension_loaded('openssl')) {
            throw new HeroException('please install openssl extension.');
        }
        return openssl_decrypt(base64_decode($str), 'AES-128-ECB', $aseKey, OPENSSL_RAW_DATA);
    }
}
