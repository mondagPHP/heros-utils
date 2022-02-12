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

class DayiAes
{
    /**
     * var string $method 加解密方法，可通过openssl_get_cipher_methods()获得
     */
    protected $method;

    /**
     * var string $secret_key 加解密的密钥
     */
    protected $secretKey;

    /**
     * var string $iv 加解密的向量，有些方法需要设置比如CBC
     */
    protected $iv = '';

    /**
     * var string $options （不知道怎么解释，目前设置为0没什么问题）
     */
    protected $options;

    /**
     * 构造函数
     *
     * @param string $key 密钥
     * @param string $method 加密方式
     * @param string $iv iv向量
     * @param mixed $options 还不是很清楚
     *
     */
    public function __construct(string $key, string $method = 'AES-128-ECB', string $iv = '', $options = 0)
    {
        // key是必须要设置的
        $this->secretKey = $key;
        $this->method = $method;
        $this->iv = $iv;
        $this->options = $options;
    }

    /**
     * 加密方法，对数据进行加密，返回加密后的数据
     *
     * @param string $data 要加密的数据
     *
     * @return string|false
     *
     */
    public function encrypt(string $data)
    {
        if (! extension_loaded('openssl')) {
            throw new HeroException('please install openssl extension.');
        }
        return openssl_encrypt($data, $this->method, $this->secretKey, $this->options, $this->iv);
    }

    /**
     * 解密方法，对数据进行解密，返回解密后的数据
     *
     * @param string $data 要解密的数据
     *
     * @return string|false
     *
     */
    public function decrypt(string $data)
    {
        if (! extension_loaded('openssl')) {
            throw new HeroException('please install openssl extension.');
        }
        return openssl_decrypt($data, $this->method, $this->secretKey, $this->options, $this->iv);
    }
}
