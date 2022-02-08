<?php
declare(strict_types=1);
/**
 * This file is part of heros-util.
 *
 * @contact  mondagroup_php@163.com
 *
 */
namespace Monda\Utils\Http;

use Monda\Utils\Exception\CurlException;
use Monda\Utils\String\StringUtil;

/**
 * Class HttpClient
 * @package Monda\Utils\Http
 */
class HttpClient
{
    /**
     * 发送 http GET 请求
     * @param string $url
     * @param array $params
     * @param array $headers 请求头信息
     * @param bool $returnHeader 是否返回头信息
     * @return array|bool|string
     */
    public static function get(string $url, array $params = [], array $headers = [], bool $returnHeader = false)
    {
        if ($params) {
            $params = http_build_query($params);
            if (false === strpos($url, '?')) {
                $url .= '?' . $params;
            } else {
                $url .= '&' . $params;
            }
        }
        $curl = self::_curlInit($url, $headers);
        curl_setopt($curl, CURLOPT_HTTPGET, true);
        return self::_doRequest($curl, $returnHeader);
    }

    /**
     * 使用代理访问.
     * @param string $url
     * @param mixed $proxy 代理配置
     * @param array $params
     * @param bool $returnHeader
     * @return array|bool|string
     */
    public static function getProxy(string $url, string $proxy, array $params = [], bool $returnHeader = false)
    {
        if ($params) {
            $params = http_build_query($params);
            if (false === strpos($url, '?')) {
                $url .= '?' . $params;
            } else {
                $url .= '&' . $params;
            }
        }
        $curl = self::_curlInit($url, null);
        curl_setopt($curl, CURLOPT_PROXY, $proxy);
        curl_setopt($curl, CURLOPT_HTTPGET, true);

        return self::_doRequest($curl, $returnHeader);
    }

    /**
     * 发送http POST 请求
     * @param string $url
     * @param array $params
     * @param array|null $headers
     * @return array|bool|string
     */
    public static function post(string $url, array $params = [], array $headers = null)
    {
        if (is_array($params)) {
            $params = http_build_query($params);
        }
        $curl = self::_curlInit($url, $headers);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        return self::_doRequest($curl, false);
    }

    /**
     * 发送restful PUT请求
     * @param string $url
     * @param array $params
     * @return array|bool|string
     */
    public static function put(string $url, array $params = [])
    {
        if ($params) {
            $params = StringUtil::jsonEncode($params);
        }
        $curl = self::_curlInit($url, ['Content-Type' => 'application/json']);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        return self::_doRequest($curl, false);
    }

    /**
     * 发送restful DELETE请求
     * @param $url
     * @param $params
     * @return mixed
     */
    public static function delete($url, $params)
    {
        if ($params) {
            $params = StringUtil::jsonEncode($params);
        }
        $curl = self::_curlInit($url, ['Content-Type' => 'application/json']);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        return self::_doRequest($curl, false);
    }

    /**
     * 发送Http请求
     * @param $curl
     * @param bool $returnHeader
     * @return array|bool|string
     */
    private static function _doRequest($curl, bool $returnHeader = false)
    {
        $ret = curl_exec($curl);
        if (false === $ret) {
            curl_close($curl);
            throw new CurlException('接口网络异常，请稍候再试');
        }
        $info = curl_getinfo($curl);

        curl_close($curl);
        if (false === $ret) {
            throw new CurlException('cURLException:' . curl_error($curl));
        }
        if ($returnHeader) {
            return ['header' => $info, 'body' => $ret];
        }
        return $ret;
    }

    /**
     * 创建curl对象
     * @param string $url
     * @param array $headers
     * @return resource
     */
    private static function _curlInit(string $url, array $headers)
    {
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
