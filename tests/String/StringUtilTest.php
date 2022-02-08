<?php
/**
 * This file is part of heros-utils.
 *
 * @contact  mondagroup_php@163.com
 *
 */
namespace Monda\Utils\Test\String;

use Monda\Utils\String\StringUtil;
use PHPUnit\Framework\TestCase;

class StringUtilTest extends TestCase
{
    public function testGenGlobalUid()
    {
        $arr = [];
        for ($i = 0; $i < 10000; $i++) {
            $arr[StringUtil::genGlobalUid()] = $i;
        }
        $this->assertCount(10000, $arr);
    }

    public function testHump2Underline()
    {
        $this->assertEquals('a_b', StringUtil::hump2Underline('aB'));
        $this->assertEquals('ab', StringUtil::hump2Underline('ab'));
    }

    public function testJsonDecode()
    {
        $this->assertEquals('["你好"]', StringUtil::jsonEncode(['你好']));
    }

    public function testUnderline2hump()
    {
        $this->assertEquals('a_b', StringUtil::hump2Underline('aB'));
        $this->assertEquals('ab', StringUtil::hump2Underline('ab'));
    }

    public function testJsonEncode()
    {
        $this->assertEquals(['你好'], StringUtil::jsonDecode('["你好"]'));
    }
}
