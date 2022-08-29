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
 * Class RSACryptBigData
 */
class RSACryptBigData
{
    /**
     * 公钥加密.
     *
     * @param  string  $data
     * @param  string  $publicKey
     * @return string
     */
    public function encryptByPublicKeyData(string $data, string $publicKey): string
    {
        if (! extension_loaded('mbstring')) {
            throw new HeroException('please install mbstring extension.');
        }
        $RSACrypt = new RSACrypt();
        $cryptRes = '';
        for ($i = 0; $i < ((strlen($data) - strlen($data) % 117) / 117 + 1); $i++) {
            $cryptRes .= ($RSACrypt->encryptByPublicKey(mb_strcut($data, $i * 117, 117, 'utf-8'), $publicKey));
        }

        return $cryptRes;
    }

    /**
     * 私钥解密.
     *
     * @param  string  $data
     * @param  string  $privateKey
     * @return string
     */
    public function decryptByPrivateKeyData(string $data, string $privateKey): string
    {
        $RSACrypt = new RSACrypt();
        $decryptRes = '';
        $dataItems = explode('@', $data);
        foreach ($dataItems ?? [] as $value) {
            $decryptRes .= $RSACrypt->decryptByPrivateKey($value, $privateKey);
        }

        return $decryptRes;
    }

    /**
     * 私钥加密.
     *
     * @param  string  $data
     * @param  string  $privateKey
     * @return string
     */
    public function encryptByPrivateKeyData(string $data, string $privateKey): string
    {
        if (! extension_loaded('mbstring')) {
            throw new HeroException('please install mbstring extension.');
        }
        $RSACrypt = new RSACrypt();
        $cryptRes = '';
        for ($i = 0; $i < ((strlen($data) - strlen($data) % 117) / 117 + 1); $i++) {
            $cryptRes .= ($RSACrypt->encryptByPrivateKey(mb_strcut($data, $i * 117, 117, 'utf-8'), $privateKey));
        }

        return $cryptRes;
    }

    /**
     * 公钥解密.
     *
     * @param  string  $data
     * @param  string  $publicKey
     * @return string
     */
    public function decryptByPublicKeyData(string $data, string $publicKey): string
    {
        $RSACrypt = new RSACrypt();
        $decryptRes = '';
        $dataItems = explode('@', $data);
        foreach ($dataItems ?? [] as $value) {
            $decryptRes .= $RSACrypt->decryptByPublicKey($value, $publicKey);
        }

        return $decryptRes;
    }
}
