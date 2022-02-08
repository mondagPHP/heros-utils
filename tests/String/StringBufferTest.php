<?php
declare(strict_types=1);
/**
 * This file is part of heros-util.
 *
 * @contact  mondagroup_php@163.com
 *
 */
namespace Monda\Utils\Test\String;

use Monda\Utils\String\StringBuffer;
use PHPUnit\Framework\TestCase;

class StringBufferTest extends TestCase
{
    private $stringBuffer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->stringBuffer = new StringBuffer();
    }

    public function testIsEmpty()
    {
        $this->assertTrue($this->stringBuffer->isEmpty());
        $this->stringBuffer->append('hello');
        $this->assertFalse($this->stringBuffer->isEmpty());
    }

    public function testToString()
    {
        $this->assertEquals('', $this->stringBuffer);
        $this->stringBuffer->append('hello');
        $this->assertEquals('hello', $this->stringBuffer->toString());
        $this->stringBuffer->append('world');
        $this->assertEquals('helloworld', $this->stringBuffer->toString());
    }

    public function testAppendTab()
    {
        $this->stringBuffer->appendTab('hello', 3);
        $this->assertEquals("hello\t\t\t", $this->stringBuffer->toString());
    }

    public function testAppend()
    {
        $this->stringBuffer->append('hello');
        $this->assertEquals('hello', $this->stringBuffer->toString());
    }

    public function testAppendLine()
    {
        $this->stringBuffer->appendLine('hello');
        $this->assertEquals("hello\n", $this->stringBuffer->toString());
    }
}
