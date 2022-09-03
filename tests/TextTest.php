<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use VPA\Console\FrameConsoleConfig;
use VPA\Console\Glyphs\Div;
use VPA\Console\Glyphs\GlyphInline;
use VPA\Console\Glyphs\Text;
use VPA\Console\Shell;

class TextTest extends TestCase
{
    private GlyphInline $glyph;
    private \PHPUnit\Framework\MockObject\MockObject|Shell $shell;

    public function setUp(): void
    {
        parent::setUp();
        // Mock for Shell
        $shell = $this->createMock(Shell::class);
        $shell->method('getDocumentWidthFromOS')->willReturn(256);
        $this->shell = $shell;
        $config = new FrameConsoleConfig($shell);
        $this->glyph = new Text($config);
    }

    public function testGetWidthByZeroContent(): void
    {
        $width = $this->glyph->getWidthByContent();
        $this->assertTrue($width === 0);
    }

    public function testGetHeightByZeroContent(): void
    {
        $height = $this->glyph->getHeightByContent();
        $this->assertTrue($height === 0);
    }

    public function testGetWidthByContent(): void
    {
        $this->glyph->setValue('12345');
        $width = $this->glyph->getWidthByContent();
        $this->assertTrue($width === 5);
    }

    public function testGetHeightByContent(): void
    {
        $this->glyph->setValue('12345');
        $height = $this->glyph->getHeightByContent();
        $this->assertTrue($height === 1);
    }

    public function testGetHeightByMultilineContent(): void
    {
        $this->glyph->setConfig(['maxWidth' => 5])->setValue('1234567890');
        $height = $this->glyph->getHeightByContent();
        $this->assertTrue($height === 2);
    }

    public function testCheckInvalidConfigAttribute(): void
    {
        try {
            $this->glyph->setConfig(['incorrect_name' => 'eny']);
            $this->assertTrue(false);
        } catch (\Exception $e) {
            $this->assertTrue(true);
            return;
        }
    }

    public function testCheckInvalidAlign(): void
    {
        try {
            $this->glyph->setValue('12345')->setAlign('not_exist');
            $this->assertTrue(false);
        } catch (\Exception $e) {
            $this->assertTrue(true);
            return;
        }
    }

    public function testSetAlignLeft(): void
    {
        $this->glyph->setAlign('left');
        $this->glyph->setConfig(['maxWidth' => 20])->setValue('1234567890');
        $data = $this->glyph->assign();
        $this->assertTrue($data[0][0]->equals('1'));
    }

    public function testSetAlignRight(): void
    {
        $config = new FrameConsoleConfig($this->shell);
        $div = new Div($config);
        $div->setWidth(20);
        $div->addText()->setAlign('right')->setValue('1234567890');
        $data = $div->assign();
        $this->assertTrue($data[0][19]->equals('0'));
    }

    public function testSetAlignCenter(): void
    {
        $config = new FrameConsoleConfig($this->shell);
        $div = new Div($config);
        $div->setWidth(5);
        $div->addText()->setAlign('center')->setValue('123');
        $data = $div->assign();
        $this->assertTrue($data[0][2]->equals('2'));
    }
}
