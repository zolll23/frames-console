<?php

use VPA\Console\Frame;
use VPA\Console\FrameConsoleConfig;
use VPA\Console\FrameSimpleConfig;
use VPA\Console\Glyphs\Page;
use VPA\Console\Glyphs\Table;
use VPA\DI\Container;

require_once(__DIR__ . '/../vendor/autoload.php');

//echo class_exists(Table::class)."\n";
//var_dump(new Table(new FrameConsoleConfig()));
$di = new Container();
$di->registerContainers([
    'VPA\Console\FrameConfigInterface' => FrameConsoleConfig::class,
]);


$page = $di->get(Page::class);

$div = $page->addDiv()->setBorder(1, 1, 1, 1)->setPadding(0, 0, 0, 0);
$div2 = $div->addDiv()->setBorder(1, 1, 1, 1)->setPadding(1, 1, 1, 1);
$text = $div2->addText()->setValue("First Name is your name, Lastname - is name of pedigree")->render();
var_dump(['Text', $text->getWidth(), $text->getHeight()]);
$div->display();
var_dump(['Div', $div->getWidth(), $div->getHeight()]);
//$page->display();

/*
$table = $page->addTable();
$header = $table->addRow();
$header->addCell()->setPadding(1,1,0,0)->addText()->setValue("First Name");
$header->addCell()->setPadding(1,1,0,0)->addText()->setValue("Last Name");
$page->display();
echo $header->getDocumentWidth()."\n";
echo $page->getWidth()."\n";
*/
/*
$frame = (new Frame([5, 12, 35]))->padding(2);
$frame->row(['10', 'test', 'This is test']);
$frame->row(['12345', 'estqqq', 'This is test !!!']);
$frame->display();
*/