<?php


namespace VPA\Console\Glyphs;


class Cell extends GlyphBlock
{
    public function addText(array $config = []): Glyph
    {
        $text = new Text($this->globalConfig);
        $this->addChild($text);
        return $text;
    }
    public function getWidthByContent(int $endOfPreviousSibling = 0): int
    {
        foreach ($this->children as $child) {
            $this->width = $child->getWidth($this->width);
        }
        parent::appendWidth($this->width);
        return $this->width;
    }

}