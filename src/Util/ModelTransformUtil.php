<?php
declare(strict_types=1);
/**
 * This file is part of heros-utils.
 *
 * @contact  mondagroup_php@163.com
 *
 */
namespace Monda\Utils\Util;

use Monda\Utils\Exception\HeroException;
use Monda\Utils\String\StringUtil;
use ReflectionNamedType;

/**
 * Class ModelTransformUtil
 * @package Monda\Utils\Util
 */
class ModelTransformUtil
{
    private static $defaultProperties = [
        'bool' => false,
        'int' => 0,
        'string' => '',
        'array' => [],
        'float' => 0.0,
        'double' => '0.0'
    ];

    /**
     * map转换为数据模型.
     * @throws \ReflectionException
     */
    public static function map2Model(string $class, array $map = []): object
    {
        $refClass = new \ReflectionClass($class);
        $obj = $refClass->newInstance();
        // Vo对象自动初始化
        static::initParamByPHP80($refClass, $obj);
        foreach ($map as $key => $value) {
            $methodName = 'set' . ucwords(StringUtil::underline2hump($key));
            if ($refClass->hasMethod($methodName)) {
                $method = $refClass->getMethod($methodName);
                $method->invoke($obj, $value);
            }
        }
        return $obj;
    }

    /**
     * 模型对象转为map.
     * @param object $model
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
            $method = 'get' . ucfirst($property);
            $map[$property] = $model->{$method}();
        }
        return $map;
    }

    /**
     * @param \ReflectionClass $refClass
     * @param object $obj
     * @return void
     * @throws \ReflectionException
     */
    protected static function initParamByPHP80(\ReflectionClass $refClass, object $obj): void
    {
        if (PHP_VERSION > 8.0) {
            $properties = $refClass->getProperties();
            /** @var \ReflectionProperty $property */
            foreach ($properties ?? [] as $property) {
                $property->setAccessible(true);
                $reflectionType = $property->getType();
                if ($property->getDefaultValue() !== null) {
                    continue;
                }
                if ($reflectionType instanceof \ReflectionNamedType) {
                    $type = $property->getType()->getName();
                    if (in_array($type, array_keys(static::$defaultProperties))) {
                        $methodName = 'set' . ucwords(StringUtil::underline2hump($property->getName()));
                        $method = $refClass->getMethod($methodName);
                        $method->invoke($obj, static::$defaultProperties[$type]);
                    }
                }
                if ($reflectionType instanceof \ReflectionUnionType) {
                    $types = $reflectionType->getTypes();
                    /** @var ReflectionNamedType $type */
                    foreach ($types ?? [] as $type) {
                        if (in_array($type->getName(), array_keys(static::$defaultProperties))) {
                            $methodName = 'set' . ucwords(StringUtil::underline2hump($property->getName()));
                            $method = $refClass->getMethod($methodName);
                            $method->invoke($obj, static::$defaultProperties[$type->getName()]);
                        }
                    }
                }
            }
        }
    }
}
