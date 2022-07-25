<?php

declare(strict_types=1);
/**
 * This file is part of heros-utils.
 *
 * @contact  mondagroup_php@163.com
 */

namespace Monda\Utils\Image;

interface CaptchaBuilderInterface
{
    /**
     * 创建验证图片
     *
     * @return mixed
     */
    public function create();

    /**
     * 将验证码图片保存到指定路径
     *
     * @param  string  $filename 物理路径
     * @param  int  $quality 清晰度
     * @return mixed
     */
    public function save(string $filename, int $quality);

    /**
     * 获取验证码图片
     *
     * @param  int  $quality 清晰度
     * @return mixed
     */
    public function output(int $quality);

    /**
     * 获取验证码内容
     *
     * @return mixed
     */
    public function getText();
}
