<?php

namespace VPA\Console\Glyphs;

use VPA\Console\FrameConfigInterface;
use VPA\Console\Symbol;

class Text extends GlyphInline
{
    private string $text = '';

    public function setValue(string $text): GlyphInline
    {
        $this->text = $text;
        $batches = $this->splitText();
        $lengths = array_map(function ($it) {
            return strlen($it);
        }, $batches);
        $this->width = max($lengths);
        $this->height = count($batches);
        $this->render();
        return $this;
    }

    public function getValue(): string
    {
        return $this->text;
    }

    public function render(): GlyphInline
    {
        $batches = $this->splitText();
        $lengths = array_map(function ($it) {
            return strlen($it);
        }, $batches);
        $this->height = count($batches);
        $maxLength = max($lengths);
        $parent = $this->getParent();
        $contentWidth = $parent && $parent instanceof GlyphBlock ? $parent->getContentWidth() : 0;
        $this->width = $contentWidth > 0 ? $contentWidth : min($maxLength, $this->__get('maxWidth'));
        $this->__set('maxWidth', max($this->width, $this->__get('maxWidth')));
        $padding = match ($this->__get('textAlign')) {
            default => STR_PAD_RIGHT,
            'right' => STR_PAD_LEFT,
            'center' => STR_PAD_BOTH,
        };
        $resultString = '';
        foreach ($batches as $batch) {
            $resultString .= str_pad(ltrim($batch), $this->getWidth(), ' ', $padding);
        }
        $symbols = str_split($resultString) ?? [];

        $this->renderMap = $this->getWidth() ? array_chunk(array_map(function ($value) {
            return new Symbol($value);
        }, $symbols), $this->getWidth()) : [];
        $this->height = count($this->renderMap);
        return $this;
    }

    private function splitText(): array
    {
        $parent = $this->getParent();
        $deltaWidth = $parent && $parent instanceof GlyphBlock ? $parent->getDeltaWidth() : 0;
        $result = [];
        $batches = explode("\n", $this->text);
        foreach ($batches as $batch) {
            $chunks = str_split(ltrim($batch), $this->__get('maxWidth') - $deltaWidth);
            foreach ($chunks as $chunk) {
                $result[] = $chunk;
            }
        }
        return $result;
    }
}
