<?php

use VPA\Console\Color;
use VPA\Console\Glyphs\Div;
use VPA\Console\SymbolMode;
use VPA\DI\Container;
use VPA\Console\FrameConsoleConfig;
use VPA\Console\Glyphs\Page;

require_once(__DIR__ . '/../vendor/autoload.php');

$di = new Container();
$di->registerContainers([
    'VPA\Console\FrameConfigInterface' => FrameConsoleConfig::class,
]);

$page = $di->get(Page::class);
$page->setConfig([
    'borderColor' => Color::BLACK,
    'backgroundColor' => Color::GREEN
])->setBorder(1, 1, 1, 1);
$page->setPadding(1, 1, 1, 1);
$documentWidth = $page->getDocumentWidth();
echo str_repeat("\n", 5);
$page->addText([
    'mode' => SymbolMode::DEFAULT, 'color' => Color::RED, 'backgroundColor' => Color::BLACK])
    ->setValue("Line 1");
$page->addText([
    'mode' => SymbolMode::BOLD, 'color' => Color::RED, 'backgroundColor' => Color::BLACK])
    ->setValue("Line 1");
$page->addText(['mode' => SymbolMode::INVERSE, 'color' => Color::RED, 'backgroundColor' => Color::BLACK])
    ->setValue("Line 1");
$page->addText(['mode' => SymbolMode::UNDERLINE, 'color' => Color::RED, 'backgroundColor' => Color::BLACK])
    ->setValue("Line 1");
$page->addText(['mode' => SymbolMode::BLINKING, 'color' => Color::RED, 'backgroundColor' => Color::BLACK])
    ->setValue("Line 1");
$page->addText([
    'mode' => SymbolMode::DEFAULT, 'color' => Color::RED, 'backgroundColor' => Color::WHITE])
    ->setValue("Line 1");
$page->addText([
    'mode' => SymbolMode::BOLD, 'color' => Color::RED, 'backgroundColor' => Color::WHITE])
    ->setValue("Line 1");
$page->addText(['mode' => SymbolMode::INVERSE, 'color' => Color::RED, 'backgroundColor' => Color::WHITE])
    ->setValue("Line 1");
$page->addText(['mode' => SymbolMode::UNDERLINE, 'color' => Color::RED, 'backgroundColor' => Color::WHITE])
    ->setValue("Line 1");
$page->addText(['mode' => SymbolMode::BLINKING, 'color' => Color::RED, 'backgroundColor' => Color::WHITE])
    ->setValue("Line 1");

$div = $page->addDiv()->setPadding(1, 1, 1, 1)->setBorder(1, 1, 1, 1);
$div->setConfig([
    'mode' => SymbolMode::DEFAULT,
    'borderColor' => Color::RED,
    'backgroundColor' => Color::BROWN
]);
$div->addText([
    'borderColor' => Color::RED,
    'backgroundColor' => Color::BLUE
])->setValue("Line 2");

$page->display();

echo str_repeat("\n", 5);