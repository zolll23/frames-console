<?php


namespace VPA\Console\Glyphs;


class GlyphBlock extends Glyph
{
    protected int $paddingLeft = 0;
    protected int $paddingRight = 0;
    protected int $paddingTop = 0;
    protected int $paddingBottom = 0;

    public function setPadding(int $paddingLeft, int $paddingRight, int $paddingTop, int $paddingBottom): GlyphBlock
    {
        $this->paddingTop = $paddingTop;
        $this->paddingLeft = $paddingLeft;
        $this->paddingRight = $paddingRight;
        $this->paddingBottom = $paddingBottom;
        return $this;
    }

    public function getWidth(int $endOffsetPreviousSibling = 0): int
    {
        $this->width = parent::getWidth($endOffsetPreviousSibling) + $this->paddingLeft + $this->paddingRight;
        return $this->width;
    }
}