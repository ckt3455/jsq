<?php

namespace vendor\AliSms\lib\Test\Core\Auth;
use PHPUnit\Framework\TestCase;
use vendor\AliSms\lib\Core\Config;
use vendor\AliSms\lib\Core\Auth\ShaHmac256Signer;

class ShaHmac256SignerTest extends TestCase
{
    public function setUp() {
        Config::load();
    }

    public function testShaHmac256Signer()
	{
		$signer = new ShaHmac256Signer();
		$this->assertEquals("TpF1lE/avV9EHGWGg9Vo/QTd2bLRwFCk9jjo56uRbCo=",
            $signer->signString("this is a ShaHmac256 test.", "accessSecret"));
	}
}