<?php

use VPA\DI\Container;
use VPA\Console\FrameConsoleConfig;
use VPA\Console\Glyphs\Page;

require_once(__DIR__ . '/../vendor/autoload.php');

$di = new Container();
$di->registerContainers([
    'VPA\Console\FrameConfigInterface' => FrameConsoleConfig::class,
]);

try {
    $page = $di->get(Page::class);
    $page->setPadding(3, 3, 3, 3);
    $documentWidth = $page->getDocumentWidth();
    $table = $page->addTable()->setBorder(1, 1, 1, 1);
    $row = $table->addRow();
    $row->addCell()
        ->setPadding(1, 1, 0, 0)
        ->setBorder(0, 1, 0, 1)
        ->addText()->setValue("First column");
    $row->addCell()
        ->setPadding(1, 1, 0, 0)
        ->setBorder(0, 0, 0, 1)
        ->addText(['maxWidth' => 25])
        ->setValue("Lorem ipsum dolor sit amet,\n consectetur adipiscing elit, sed do eiusmod tempor");
    $row2 = $table->addRow();
    $row2->addCell()
        ->setPadding(1, 1, 0, 0)
        ->setBorder(0, 1, 0, 0)
        ->addText(['textAlign' => 'center'])->setValue("Center");
    $row2->addCell()
        ->setPadding(1, 1, 0, 0)
        ->setBorder(0, 0, 0, 0)
        ->addText(['textAlign' => 'right'])->setValue("Right text");
    $page->display();
} catch (\Exception $e) {
    echo $e->getMessage();
}
// Padding bottom for demo
echo str_repeat("\n", 5);
