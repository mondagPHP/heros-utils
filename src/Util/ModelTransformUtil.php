<?php

declare(strict_types=1);
/**
 * This file is part of heros-utils.
 *
 * @contact  mondagroup_php@163.com
 */

namespace Monda\Utils\Util;

use Monda\Utils\Exception\HeroException;
use Monda\Utils\String\StringUtil;

/**
 * Class ModelTransformUtil
 */
class ModelTransformUtil
{
    /**
     * map转换为数据模型.
     *
     * @throws \ReflectionException
     */
    public static function map2Model(string $class, array $map = []): object
    {
        $refClass = new \ReflectionClass($class);
        $obj = $refClass->newInstance();
        // Vo对象自动初始化
        foreach ($map as $key => $value) {
            $methodName = 'set'.ucwords(StringUtil::underline2hump($key));
            if ($refClass->hasMethod($methodName)) {
                $method = $refClass->getMethod($methodName);
                $method->invoke($obj, $value);
            }
        }

        return $obj;
    }

    /**
     * 模型对象转为map.
     *
     * @param  object  $model
     * @return array
     */
    public static function model2Map(object $model): array
    {
        if (! is_object($model)) {
            throw new HeroException('请传入对象');
        }
        $refClass = new \ReflectionClass($model);
        $properties = $refClass->getProperties();
        $map = [];
        foreach ($properties as $value) {
            $property = $value->getName();
            if (strpos($property, '_')) {
                $property = StringUtil::underline2hump($property); //转换成驼锋格式
            }
            $method = 'get'.ucfirst($property);
            $map[$property] = $model->{$method}();
        }

        return $map;
    }
}
