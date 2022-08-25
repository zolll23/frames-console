<?php


namespace VPA\Console\Glyphs;


class Div extends GlyphBlock
{
    public function render(): Glyph
    {
        parent::render();
        //$this->printMap();
        $this->renderBySprites();
        return $this;
    }
}