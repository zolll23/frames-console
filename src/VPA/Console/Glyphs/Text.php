<?php

namespace VPA\Console\Glyphs;

use VPA\Console\Symbol;

class Text extends GlyphInline
{
    private string $text = '';

    public function setValue(string $text): GlyphInline
    {
        $this->text = $text;
        $this->width = strlen($this->text);
        $this->height = 1;
        $this->render();
        return $this;
    }

    public function render(): GlyphInline
    {
        switch ($this->__get('textAlign')) {
            default:
                $resultString = str_pad($this->text, $this->getWidth(), ' ', STR_PAD_RIGHT);
                break;
            case 'right':
                $resultString = str_pad($this->text, $this->getWidth(), ' ', STR_PAD_LEFT);
                break;
            case 'center':
                $resultString = str_pad($this->text, $this->getWidth(), ' ', STR_PAD_BOTH);
                break;
        }
        $symbols = str_split($resultString);

        $this->renderMap = array_chunk(array_map(function ($value) {
            return new Symbol($value);
        }, $symbols), $this->__get('maxWidth'));
        $this->height = count($this->renderMap);
        $this->width = isset($this->renderMap[0]) ? count($this->renderMap[0]) : $this->__get('maxWidth');
        //$this->printMap();
        return $this;
    }
}
