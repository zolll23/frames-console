<?php


namespace VPA\Console\Glyphs;

use VPA\Console\FrameConfigInterface;

class Row extends GlyphBlock
{
    public function __construct(FrameConfigInterface $globalConfig)
    {
        parent::__construct($globalConfig);
        $this->directionX = false;
        $this->widthEqualsSibling = true;
    }

    public function addCell(array $config = []): Glyph
    {
        $cell = new Cell($this->globalConfig);
        $this->addChild($cell);
        return $cell;
    }
}