<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use VPA\Console\Shell;

class ShellTest extends TestCase
{
    public function testGetDocumentWidthFromOS(): void
    {
        $shell = new Shell();
        $width = $shell->getDocumentWidthFromOS();
        $this->assertTrue($width > 0);
    }
}
