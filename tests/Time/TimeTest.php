<?php
/**
 * This file is part of heros-utils.
 *
 * @contact  mondagroup_php@163.com
 */

namespace Monda\Utils\Test\Time;

use Monda\Utils\Time\Time;
use PHPUnit\Framework\TestCase;

class TimeTest extends TestCase
{
    public function testPretty()
    {
        $this->assertEquals('刚刚', Time::pretty(\time()));
    }
}
