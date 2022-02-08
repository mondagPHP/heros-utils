<?php
declare(strict_types=1);
/**
 * This file is part of heros-util.
 *
 * @contact  mondagroup_php@163.com
 *
 */
namespace Monda\Utils\Container;

use Monda\Utils\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

/**
 * Class Container
 * @package Webman
 */
class Container implements ContainerInterface
{
    /**
     * @var array
     */
    protected $_instances = [];

    /**
     * @param string $id
     * @return mixed
     */
    public function get(string $id)
    {
        if (! isset($this->_instances[$id])) {
            if (! class_exists($id)) {
                throw new NotFoundException("Class '$id' not found");
            }
            $this->_instances[$id] = new $id();
        }
        return $this->_instances[$id];
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has(string $id): bool
    {
        return \array_key_exists($id, $this->_instances);
    }

    /**
     * @param string $name
     * @param array $constructor
     * @return mixed
     */
    public function make(string $name, array $constructor = [])
    {
        if (! class_exists($name)) {
            throw new NotFoundException("Class '$name' not found");
        }
        return new $name(... array_values($constructor));
    }
}
