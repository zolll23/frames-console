<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use VPA\Console\Symbol;

class SymbolTest extends TestCase
{
    public function testSymbol(): void
    {
        $code = dechex(ord('0'));
        $symbol = new Symbol("0");
        $this->assertTrue($symbol instanceof Symbol);
        $this->assertTrue((string)$symbol === "0");
        $this->assertTrue($symbol->getCode() === $code);
        $this->assertTrue($symbol->getAlias() === '');
        $this->assertTrue($symbol->equalsCode($code));
        $this->assertTrue($symbol->equals('0'));
    }
}
