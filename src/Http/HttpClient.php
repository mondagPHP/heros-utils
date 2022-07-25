<?php

declare(strict_types=1);
/**
 * This file is part of heros-utils.
 *
 * @contact  mondagroup_php@163.com
 */

namespace Monda\Utils\Http;

use Monda\Utils\Exception\CurlException;
use Monda\Utils\Exception\HeroException;

/**
 * Class HttpClient
 */
class HttpClient
{
    /**
     * 发送 http GET 请求
     *
     * @param  string  $url
     * @param  array  $params
     * @param  array  $headers 请求头信息
     * @return bool|string
     */
    public static function get(string $url, array $params = [], array $headers = [])
    {
        if (! extension_loaded('curl')) {
            throw new HeroException('please install curl extension.');
        }
        if ($params) {
            $query = http_build_query($params);
            if (false === strpos($url, '?')) {
                $url .= '?'.$query;
            } else {
                $url .= '&'.$query;
            }
        }
        $curl = self::_curlInit($url, $headers);
        curl_setopt($curl, CURLOPT_HTTPGET, true);

        return self::_doRequest($curl);
    }

    /**
     * 使用代理访问.
     *
     * @param  string  $url
     * @param  mixed  $proxy 代理配置
     * @param  array  $params
     * @return bool|string
     */
    public static function getProxy(string $url, string $proxy, array $params = [])
    {
        if (! extension_loaded('curl')) {
            throw new HeroException('please install curl extension.');
        }
        if ($params) {
            $query = http_build_query($params);
            if (false === strpos($url, '?')) {
                $url .= '?'.$query;
            } else {
                $url .= '&'.$query;
            }
        }
        $curl = self::_curlInit($url, null);
        curl_setopt($curl, CURLOPT_PROXY, $proxy);
        curl_setopt($curl, CURLOPT_HTTPGET, true);

        return self::_doRequest($curl);
    }

    /**
     * 发送http POST 请求
     *
     * @param  string  $url
     * @param  array  $params
     * @param  array|null  $headers
     * @return bool|string
     */
    public static function post(string $url, array $params = [], array $headers = [])
    {
        if (! extension_loaded('curl')) {
            throw new HeroException('please install curl extension.');
        }
        $curl = self::_curlInit($url, $headers);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));

        return self::_doRequest($curl);
    }

    /**
     * 发送restful PUT请求
     *
     * @param  string  $url
     * @param  array  $params
     * @return bool|string
     */
    public static function put(string $url, array $params = [])
    {
        if (! extension_loaded('curl') || ! extension_loaded('json')) {
            throw new HeroException('please install curl extension.');
        }
        $curl = self::_curlInit($url, ['Content-Type' => 'application/json']);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));

        return self::_doRequest($curl);
    }

    /**
     * 发送restful DELETE请求
     *
     * @param $url
     * @param $params
     * @return mixed
     */
    public static function delete(string $url, array $params = [])
    {
        if (! extension_loaded('curl') || ! extension_loaded('json')) {
            throw new HeroException('please install curl extension.');
        }
        if ($params) {
            $query = http_build_query($params);
            if (false === strpos($url, '?')) {
                $url .= '?'.$query;
            } else {
                $url .= '&'.$query;
            }
        }
        $curl = self::_curlInit($url, ['Content-Type' => 'application/json']);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');

        return self::_doRequest($curl);
    }

    /**
     * 发送Http请求
     *
     * @param $curl
     * @return bool|string
     */
    private static function _doRequest($curl)
    {
        if (! extension_loaded('curl')) {
            throw new HeroException('please install curl extension.');
        }
        try {
            $ret = curl_exec($curl);
            if (false === $ret) {
                throw new CurlException('接口网络异常，请稍候再试');
            }
            if (false === $ret) {
                throw new CurlException('cURLException:'.curl_error($curl));
            }

            return $ret;
        } finally {
            curl_close($curl);
        }
    }

    /**
     * 创建curl对象
     *
     * @param  string  $url
     * @param  array  $headers
     * @return resource
     */
    private static function _curlInit(string $url, array $headers = [])
    {
        if (! extension_loaded('curl')) {
            throw new HeroException('please install curl extension.');
        }
        $curl = curl_init();
        if (false !== stripos($url, 'https://')) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if ($headers) {
            $_headers = [];
            foreach ($headers as $key => $value) {
                $_headers[] = "{$key}:{$value}";
            }
            curl_setopt($curl, CURLOPT_HTTPHEADER, $_headers);
        }

        return $curl;
    }
}
