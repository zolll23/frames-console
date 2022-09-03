<?php

namespace VPA\Console\Glyphs;

use VPA\Console\FrameConfigInterface;

class Page extends GlyphBlock
{
    protected int $documentWidth = 80;

    public function __construct(protected FrameConfigInterface $globalConfig)
    {
        parent::__construct($globalConfig);
        $this->documentWidth = (PHP_OS_FAMILY === 'Windows') ? $this->getDocumentWidthWindows() :
            $this->getDocumentWidthUnix();
    }

    /**
     * @codeCoverageIgnore
     */
    private function getDocumentWidthWindows(): int
    {
        $arr = explode("\n", shell_exec('mode con') ?? "");
        return intval(explode(':', $arr[4])[1]);
    }

    /**
     * @codeCoverageIgnore
     */
    private function getDocumentWidthUnix(): int
    {
        return intval(exec('tput cols'));
    }
}
