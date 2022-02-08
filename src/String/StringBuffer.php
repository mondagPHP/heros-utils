<?php
declare(strict_types=1);
/**
 * This file is part of heros-util.
 *
 * @contact  mondagroup_php@163.com
 *
 */
namespace Monda\Utils\String;

/**
 * Class StringBuffer
 * @package Monda\Utils\String
 */
class StringBuffer
{
    private $strMap = [];

    /**
     * StringBuffer constructor.
     * @param string|null $str
     */
    public function __construct(?string $str = null)
    {
        if (null !== $str) {
            $this->append($str);
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    public function isEmpty(): bool
    {
        return 0 === count($this->strMap);
    }

    /**
     * @param string $str
     */
    public function append(string $str)
    {
        array_push($this->strMap, $str);
    }

    /**
     * @param string $str
     */
    public function appendLine(string $str)
    {
        $this->append($str . PHP_EOL);
    }

    /**
     * @param string $str
     * @param int $tabNum
     */
    public function appendTab(string $str, int $tabNum = 1)
    {
        $tab = str_repeat("\t", $tabNum);
        $this->append($str . $tab);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        foreach ($this->strMap as $key => $value) {
            if (is_array($value)) {
                $this->strMap[$key] = implode('', $value);
            }
        }
        return implode('', $this->strMap);
    }
}
