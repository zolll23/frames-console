<?php


namespace VPA\Console\Glyphs;

class Row extends GlyphBlock
{
    public function addCell(array $config = []): Glyph
    {
        $cell = new Cell($this->globalConfig);
        $this->addChild($cell);
        return $cell;
    }
}