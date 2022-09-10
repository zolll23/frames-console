<?php

namespace VPA\Console\Glyphs;

use VPA\Console\Color;
use VPA\Console\FrameConfigInterface;
use VPA\Console\SymbolMode;

abstract class GlyphInline extends Glyph
{
    public function __construct(protected FrameConfigInterface $globalConfig)
    {
        parent::__construct($globalConfig);
        $this->setConfig([
                'textAlign' => 'left',
                'maxWidth' => 80,
                'color' => Color::WHITE,
                'backgroundColor' => Color::BLACK,
                'mode' => SymbolMode::DEFAULT,
            ]);
    }

    public function setAlign(string $align): GlyphInline
    {
        $aligns = ['left', 'center', 'right'];
        if (!in_array($align, $aligns)) {
            throw new \Exception('Incorrect align value for inline element');
        }
        $this->__set('textAlign', $align);
        return $this;
    }
}
