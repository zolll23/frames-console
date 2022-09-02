<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use VPA\Console\FrameConfig;
use VPA\Console\Symbol;

class FrameConfigTest extends TestCase
{
    public function testGet(): void
    {
        $config = new FrameConfig();
        $this->assertTrue($config->__get('space') instanceof Symbol);
    }
}
