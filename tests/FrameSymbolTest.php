<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use VPA\Console\FrameSymbol;
use VPA\Console\Symbol;

class FrameSymbolTest extends TestCase
{
    public function testSymbol(): void
    {
        $symbol = new FrameSymbol("\x78", "|");
        $this->assertTrue($symbol instanceof FrameSymbol);
        $this->assertTrue($symbol->getAlias()==='|');
        $this->assertTrue((string)$symbol==="\x1b(0\x78\x1b(B");
    }
}
