**Frames-console**

[![Latest Stable Version](http://poser.pugx.org/vpa/frames-console/v)](https://packagist.org/packages/vpa/frames-console) [![Total Downloads](http://poser.pugx.org/vpa/frames-console/downloads)](https://packagist.org/packages/vpa/frames-console) [![Latest Unstable Version](http://poser.pugx.org/vpa/frames-console/v/unstable)](https://packagist.org/packages/vpa/frames-console) [![License](http://poser.pugx.org/vpa/frames-console/license)](https://packagist.org/packages/vpa/frames-console) [![PHP Version Require](http://poser.pugx.org/vpa/frames-console/require/php)](https://packagist.org/packages/vpa/frames-console)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/zolll23/frames-console/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/zolll23/frames-console/?branch=main)
[![Code Coverage](https://scrutinizer-ci.com/g/zolll23/frames-console/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/zolll23/frames-console/?branch=main)
[![Build Status](https://scrutinizer-ci.com/g/zolll23/frames-console/badges/build.png?b=main)](https://scrutinizer-ci.com/g/zolll23/frames-console/build-status/main)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/zolll23/frames-console/badges/code-intelligence.svg?b=main)](https://scrutinizer-ci.com/code-intelligence)

A flexible set of components that allow you to frame various content in the console. Supports the display of text, block elements and tables, including nested ones.

### Install
```
composer require vpa/frames-console
```

### Get Started

If you want to display multiple elements - we recommend using the Page root element.
You can initialize the first element yourself:
```
use VPA\Console\Shell;
use VPA\Console\FrameConsoleConfig;
use VPA\Console\Glyphs\Page;

require_once(__DIR__ . '/../vendor/autoload.php');

$shell = new Shell();
$config = new FrameConsoleConfig($shell);
$page = new Page($config);
```
or use DI Container:
```
use VPA\DI\Container;
use VPA\Console\FrameConsoleConfig;
use VPA\Console\Glyphs\Page;

require_once(__DIR__ . '/../vendor/autoload.php');

$di = new Container();
$di->registerContainers([
    'VPA\Console\FrameConfigInterface' => FrameConsoleConfig::class,
]);

$page = $di->get(Page::class);
```
### Documentation

#### Base elements:

1. [Text element](https://github.com/zolll23/frames-console/blob/main/docs/Text.md)
2. [DIV element](https://github.com/zolll23/frames-console/blob/main/docs/Div.md)
3. [Table element](https://github.com/zolll23/frames-console/blob/main/docs/Table.md)

#### Components:
1. [Table1D element](https://github.com/zolll23/frames-console/blob/main/docs/Table1D.md)
