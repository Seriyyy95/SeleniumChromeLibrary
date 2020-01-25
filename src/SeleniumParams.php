<?php

namespace Seriyyy95\SeleniumChromeLibrary;

class SeleniumParams
{
    private static $dataPrefix = "dir";
    private $seleniumHost = "http://localhost";
    private $seleniumDataDir = "/tmp/Selenium/";
    private $seleniumDataSubdir = "";
    private $loadStrategy = "normal";
    private $proxyIp = null;
    private $proxyUser = null;
    private $proxyPass = null;

    public function __construct()
    {
        $this->setDataSubdir("default");
        $defaultHost = SeleniumAPI::getInstance()->getDefaultHost();
        if ($defaultHost != null) {
            $this->seleniumHost = $defaultHost;
        }
    }

    public static function setDataPrefix(string $prefix)
    {
        self::$dataPrefix = $prefix;
    }

    public static function getDataPrefix()
    {
        return self::$dataPrefix;
    }

    public function setLoadStrategy(string $strategy)
    {
        if ($strategy == "normal"
            || $strategy == "eager"
            || $strategy == "none") {
            $this->loadStrategy = $strategy;
        } else {
            throw new \Exception("Unknown load strategy $strategy");
        }
    }

    public function setDataDir(string $dataDir)
    {
        $this->seleniumDataDir = $dataDir;
    }

    public function setHost(string $host)
    {
        $this->seleniumHost = $host;
    }

    public function setDataSubDir(string $directory)
    {
        $this->seleniumDataSubdir = $directory;
    }

    public function setProxy(string $proxyIp, string $user = null, string $pass = null)
    {
        $this->proxyIp = $proxyIp;
        $this->proxyUser = $user;
        $this->proxyPass = $pass;
    }

    public function hasProxy()
    {
        if ($this->proxyIp) {
            return true;
        } else {
            return false;
        }
    }

    public function getProxyIp()
    {
        return $this->proxyIp;
    }

    public function getProxyUser()
    {
        return $this->proxyUser;
    }

    public function getProxyPass()
    {
        return $this->proxyPass;
    }



    public function getHost()
    {
        return $this->seleniumHost;
    }

    public function getDataPath()
    {
        return $this->seleniumDataDir . self::$dataPrefix . "_" . $this->seleniumDataSubdir;
    }

    public function getLoadStrategy()
    {
        return $this->loadStrategy;
    }
}
