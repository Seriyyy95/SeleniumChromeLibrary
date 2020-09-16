<?php

namespace Seriyyy95\SeleniumChromeLibrary;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DriverCommand;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;
use Facebook\WebDriver\Interactions\WebDriverActions;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;
use Facebook\WebDriver\Chrome\ChromeOptions;

class SeleniumBrowser
{
    protected $driver;
    protected $root;

    use ElementTrait;

    public function __construct($driver)
    {
        $this->driver = $driver;
        $this->root = $driver;
    }

    public function getDriver()
    {
        return $this->driver;
    }

    public function open(string $url)
    {
        $this->driver->get($url);
    }

    public function close()
    {
        $this->driver->quit();
    }

    public function click(WebDriverElement $element)
    {
        $action = new WebDriverActions($this->driver);
        $action->moveToElement($element)->perform();
        $action->click()->perform();
    }

    public function waitTitle(string $title)
    {
        $this->driver->wait(5, 3000)->until(
            WebDriverExpectedCondition::titleContains($title)
        );
    }

    public function waitVisibility(WebDriverBy $selector)
    {
        $this->driver->wait(10, 3000)->until(
            WebDriverExpectedCondition::visibilityOfElementLocated($selector)
        );
    }

    public function visibilityByName(string $selector)
    {
        $driverBy = WebDriverBy::tagName($selector);
        return $this->waitVisibility($driverBy);
    }


    public function visibilityByCss(string $selector)
    {
        $driverBy = WebDriverBy::cssSelector($selector);
        return $this->waitVisibility($driverBy);
    }

    public function visibilityByXpath(string $selector)
    {
        $driverBy = WebDriverBy::xpath($selector);
        return $this->waitVisibility($driverBy);
    }

    public function doAndWaitReload($closure)
    {
        $id = $this->driver->findElement(WebDriverBy::cssSelector('html'))->getId();
        $url = $this->driver->getCurrentUrl();
        $closure();
        //5 - таймаут, секунды
        //1000 - интервал проверки, мс
        $this->driver->wait(5, 1000)->until(function () use ($id, $url) {
            $html = $this->driver->findElement(WebDriverBy::cssSelector('html'));
            if ($html->getId() != $id) {
                return true;
            }
            if ($this->driver->getCurrentUrl() != $url) {
                return true;
            }
        }, "Timeout on waiting for page reloading");
        return true;
    }

    public function doAndWaitIncrease(WebDriverBy $selector, $closure, $timeout=20)
    {
        $count = $this->count($selector);
        $closure();
        $this->driver->wait($timeout, 2000)->until(function () use ($count, $selector) {
            $currentCount = count($this->driver->findElements($selector));
            if ($currentCount > $count) {
                return true;
            }
        }, "Timeout on waiting for increase element");
        return true;
    }

    public function waitAppearByCss(string $selector)
    {
        $driverBy = WebDriverBy::cssSelector($selector);
        $this->waitAppear($driverBy);
    }

    public function waitAppearByXpath(string $selector)
    {
        $driverBy = WebDriverBy::xpath($selector);
        $this->waitAppear($driverBy);
    }


    public function ifAppearsByCss(string $selector, $closure, $timeout = 5)
    {
        $driverBy = WebDriverBy::cssSelector($selector);
        return $this->ifAppears($driverBy, $closure, $timeout);
    }

    public function ifAppearsByXpath(string $selector, $closure, $timeout = 5)
    {
        $driverBy = WebDriverBy::xpath($selector);
        return $this->ifAppears($driverBy, $closure, $timeout);
    }


    public function waitAppear(WebDriverBy $selector, $timeout = 5)
    {
        $this->driver->wait($timeout, 1000)->until(function () use (&$selector) {
            $elements = $this->driver->findElements($selector);
            if (count($elements) > 0) {
                return true;
            }
        }, "Request element not found, timeout");
    }

    public function waitDisappear(WebDriverBy $selector)
    {
        $this->driver->wait(5, 1000)->until(function () use ($selector) {
            $elements = $this->driver->findElements($selector);
            if (count($elements) == 0) {
                return true;
            }
        }, "Request element still on page, timeout");
    }

    public function waitDisappearByCss(string $selector)
    {
        $driverBy = WebDriverBy::cssSelector($selector);
        $this->waitDisappear($driverBy);
    }

    public function waitDisappearByXpath(string $selector)
    {
        $driverBy = WebDriverBy::xpath($selector);
        $this->waitDisappear($driverBy);
    }

    public function waitDisappearName(string $selector)
    {
        $driverBy = WebDriverBy::tagName($selector);
        $this->waitDisappear($driverBy);
    }


    public function waitCount(WebDriverBy $selector, $count, $timeout = 20)
    {
        $this->driver->wait($timeout, 1000)->until(function () use (&$selector, $count) {
            $elements = $this->driver->findElements($selector);
            if (count($elements) >= $count) {
                return true;
            }
        }, "Request element not found, timeout");
    }

    public function waitCountByCss(string $selector, $count)
    {
        $driverBy = WebDriverBy::cssSelector($selector);
        $this->waitCount($driverBy, $count);
    }

    public function waitCountByXpath(string $selector, $count)
    {
        $driverBy = WebDriverBy::xpath($selector);
        $this->waitCount($driverBy, $count);
    }

    public function ifAppears(WebDriverBy $selector, $closure, $timeout = 5)
    {
        try {
            $this->waitAppear($selector, $timeout);
            $closure();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function script($code)
    {
        return $this->driver->executeScript($code);
    }
    
    public function sendToClipboard($string)
    {
        $keyboard = $this->driver->getKeyboard();
        $this->script("window.open();");
        sleep(1);
        $tabs = $this->driver->getWindowHandles();
        $this->driver->switchTo()->window($tabs[1]);
        $this->driver->get("data:text/html, <html contenteditable>$string</html>");
        sleep(1);
        $keyboard->pressKey(WebDriverKeys::CONTROL);
        $keyboard->sendKeys("a");
        $keyboard->releaseKey(WebDriverKeys::CONTROL);
        sleep(1);
        $keyboard->pressKey(WebDriverKeys::CONTROL);
        $keyboard->pressKey("c");
        $keyboard->releaseKey(WebDriverKeys::CONTROL);
        sleep(1);
        $this->driver->close();
        $this->driver->switchTo()->window($tabs[0]);
    }

    public function pasteFromClipboard()
    {
        $keyboard = $this->driver->getKeyboard();
        $keyboard->pressKey(WebDriverKeys::CONTROL);
        $keyboard->sendKeys("v");
        $keyboard->releaseKey(WebDriverKeys::CONTROL);
    }

    public function sendKeys($string)
    {
        $this->driver->getKeyboard()->sendKeys($string);
    }

    public function takeScreenshot()
    {
        if ($this->driver != null) {
            $time = time();
            $elementPath = __DIR__ . "/../../../public/attachments/screenshot-$time.png";
            $this->driver->takeScreenshot($elementPath);
            return "/attachments/screenshot-$time.png";
        } else {
            return "";
        }
    }
}
