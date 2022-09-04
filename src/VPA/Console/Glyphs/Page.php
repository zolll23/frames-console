<?php

namespace VPA\Console\Glyphs;

use VPA\Console\FrameConfigInterface;

class Page extends GlyphBlock
{
    protected int $documentWidth = 80;

    public function __construct(protected FrameConfigInterface $globalConfig)
    {
        parent::__construct($globalConfig);
        $this->documentWidth = $this->gc('shell')->getDocumentWidthFromOS();
    }

    public function getDocumentWidth(): int
    {
        return $this->documentWidth;
    }
}
