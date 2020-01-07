<?php

namespace Seriyyy95\SeleniumChromeLibrary;

class SeleniumMap
{
    private $drivers;

    public function __construct()
    {
      $this->drivers = array();
    }

    public function addDriver($driver, SeleniumParams $params){
      $path = $params->getDataPath();
      if(!isset($this->drivers[$path])){
        $this->drivers[$path] = $driver;
      }else{
        throw new \Exception("Driver for $path already exists!");
      }
    }

    public function setDriver($driver, SeleniumParams $params){
      $path = $params->getDataPath();
      $this->drivers[$path] = $driver;
    }

    public function getDriver(SeleniumParams $params){
      $path = $params->getDataPath();
      if(isset($this->drivers[$path])){
        return $this->drivers[$path];
      }else{
        throw new \Exception("Driver for $path not found!");
      }
    }

    public function hasDriver(SeleniumParams $params){
      $path = $params->getDataPath();
      if(isset($this->drivers[$path])){
        return true;
      }else{
        return false;
      }
    }

    public function removeDriver($currentDriver){
      for($i = 0; $i < count($this->drivers); $i++){
        if($this->drivers[$i] == $currentDriver){
          unset($this->drivers[$i]);
          return true;
        }
      }
      return false;
    }

    public function allDrivers(){
      return $this->drivers;
    }

}
