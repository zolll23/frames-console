<?php

namespace VPA\Console;

class FrameSymbol extends Symbol
{
    public function __construct(protected string $string, private string $alias = '')
    {
        parent::__construct($string);
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function __toString(): string
    {
        list($start, $end) = $this->colorizeText();
        return sprintf("%s\x1b(0%s\x1b(B%s", $start, $this->string, $end);
    }
}
