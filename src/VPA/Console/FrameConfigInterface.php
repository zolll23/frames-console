<?php


namespace VPA\Console;


use VPA\DI\Injectable;

#[Injectable]
interface FrameConfigInterface
{
    public function __get(string $name): Symbol;
}