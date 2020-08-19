<?php

namespace Seriyyy95\SeleniumChromeLibrary;

class SeleniumElement{
   
    protected $root;

    use ElementTrait;

    public function __construct($element){
        $this->root = $element;
    }

}