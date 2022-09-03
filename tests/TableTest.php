<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use VPA\Console\FrameConsoleConfig;
use VPA\Console\Glyphs\Div;
use VPA\Console\Glyphs\GlyphBlock;
use VPA\Console\Glyphs\Table;
use VPA\Console\Shell;

class TableTest extends TestCase
{
    private GlyphBlock $glyph;

    public function setUp(): void
    {
        parent::setUp();
        $this->shell = $this->createMock(Shell::class);
        $this->shell->method('getDocumentWidthFromOS')->willReturn(256);
        $config = new FrameConsoleConfig($this->shell);
        $this->glyph = new Table($config);
    }

    public function testAddTable(): void
    {
        $config = new FrameConsoleConfig($this->shell);
        $div = new Div($config);
        $table = $div->addTable();
        $width = $table->getWidthByContent();
        $this->assertTrue($width === 0);
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
        $row = $this->glyph->addRow();
        $cell = $row->addCell();
        $cell->addText()->setValue('12345');
        $width = $this->glyph->getWidthByContent();
        $widthRow = $row->getWidthByContent();
        $widthCell = $cell->getWidthByContent();
        $this->assertTrue($width === 5);
        $this->assertTrue($widthRow === 5);
        $this->assertTrue($widthCell === 5);
    }

    public function testGetWidthByContentWithPadding(): void
    {
        $this->glyph->addRow()->addCell()->setPadding(1, 1, 0, 0)->addText()->setValue('12345');
        $width = $this->glyph->getWidthByContent();
        $this->assertTrue($width === 7);
    }

    public function testGetHeightByContent(): void
    {
        $row = $this->glyph->addRow();
        $cell = $row->addCell();
        $cell->addText()->setValue('12345');
        $height = $this->glyph->getHeightByContent();
        $heightRow = $row->getHeightByContent();
        $heightCell = $cell->getHeightByContent();
        $this->assertTrue($height === 1);
        $this->assertTrue($heightRow === 1);
        $this->assertTrue($heightCell === 1);
    }

    public function testGetHeightByContentWithPadding(): void
    {
        $this->glyph->addRow()->addCell()->setPadding(0, 0, 1, 1)->addText()->setValue('12345');
        $height = $this->glyph->getHeightByContent();
        $this->assertTrue($height === 3);
    }

    public function testGetHeightByMultilineContent(): void
    {
        $this->glyph->addRow()->addCell()->addText()->setConfig(['maxWidth' => 5])->setValue('1234567890');
        $height = $this->glyph->getHeightByContent();
        $this->assertTrue($height === 2);
    }

    public function testTableWithTwoCells(): void
    {
        $row = $this->glyph->addRow();
        $row->addCell()->setBorder(1, 1, 1, 1)->addText()->setValue('First');
        $row->addCell()->setBorder(0, 1, 1, 1)->addText()->setValue('Second');
        $this->glyph->assign();
        $height = $this->glyph->getHeightByContent();
        $this->assertTrue($height === 3);
    }
}
