<?php


namespace VPA\Console\Glyphs;


use VPA\Console\FrameConfigInterface;

abstract class GlyphInline extends Glyph
{
    public function __construct(protected FrameConfigInterface $globalConfig)
    {
        parent::__construct($globalConfig);
        $this->config = array_merge(
            parent::getConfig(),
            [
                'textAlign' => 'left',
            ]);
    }

    public function setAlign(string $align): GlyphInline
    {
        $this->__set('textAlign', $align);
        return $this;
    }

    public function render(): array
    {
        return parent::render();
    }
}