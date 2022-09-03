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

    public function getDocumentWidth(): int
    {
        return $this->documentWidth;
    }

    public function getWidthByContent(int $endOfPreviousSibling = 0): int
    {
        $this->width = $this->documentWidth;
        return $this->width;
    }

    public function getHeightByContent(int $endOfPreviousSibling = 0): int
    {
        $this->Y = $endOfPreviousSibling;
        $this->height = 0;
        $offset = 0;
        foreach ($this->children as $child) {
            $height = $child->getHeightByContent($offset);
            $offset += $height;
            $this->height += $height;
        }
        return $this->height;
    }
}
