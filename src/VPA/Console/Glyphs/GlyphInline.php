<?php

namespace VPA\Console\Glyphs;

use VPA\Console\FrameConfigInterface;

abstract class GlyphInline extends Glyph
{
    public function __construct(protected FrameConfigInterface $globalConfig)
    {
        parent::__construct($globalConfig);
        $this->setConfig([
                'textAlign' => 'left',
                'maxWidth' => '20',
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
