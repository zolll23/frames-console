<?php


namespace VPA\Console\Glyphs;


class Cell extends GlyphBlock
{
    public function getWidthByContent(int $endOfPreviousSibling = 0): int
    {
        if ($this->renderedWidth) {
            return $this->width;
        }
        foreach ($this->children as $child) {
            $this->width = $child->getWidthByContent(0);
        }
        parent::appendWidth($this->width);
        return $this->width;
    }

    public function getHeightByContent(int $endOfPreviousSibling = 0): int
    {
        if ($this->renderedHeight) {
            return $this->height;
        }
        foreach ($this->children as $child) {
            $this->height = $child->getHeightByContent(0);
        }
        parent::appendHeight($this->height);
        return $this->height;
    }

    public function render(): Glyph
    {
        parent::render();
        $this->renderBySprites();
        return $this;
    }
}