<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use VPA\Console\FrameConsoleConfig;
use VPA\Console\Glyphs\GlyphBlock;
use VPA\Console\Glyphs\Page;

class PageTest extends TestCase
{
    private GlyphBlock $glyph;

    public function setUp(): void
    {
        parent::setUp();
        $config = new FrameConsoleConfig();
        $this->glyph = new Page($config);
    }

    public function testSetBorder(): void
    {
        $this->glyph->setBorder(1, 1, 1, 1);
        $this->assertTrue($this->glyph->__get('borderLeft') === 1);
        $this->assertTrue($this->glyph->__get('borderRight') === 1);
        $this->assertTrue($this->glyph->__get('borderTop') === 1);
        $this->assertTrue($this->glyph->__get('borderBottom') === 1);
    }

    public function testSetPadding(): void
    {
        $this->glyph->setPadding(1, 1, 1, 1);
        $this->assertTrue($this->glyph->__get('paddingLeft') === 1);
        $this->assertTrue($this->glyph->__get('paddingRight') === 1);
        $this->assertTrue($this->glyph->__get('paddingTop') === 1);
        $this->assertTrue($this->glyph->__get('paddingBottom') === 1);
    }

    public function testGetWidthByZeroContent(): void
    {
        $width = $this->glyph->getWidthByContent();
        $documentWidth = $this->glyph->getDocumentWidth();
        $this->assertTrue($width === 0);
        $this->assertTrue($documentWidth > 0);
    }

    public function testGetHeightByZeroContent(): void
    {
        $height = $this->glyph->getHeightByContent();
        $this->assertTrue($height === 0);
    }

    public function testGetWidthByContent(): void
    {
        $this->glyph->addText()->setValue('12345');
        $width = $this->glyph->getWidthByContent();
        $this->assertTrue($width === 5);
    }

    public function testGetHeightByContent(): void
    {
        $this->glyph->addText()->setValue('12345');
        $height = $this->glyph->getHeightByContent();
        $this->assertTrue($height === 1);
    }

    public function testGetHeightByMultilineContent(): void
    {
        $this->glyph->addText(['maxWidth' => 5])->setValue('1234567890');
        $height = $this->glyph->getHeightByContent();
        $this->assertTrue($height === 2);
    }
}
