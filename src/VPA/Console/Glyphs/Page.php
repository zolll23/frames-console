<?php

namespace VPA\Console\Glyphs;

use VPA\Console\FrameConfigInterface;

class Page extends GlyphBlock
{
    protected int $documentWidth = 80;

    public function __construct(protected FrameConfigInterface $globalConfig)
    {
        parent::__construct($globalConfig);
        try {
            if (PHP_OS_FAMILY === 'Windows') {
                $response = shell_exec('mode con') ?? "";
                $arr = explode("\n", $response);
                $this->documentWidth = intval(explode(':', $arr[4])[1]);
            } else {
                $this->documentWidth = intval(exec('tput cols'));
            }
        } catch (\Exception $e) {
            $this->documentWidth = 80;
        }
    }
}
