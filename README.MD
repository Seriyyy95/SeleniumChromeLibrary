The library allows you to use Selenium on PHP with Google Chrome/Chromium Web Browser. Capabilities:

- One instance for manage all drivers
- Manage multipe running selenium sessions
- Simple objective interface for configuring Selenium driver
- Simple objective interface for accessing to web page elements
- Simple wait methods
- Proxy support
- Data path support
- Auto shutdown all running selenium sessions on program exit

# Install

For installing ChromeSeleniumLibrary just run:

```
composer install seriyyy95/chrome-selenium-library
```

# Use

Basic running usage example:

```
use Seriyyy95\ChromeSeleniumLibrary\SeleniumParams;
use Seriyyy95\ChromeSeleniumLibrary\SeleniumAPI;

$params = new SeleniumParams();
$params->setHost("localhost");
$params->setDataPath("/tmp/selenium");
$browser = SeleniumAPI::getInstance()->getBrowser($params);
$browser->open("https://google.com");
```

Also you can get a driver:

```
...
$driver = SeleniumAPI::getInstance()->getDriver($params);
...
```

Basic SeleniumBrowser methods:

- **waitTitle** - wait for page title loading
- **click** - click on element
- **visibilityByCss** - wait visibility of element by CSS selector
- **visibilityByXpath** - wait visibility of element by Xpath selector
- **doAndWaitReload** - do anything and wait for page reload
- **doAndWaitIncrease** - do anything and wait for number of some elements increase
- **waitAppearByCss** - wait appear element on page identified by CSS selector
- **waitAppearByXpath** - wait appear element on page identified by Xpath selector
- **ifAppearsByCss** - do something if element appears on page, if it not appears - do nothing, element identifies by CSS selector
- **ifAppearsByXpath** - same, but element identifies by Xpath selector
- **waitDisappearCss** - wait for some element identified by CSS disapear on page
- **waitDisappearXpath** - same, but element identified by Xpath selector
- **waitCountByCss** - wait until number of elements identified by CSS selector be greater than given value
- **waitCountByXpath** - same, but with Xpath selector
- **script** - run JavaScript script
- **typeKeys** - send keys to browser
- **takeScreenshot** - take screenshot of browser window

SeleniumBrowser methods for managing elements:

- hasByCss
- hasByXpath
- hasByName
- countByCss
- countByXpath
- countByName
- findByCss
- findByXpath
- findByName
- listByCss
- listByXpath
- listByName

Explanation:

- The **has*** methods check if element is on page and return bool value.
- The **count*** methods return number of elements on page.
- The **find*** methods return first WebElement on page that match with selector. Same as Selenium findElement method.
- The **list*** methods return array of WebElement objects by given selector.

If you want to use these methods for some Element, you should use SeleniumElement wrapper for WebElement

```
...
$element = new SeleniumElement($webElement);
$list = $element->listByCss("div.some_class");
var_dump($list)
...
```
Show tests folder for more examples.