<?php

namespace vendor\AliSms\lib\Test\Core\Profile;
use PHPUnit\Framework\TestCase;
use vendor\AliSms\lib\Core\Regions\EndpointProvider;
use vendor\AliSms\lib\Core\Config;

class EndpointProviderTest extends TestCase
{
    public function setUp() {
        Config::load();
    }

	public function testFindProductDomain()
	{
		$this->assertEquals("ecs-cn-hangzhou.aliyuncs.com",EndpointProvider::findProductDomain("cn-hangzhou", "Ecs"));
	}
	
}