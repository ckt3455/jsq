<?php

namespace vendor\AliSms\lib\Test\Core;

use PHPUnit\Framework\TestCase;
use vendor\AliSms\lib\Core\Profile\DefaultProfile;
use vendor\AliSms\lib\Core\Config;
use vendor\AliSms\lib\Core\DefaultAcsClient;
use vendor\AliSms\lib\Test\Core\Ecs\Request\DescribeRegionsRequest;

class DefaultAcsClientTest extends TestCase {

    function setUp() {
        Config::load();
    }

	public function testDoActionRPC() {
        echo "\nWARNING: setup accessKeyId and accessSecret of DefaultAcsClientTest";
        $iClientProfile = DefaultProfile::getProfile(
            "cn-hangzhou",
            "yourAccessKeyId",
            "yourAccessKeySecret"
        );
		$request = new DescribeRegionsRequest();
        $client = new DefaultAcsClient($iClientProfile);
        $response = $client->getAcsResponse($request);
		
		$this->assertNotNull($response->RequestId);
		$this->assertNotNull($response->Regions->Region[0]->LocalName);
		$this->assertNotNull($response->Regions->Region[0]->RegionId);
	}
}