<?php

use VPA\Console\Color;
use VPA\DI\Container;
use VPA\Console\Shell;
use VPA\Console\FrameConsoleConfig;
use VPA\Console\Glyphs\Page;

require_once(__DIR__ . '/../vendor/autoload.php');

$di = new Container();
$di->registerContainers([
    'VPA\Console\FrameConfigInterface' => FrameConsoleConfig::class,
    'VPA\Shell' => Shell::class,
]);

$page = $di->get(Page::class);

$table = $page->addTable(['color'=>Color::GREEN,'borderColor'=>Color::RED, 'backgroundColor'=>Color::WHITE])->setBorder(1, 1, 1, 1);
$row = $table->addRow();
$row->addCell()->setPadding(1, 1, 0, 0)->setBorder(0, 1, 0, 0)->addText()->setValue("Table 1\nCell 1");
$cell1 = $row->addCell()->setPadding(0, 0, 0, 0);
$table2 = $cell1->addTable(['borderColor'=>Color::GREEN])->setBorder(1, 1, 1, 1);
$row2 = $table2->addRow();
$row2->addCell()->setPadding(1, 1, 0, 0)->setBorder(0, 1, 0, 0)->addText()->setValue("Table 2\nCell 1");
$cell2 = $row2->addCell()->setPadding(1, 1, 0, 0)->setBorder(0, 0, 0, 0);
$table3 = $cell2->addTable(['borderColor'=>Color::CYAN])->setBorder(1, 1, 1, 1);
$row3 = $table3->addRow();
$row3->addCell()->setPadding(1, 1, 0, 0)->setBorder(0, 1, 0, 0)->addText()->setValue("Table 3\nCell 1");
$row3->addCell()->setPadding(1, 1, 0, 0)->setBorder(0, 0, 0, 0)->addText()->setValue("Table 3\nCell 2");

$page->display();