<?php

declare(strict_types=1);
/**
 * This file is part of heros-utils.
 *
 * @contact  mondagroup_php@163.com
 */

namespace Monda\Utils\Wechat;

use Monda\Utils\Exception\HeroException;
use Monda\Utils\File\FileUtil;

class Avatar
{
    /**
     * 下载文件到本地
     *
     * @param  string  $avatarUrl
     * @param  string  $savePath
     * @return string|false
     */
    public static function down(string $avatarUrl, string $savePath)
    {
        $imgContent = static::curlAvatar($avatarUrl); //图片内容
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $imgContent, $result)) {
            $type = $result[2]; //得到图片类型png?jpg?gif?
            FileUtil::makeFileDirs($savePath);
            $newFile = $savePath.date('YmdHis').'.'.$type;
            if (file_put_contents($newFile, base64_decode(str_replace($result[1], '', $imgContent)))) {
                return $newFile;
            }
        }

        return false;
    }

    /**
     * 下载远程图像
     *
     * @param  string  $avatarUrl
     * @return string|bool
     */
    private static function curlAvatar(string $avatarUrl)
    {
        if (! extension_loaded('curl')) {
            throw new HeroException('please install curl extension.');
        }
        $header = [
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:45.0) Gecko/20100101 Firefox/45.0',
            'Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',
            'Accept-Encoding: gzip, deflate', ];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $avatarUrl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $data = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ((int) $code !== 200) {//把URL格式图片转成base64_encode格式！
            return false;
        }

        return 'data:image/jpeg;base64,'.base64_encode($data);
    }
}
