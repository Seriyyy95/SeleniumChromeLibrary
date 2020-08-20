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

trait ElementTrait {

    public function has(WebDriverBy $selector, $block = null)
    {
        if ($block == null) {
            $block = $this->root;
        }
        if (count($block->findElements($selector)) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function findByName(string $string)
    {
        return $this->root->findElement(WebDriverBy::tagName($string));
    }


    public function findByCss(string $string)
    {
        return $this->root->findElement(WebDriverBy::cssSelector($string));
    }

    public function listByCss(string $string, $block=null){
        return $this->root->findElements(WebDriverBy::cssSelector($string));
    }

    public function findByXpath(string $string)
    {
        return $this->root->findElement(WebDriverBy::xpath($string));
    }

    public function hasByCss(string $string)
    {
        if (count($this->root->findElements(WebDriverBy::cssSelector($string))) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function hasByXpath(string $string)
    {
        if (count($this->root->findElements(WebDriverBy::xpath($string))) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function hasByCssInElement(string $string, $element)
    {
        if (count($element->findElements(WebDriverBy::cssSelector($string))) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function listByCssInElement(string $string, $element){
        return $element->findElements(WebDriverBy::cssSelector($string));
    }




}