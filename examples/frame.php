<?php

use VPA\Console\Frame;
use VPA\Console\FrameConsoleConfig;
use VPA\Console\FrameSimpleConfig;
use VPA\Console\Glyphs\Page;
use VPA\Console\Glyphs\Table;
use VPA\Console\Table1D;
use VPA\DI\Container;

require_once(__DIR__ . '/../vendor/autoload.php');

//echo class_exists(Table::class)."\n";
//var_dump(new Table(new FrameConsoleConfig()));
$di = new Container();
$di->registerContainers([
    'VPA\Console\FrameConfigInterface' => FrameConsoleConfig::class,
]);

$table = new Table1D([
    'Project'=> 'Frames Console',
    'Version'=> 'v0.1.0',
    'Description' => 'The library to beautiful display of tables on console',
]);

/*
$page = $di->get(Page::class);
$table = $page->addTable();
$header = $table->addRow();
$header->addCell()->setPadding(1,1,0,0)->addText()->setValue("First Name");
$header->addCell()->setPadding(1,1,0,0)->addText()->setValue("Last Name");
$page->display();
echo $header->getDocumentWidth()."\n";
echo $page->getWidthByContent()."\n";
*/
/*
$frame = (new Frame([5, 12, 35]))->padding(2);
$frame->row(['10', 'test', 'This is test']);
$frame->row(['12345', 'estqqq', 'This is test !!!']);
$frame->display();
*/