<?php

namespace VPA\Console\Glyphs;

use VPA\Console\FrameConfigInterface;
use VPA\Console\Shell;

class Page extends GlyphBlock
{
    protected int $documentWidth = 80;

    public function __construct(protected FrameConfigInterface $globalConfig)
    {
        parent::__construct($globalConfig);
        $this->documentWidth = $this->gc('shell')->getDocumentWidthFromOS();
    }
}
