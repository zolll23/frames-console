<?php

use VPA\Console\Frame;

require_once(__DIR__ . '/../vendor/autoload.php');

$frame = (new Frame([5, 12, 35]))->padding(2);
$frame->row(['10', 'test', 'This is test']);
$frame->row(['12345', 'estqqq', 'This is test !!!']);
$frame->display();