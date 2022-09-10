<?php

use VPA\Console\Components\Table1D;
use VPA\Console\Shell;
use VPA\Console\TableDisplayMode;
use VPA\Console\FrameConsoleConfig;
use VPA\Console\Glyphs\Page;

require_once(__DIR__ . '/../vendor/autoload.php');

$shell = new Shell();
$config = new FrameConsoleConfig($shell);
$page = (new Page($config))->setPadding(3, 3, 3, 3);

try {
    $data = [
        'Company' => 'Everyone Inc',
        'Date' => '2002',
        'Description' => 'You may recognise the first few words as "lorem ipsum dolor sit amet".',
        'Code 1' => 'XU19299',
        'Code IPU' => 'UI2929I199',
        'Serial No' => '19889',
    ];
    $table1d = new Table1D($config);
    $page->addChild($table1d);
    $table1d->setConfig([
        'columns' => 2,
        'secondColumnWidth' => 30,
    ]);
    $table1d->setData($data);
    $page->display();

    $div = $page->addDiv()
        ->setBorder(1, 1, 1, 1)
        ->setPadding(2, 2, 1, 1);
    $table1d = (new Table1D($config))
        ->setConfig(['secondColumnWidth' => 30,])
        ->setHeader("Attribute", "Value");
    $div->addChild($table1d);
    $table1d->setData($data);
    $page->display();

    $table1d = new Table1D($config);
    $table1d->setConfig([
        'secondColumnWidth' => 30,
    ])->setHeader("Attribute", "Value");
    $table1d->output($data);

    $table1d = new Table1D($config);
    $table1d->setConfig([
        'columns' => 'auto',
        'secondColumnMaxWidth' => 30,
    ])->setHeader("Attribute", "Value");
    $table1d->output($data);


    $table1d = new Table1D($config);
    $table1d->setConfig([
        'type' => TableDisplayMode::Frame,
        'secondColumnWidth' => 30,
    ])->setHeader("Attribute", "Value");
    $table1d->output($data);

    $table1d = new Table1D($config);
    $table1d->setConfig([
        'type' => TableDisplayMode::Frameless,
        'secondColumnWidth' => 30,
    ])->setHeader("Attribute", "Value");
    $table1d->output($data);
} catch (\Exception $e) {
    echo $e->getMessage();
}
// Padding bottom for demo
echo str_repeat("\n", 5);
