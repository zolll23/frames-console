<?php


namespace VPA\Console\Glyphs;


class Text extends Glyph
{
    private string $text;

    public function setValue(string $text)
    {
        $this->text = $text;
        $this->width = strlen($this->text);
    }
}