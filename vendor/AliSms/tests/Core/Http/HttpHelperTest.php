<?php

namespace vendor\AliSms\lib\Test\Core\Http;
use PHPUnit\Framework\TestCase;
use vendor\AliSms\lib\Core\Http\HttpHelper;
use vendor\AliSms\lib\Core\Config;

class HttpHelperTest extends TestCase
{
    function setUp()
    {
        Config::load();
    }

	public function testCurl()
	{
		$httpResponse = HttpHelper::curl("ecs.aliyuncs.com");
		$this->assertEquals(400,$httpResponse->getStatus());		
		$this->assertNotNull($httpResponse->getBody());
	}

}