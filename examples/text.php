<?php

use VPA\DI\Container;
use VPA\Console\FrameConsoleConfig;
use VPA\Console\Glyphs\Page;

require_once(__DIR__ . '/../vendor/autoload.php');

$di = new Container();
$di->registerContainers([
    'VPA\Console\FrameConfigInterface' => FrameConsoleConfig::class,
]);

$page = $di->get(Page::class);
$page->setPadding(3, 3, 3, 3);
$documentWidth = $page->getDocumentWidth();
echo str_repeat("\n", 5);
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
echo str_repeat("\n", 5);