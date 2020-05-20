<?php

namespace Seriyyy95\SeleniumChromeLibrary;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DriverCommand;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Interactions\WebDriverActions;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\Remote\WebDriverCapabilityType;
use Facebook\WebDriver\Chrome\ChromeOptions;
use ZipArchive;

class SeleniumAPI
{
    private static $instance = null;
    private $seleniumMap;
    private $lastDriver = null;
    private $defaultHost = null;

    private function __construct()
    {
        $this->seleniumMap = new SeleniumMap();
        register_shutdown_function(function () {
            $seleniumApi = SeleniumAPI::getInstance();
            $seleniumApi->shutdownAllDrivers();
        });
    }

    public function setDefaultHost(string $host){
	$this->defaultHost = $host;
    }

    public function getDefaultHost(){
	return $this->defaultHost;
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function resetInstance(){
	if(self::$instance != null){
		self::$instance->shutdownAllDrivers();
		self::$instance = null;
	}
    }

    public function setDataPrefix(string $prefix){
      $this->shutdownAllDrivers();
      SeleniumParams::setDataPrefix($prefix);
    }

    public function checkDriver($driver){
        try{
            $url = $driver->getCurrentUrl();
            return true;
        }catch(\Exception $e){
            return false;
        }
    }

    public function getDriver(SeleniumParams $params = null)
    {
        if($params == null){
            $params = new SeleniumParams();
        }
        if($this->seleniumMap->hasDriver($params)){
          $driver = $this->seleniumMap->getDriver($params);
          if (!$this->checkDriver($driver)) {
              $driver = $this->setupDriver($params);
              $this->seleniumMap->setDriver($driver, $params);
          }
          $this->lastDriver = $driver;
        }else{
          $this->lastDriver = $this->setupDriver($params);
          $this->seleniumMap->addDriver($this->lastDriver, $params);
        }
        return $this->lastDriver;
    }

    public function getBrowser(SeleniumParams $params = null){
        $driver = $this->getDriver($params);
        $browser = new SeleniumBrowser($driver);
        return $browser;
    }

    public function getLastDriver(){
        return $this->lastDriver;
    }

    public function shutdownAllDrivers(){
        $drivers = $this->seleniumMap->allDrivers();
        foreach($drivers as $driver){
            $driver->quit();
        }
    }

    private function setupDriver(SeleniumParams $params)
    {
        $host = $params->getHost() . ':4444/wd/hub';

        $sessions = RemoteWebDriver::getAllSessions($host);
        foreach($sessions as $session){
            $sessionPath = $session["capabilities"]["chrome"]["userDataDir"];
//            if($sessionPath == $params->getDataPath()){
              $driver = RemoteWebDriver::createBySessionID($session["id"], $host);
              $driver->quit();
//            }
        }

        $desiredCapabilities = DesiredCapabilities::chrome();
        $desiredCapabilities->setCapability("pageLoadStrategy", $params->getLoadStrategy());

        $options = new ChromeOptions();
        $options->addArguments(array("--disable-notifications"));
//        $options->addArguments(array("--headless"));
        $options->addArguments(array("--disable-gpu"));
        $options->addArguments(array("--disable-popup-blocking"));
        $options->addArguments(array('--disable-blink-features="BlockCredentialedSubresources"'));
        $options->addArguments(array("--no-sandbox"));
        $options->addArguments(array("--window-size=1900x800"));
        $options->addArguments(array("--ignore-certificate-errors"));
        $options->addArguments(array("--user-data-dir=" . $params->getDataPath()));
//        $options->setBinary("/usr/bin/chromium-browser");

        if($params->hasProxy()){
            $proxyParams = explode(':', $params->getProxyIp());
            $proxyIp = $proxyParams[0];
            $proxyPort = $proxyParams[1];
            $proxyUsername = $params->getProxyUser();
            $proxyPassword = $params->getProxyPass();
    
            $pluginForProxyLogin = '/tmp/a' . uniqid() . '.zip';
    
            $zip = new ZipArchive();
            $res = $zip->open($pluginForProxyLogin, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            $zip->addFile(__DIR__ . '/../resources/proxyextendsion/manifest.json', 'manifest.json');
            $background = file_get_contents(__DIR__ . '/../resources/proxyextendsion/background.js');
            $background = str_replace(['%proxy_host', '%proxy_port', '%username', '%password'], [$proxyIp, $proxyPort, $proxyUsername, $proxyPassword], $background);
            $zip->addFromString('background.js', $background);
            $zip->close();
           
            $options->addExtensions([$pluginForProxyLogin]);
        }

        $desiredCapabilities->setCapability(ChromeOptions::CAPABILITY, $options);
            $driver = RemoteWebDriver::create(
                $host,
                $desiredCapabilities,
                160 * 1000, // Connection timeout in miliseconds
                160 * 1000  // Request timeout in miliseconds);
            );
//            shell_exec("ps aux | grep chrom | awk '{print $2}' | xargs kill -TERM");
        return $driver;
    }
}
