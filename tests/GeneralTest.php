<?php

use PHPUnit\Framework\TestCase;

use Seriyyy95\SeleniumChromeLibrary\SeleniumBrowser;
use Seriyyy95\SeleniumChromeLibrary\SeleniumParams;
use Seriyyy95\SeleniumChromeLibrary\SeleniumAPI;

class StackTest extends TestCase
{

    public function testSettingHostViaIstance(){
	SeleniumAPI::resetInstance();
    	$seleniumAPI = SeleniumAPI::getInstance();
	$seleniumAPI->setDefaultHost("https://test-host");
	$params = new SeleniumParams();
	$host = $params->getHost();
	$this->assertSame($host, "https://test-host");
    }

    public function testUsingProxy()
    {
	SeleniumAPI::resetInstance();
	$params = new SeleniumParams();
	$params->setProxy("185.221.161.30:9589", "AWtgXV", "aoBsME");
    	$seleniumAPI = SeleniumAPI::getInstance();
    	$browser = $seleniumAPI->getBrowser($params);
	$browser->open("https://www.myexternalip.com/raw");
	$ip = $browser->findByName("body")->getText();
	$this->assertSame("185.221.161.30", $ip);	
    }

    public function testOpenGoogle()
    {
	SeleniumAPI::resetInstance();
    	$seleniumAPI = SeleniumAPI::getInstance();
    	$browser = $seleniumAPI->getBrowser();
	$browser->open("https://google.com");	
	$url = $browser->getDriver()->getCurrentUrl();
	$browser->close();
	$this->assertRegexp('/google/', $url);
    }

}
