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
        var_dump(get_class($this->getParent()));
        $contentWidth = $this->getParent()->getContentWidth();
        echo "Text \"{$this->text}\" {$this->__get('textAlign')} {$this->width}/{$contentWidth} render\n";
        $this->width = $contentWidth;
        $this->__set('maxWidth', max($this->width, $this->__get('maxWidth')));
        $padding = match ($this->__get('textAlign')) {
            default => STR_PAD_RIGHT,
            'right' => STR_PAD_LEFT,
            'center' => STR_PAD_BOTH,
        };
        $resultString = str_pad($this->text, $this->getWidth(), ' ', $padding);
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
