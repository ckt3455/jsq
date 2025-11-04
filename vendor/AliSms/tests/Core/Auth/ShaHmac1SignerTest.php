<?php

namespace vendor\AliSms\lib\Test\Core\Auth;
use PHPUnit\Framework\TestCase;
use vendor\AliSms\lib\Core\Config;
use vendor\AliSms\lib\Core\Auth\ShaHmac1Signer;

class ShaHmac1SignerTest extends TestCase
{
    public function setUp() {
        Config::load();
    }

    public function testShaHmac1Signer()
	{
		$signer = new ShaHmac1Signer();
		$this->assertEquals("33nmIV5/p6kG/64eLXNljJ5vw84=",$signer->signString("this is a ShaHmac1 test.", "accessSecret"));
	}
}