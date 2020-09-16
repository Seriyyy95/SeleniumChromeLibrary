<?php

use PHPUnit\Framework\TestCase;

use Seriyyy95\SeleniumChromeLibrary\SeleniumBrowser;
use Seriyyy95\SeleniumChromeLibrary\SeleniumParams;
use Seriyyy95\SeleniumChromeLibrary\SeleniumAPI;
use Facebook\WebDriver\WebDriverBy;

class StackTest extends TestCase
{
    public function testSettingHostViaIstance()
    {
        SeleniumAPI::resetInstance();
        $seleniumAPI = SeleniumAPI::getInstance();
        $seleniumAPI->setDefaultHost("https://test-host");
        $params = new SeleniumParams();
        $host = $params->getHost();
        $this->assertSame($host, "https://test-host");
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

    public function testClick()
    {
        SeleniumAPI::resetInstance();
        $seleniumAPI = SeleniumAPI::getInstance();
        $browser = $seleniumAPI->getBrowser();
        $browser->open($_ENV["TEST_HOST"]);
        $browser->visibilityByCss(".container");
        $btn = $browser->findByCss("#clickable");
        $browser->click($btn);
        $textElem = $browser->findByCss("#clickable-related");
        $text = $textElem->getText();
        $this->assertSame("button clicked", $text);
    }

    public function testDoAndWaitReload()
    {
        SeleniumAPI::resetInstance();
        $seleniumAPI = SeleniumAPI::getInstance();
        $browser = $seleniumAPI->getBrowser();
        $browser->open($_ENV["TEST_HOST"]);
        $browser->visibilityByCss(".container");
        $btn = $browser->findByCss("#reload");
        $result = $browser->doAndWaitReload(function () use ($browser, $btn) {
            $browser->click($btn);
        });
        $this->assertTrue($result);
    }

    public function testDoAndWaitIncrease()
    {
        SeleniumAPI::resetInstance();
        $seleniumAPI = SeleniumAPI::getInstance();
        $browser = $seleniumAPI->getBrowser();
        $browser->open($_ENV["TEST_HOST"]);
        $browser->visibilityByCss(".container");
        $btn = $browser->findByCss("#increase");
        $result = $browser->doAndWaitIncrease(WebDriverBy::cssSelector(".increase-element"), function () use ($browser, $btn) {
            $browser->click($btn);
        });
        $this->assertTrue($result);
    }


    public function testWaitAppear()
    {
        SeleniumAPI::resetInstance();
        $seleniumAPI = SeleniumAPI::getInstance();
        $browser = $seleniumAPI->getBrowser();
        $browser->open($_ENV["TEST_HOST"]);
        $browser->visibilityByCss(".container");
        $browser->waitAppearByCss("#appear-element");
        $result = $browser->hasByCss("#appear-element");
        $this->assertTrue($result);
    }

    public function testWaitDisappear()
    {
        SeleniumAPI::resetInstance();
        $seleniumAPI = SeleniumAPI::getInstance();
        $browser = $seleniumAPI->getBrowser();
        $browser->open($_ENV["TEST_HOST"]);
        $browser->visibilityByCss(".container");
        $browser->waitDisappearByCss("#disappear-element");
        $result = $browser->hasByCss("#disappear-element");
        $this->assertFalse($result);
    }

    public function testIfAppears()
    {
        SeleniumAPI::resetInstance();
        $seleniumAPI = SeleniumAPI::getInstance();
        $browser = $seleniumAPI->getBrowser();
        $browser->open($_ENV["TEST_HOST"]);
        $browser->visibilityByCss(".container");
        $firstTest = false;
        $secondTest = false;
        $browser->ifAppearsByCss("#appear-element", function () use (&$firstTest) {
            $firstTest = true;
        });
        $browser->ifAppearsByCss("#some-another-element", function () use (&$secondTest) {
            $secondTest = true;
        });
        $this->assertTrue($firstTest);
        $this->assertFalse($secondTest);
    }

    public function testWaitCount()
    {
        SeleniumAPI::resetInstance();
        $seleniumAPI = SeleniumAPI::getInstance();
        $browser = $seleniumAPI->getBrowser();
        $browser->open($_ENV["TEST_HOST"]);
        $browser->visibilityByCss(".container");
        $browser->waitCountByCss(".count-element", 4);
        $countElements = $browser->countByCss(".count-element");
        $this->assertGreaterThan(3, $countElements);
    }

    public function testWaitVisibility()
    {
        SeleniumAPI::resetInstance();
        $seleniumAPI = SeleniumAPI::getInstance();
        $browser = $seleniumAPI->getBrowser();
        $browser->open($_ENV["TEST_HOST"]);
        $browser->visibilityByCss("#visibility-element");
        $element = $browser->findByCss("#visibility-element");
        $displayValue = $element->getCssValue("display");
        $this->assertEquals($displayValue, "block");
    }

    public function testHas()
    {
        SeleniumAPI::resetInstance();
        $seleniumAPI = SeleniumAPI::getInstance();
        $browser = $seleniumAPI->getBrowser();
        $browser->open($_ENV["TEST_HOST"]);
        $browser->visibilityByCss(".container");
        $this->assertTrue($browser->hasByName("div"));
        $this->assertFalse($browser->hasByName("img"));
    }

    public function testList()
    {
        SeleniumAPI::resetInstance();
        $seleniumAPI = SeleniumAPI::getInstance();
        $browser = $seleniumAPI->getBrowser();
        $browser->open($_ENV["TEST_HOST"]);
        $browser->visibilityByCss(".container");
        $list = $browser->listByCss(".list-element");
        $this->assertEquals(count($list), 4);
    }

    public function testCount()
    {
        SeleniumAPI::resetInstance();
        $seleniumAPI = SeleniumAPI::getInstance();
        $browser = $seleniumAPI->getBrowser();
        $browser->open($_ENV["TEST_HOST"]);
        $browser->visibilityByCss(".container");
        $count = $browser->countByCss(".list-element");
        $this->assertEquals($count, 4);
    }

    public function testClipboard()
    {
        SeleniumAPI::resetInstance();
        $seleniumAPI = SeleniumAPI::getInstance();
        $browser = $seleniumAPI->getBrowser();
        $browser->open($_ENV["TEST_HOST"]);
        $browser->visibilityByCss("#clipboard_element");
        $testString = "Test clip Тест русский";
        $browser->sendToClipboard($testString);
        $input = $browser->findByCss("#clipboard_element");
        $browser->click($input);
        $browser->pasteFromClipboard();
        $resultText = $input->getAttribute("value");
        $this->assertSame($testString, $resultText);
    }

    public function testUsingProxy()
    {
        SeleniumAPI::resetInstance();
        $params = new SeleniumParams();
        $params->setProxy($_ENV["PROXY_HOST"].":".$_ENV["PROXY_PORT"], $_ENV["PROXY_USER"], $_ENV["PROXY_PASS"]);
        $seleniumAPI = SeleniumAPI::getInstance();
        $browser = $seleniumAPI->getBrowser($params);
        $browser->open("https://www.myexternalip.com/raw");
        $ip = $browser->findByName("body")->getText();
        $this->assertSame($_ENV["PROXY_HOST"], $ip);
    }
}
