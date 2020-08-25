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

    //Проверить есть ли элемент с указанным селектором на странице
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

    public function hasByCss(string $string)
    {
        return $this->has(WebDriverBy::cssSelector($string));
    }

    public function hasByXpath(string $string)
    {
        return $this->has(WebDriverBy::xpath($string));
    }

    public function hasByName(string $string)
    {
        return $this->has(WebDriverBy::tagName($string));
    }

    //Подсчёт количества элементов
    public function count(WebDriverBy $selector){
        return count($this->root->findElements($selector));
    }

    public function countByCss($string){
        return $this->count(WebDriverBy::cssSelector($string));
    }

    public function countByXpath($string){
        return $this->count(WebDriverBy::xpath($string));
    }

    public function countByName($string){
        return $this->count(WebDriverBy::tagName($string));
    }

    //Выбрать первый элемент с указанным селектором на странице
    public function find(WebDriverBy $selector)
    {
        return $this->root->findElement($selector);
    }

    public function findByName(string $string)
    {
        return $this->find(WebDriverBy::tagName($string));
    }


    public function findByCss(string $string)
    {
        return $this->find(WebDriverBy::cssSelector($string));
    }

    public function findByXpath(string $string)
    {
        return $this->find(WebDriverBy::xpath($string));
    }

    //Список элементов подпадающих под селектор на странице
    public function list(WebDriverBy $selector){
        return $this->root->findElements($selector);
    }

    public function listByName(string $string, $block=null){
        return $this->list(WebDriverBy::tagName($string));
    }

    public function listByCss(string $string, $block=null){
        return $this->list(WebDriverBy::cssSelector($string));
    }

    public function listByXpath(string $string, $block=null){
        return $this->list(WebDriverBy::xpath($string));
    }

    public function hasByCssInElement(string $string, $element)
    {
        error_log("The method hasByCssInElement is depecated!");
        if (count($element->findElements(WebDriverBy::cssSelector($string))) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function listByCssInElement(string $string, $element){
        error_log("The method listByCssInElement is depecated!");
        return $element->findElements(WebDriverBy::cssSelector($string));
    }




}