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
// Padding top for demo
echo str_repeat("\n", 5);
// Single Div
$div = $page->addDiv()->setPadding(1, 1, 0, 0)->setBorder(1, 1, 1, 1)->addText()->setValue("Line 1\nLine 2\nLine 3");
// Div with text and another div
$div = $page->addDiv()->setPadding(3, 3, 1, 1)->setBorder(1, 1, 1, 1);
$div->addText()->setValue("Line 1\nLine 2\nLine 3");
$div2 = $div->addDiv()->setPadding(1, 1, 0, 0)->setBorder(1, 1, 1, 1);
$div2->addText()->setValue("Line 1\nLine 2\nLine 3");

$page->display();

// Padding bottom for demo
echo str_repeat("\n", 5);
