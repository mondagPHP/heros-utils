<?php
/**
 * This file is part of heros-utils.
 *
 * @contact  mondagroup_php@163.com
 */

namespace Monda\Utils\Test\Http;

use Monda\Utils\Http\HttpClient;
use PHPUnit\Framework\TestCase;

class HttpClientTest extends TestCase
{
    public function testGet(): void
    {
        $res = HttpClient::get('http://127.0.0.1:8080/test/get?a=x', ['hello' => 'delete']);
        $this->assertEquals('{"code":"000","success":true,"message":"get"}', $res);
    }
}
