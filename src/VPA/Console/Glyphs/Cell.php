<?php


namespace VPA\Console\Glyphs;


class Cell extends GlyphBlock
{
    public function addText(array $config = []): Glyph
    {
        $text = new Text($this->globalConfig);
        $text->setConfig($config ?? $this->getConfig());
        $this->addChild($text);
        return $text;
    }
}