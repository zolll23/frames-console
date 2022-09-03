<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use VPA\Console\FrameConfig;
use VPA\Console\FrameConsoleConfig;
use VPA\Console\Shell;
use VPA\Console\Symbol;

class FrameConfigTest extends TestCase
{
    public function testGetConsoleConfig(): void
    {
        $shell = $this->createMock(Shell::class);
        $shell->method('getDocumentWidthFromOS')->willReturn(256);
        $config = new FrameConsoleConfig($shell);
        $this->assertTrue($config->__get('space') instanceof Symbol);
    }

    public function testGetConfig(): void
    {
        $shell = $this->createMock(Shell::class);
        $shell->method('getDocumentWidthFromOS')->willReturn(256);
        $config = new FrameConfig($shell);
        $this->assertTrue($config->__get('space') instanceof Symbol);
    }
}
