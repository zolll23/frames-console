**Frames-console**

[![Latest Stable Version](http://poser.pugx.org/vpa/frames-console/v)](https://packagist.org/packages/vpa/frames-console) [![Total Downloads](http://poser.pugx.org/vpa/frames-console/downloads)](https://packagist.org/packages/vpa/frames-console) [![Latest Unstable Version](http://poser.pugx.org/vpa/frames-console/v/unstable)](https://packagist.org/packages/vpa/frames-console) [![License](http://poser.pugx.org/vpa/frames-console/license)](https://packagist.org/packages/vpa/frames-console) [![PHP Version Require](http://poser.pugx.org/vpa/frames-console/require/php)](https://packagist.org/packages/vpa/frames-console)

A flexible set of components that allow you to frame various content in the console. Supports the display of text, block elements and tables, including nested ones.

**Install**
```
composer require vpa/frames-console
```

**Get Started**

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

**Output text**

Not the most useful application, but why not?

We assume that you have already initialized the element in the previous paragraph.

Examples:
```
$documentWidth = $page->getDocumentWidth();

// Single short line
$page->addText()->setValue("Line 1");
$page->addText()->setValue("----------");
// One long line with a length limit of up to screen width
$page->addText(['maxWidth'=>$documentWidth])->setValue("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\n");
$page->addText()->setValue("----------");
// Multiline long line with a length limit of up to default maxWidth and with new line symbols
$text = $page->addText()->setValue("Lorem ipsum dolor sit amet, consectetur adipiscing elit,\n sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.\nUt enim ad minim veniam, quis nostrud exercitation\n ullamco laboris nisi ut aliquip ex ea commodo consequat.\n Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore\n eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt\n in culpa qui officia deserunt mollit anim id est laborum.\n");
$page->display();
```
You can see:

![Screenshot of Text example](docs/text.png)

Text element supports the attribute __textAlign__: left (default), center, right.

Example:
```
// Single short line
$page->addText(['maxWidth' => 100, 'textAlign' => 'center'])->setValue("Line 1");
$page->addText(['maxWidth' => 100, 'textAlign' => 'center'])->setValue("----------");
// One line with a length limit of to 100 characters
$page->addText(['maxWidth' => 100])->setValue("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\n");
$page->addText(['maxWidth' => 100, 'textAlign' => 'center'])->setValue("----------");
// Multiline line with a length limit of up to 100 characters and with new line symbols
$text = $page->addText(['maxWidth' => 100])->setValue("Lorem ipsum dolor sit amet, consectetur adipiscing elit,\n sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.\nUt enim ad minim veniam, quis nostrud exercitation\n ullamco laboris nisi ut aliquip ex ea commodo consequat.\n Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore\n eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt\n in culpa qui officia deserunt mollit anim id est laborum.\n");
// TextAlign right
$text = $page->addText(['textAlign' => 'right', 'maxWidth' => 100])->setValue("Lorem ipsum dolor sit amet, consectetur adipiscing elit,\n sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.\nUt enim ad minim veniam, quis nostrud exercitation\n ullamco laboris nisi ut aliquip ex ea commodo consequat.\n Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore\n eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt\n in culpa qui officia deserunt mollit anim id est laborum.\n");
// TextAlign center
$text = $page->addText(['textAlign' => 'center', 'maxWidth' => 100])->setValue("Lorem ipsum dolor sit amet, consectetur adipiscing elit,\n sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.\nUt enim ad minim veniam, quis nostrud exercitation\n ullamco laboris nisi ut aliquip ex ea commodo consequat.\n Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore\n eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt\n in culpa qui officia deserunt mollit anim id est laborum.\n");

$page->display();
```

![Screenshot of Text example](docs/textWithAlign.png)
