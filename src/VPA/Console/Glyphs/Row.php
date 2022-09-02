<?php


namespace VPA\Console\Glyphs;

class Row extends GlyphBlock
{
    public function addCell(array $config = []): Glyph
    {
        $cell = new Cell($this->globalConfig);
        $cell->setConfig(array_merge($cell->getConfig(), $config));
        $this->addChild($cell);
        return $cell;
    }

    public function getWidthByContent(int $endOfPreviousSibling = 0): int
    {
        return $this->width;
    }

    public function getHeightByContent(int $endOfPreviousSibling = 0): int
    {
        return $this->height;
    }
}