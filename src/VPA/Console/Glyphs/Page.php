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
                $response = shell_exec('mode con');
                $arr = explode("\n", $response);
                $this->documentWidth = trim(explode(':', $arr[4])[1]);
            } else {
                $this->documentWidth = exec('tput cols');
            }
        } catch (\Exception $e) {
            $this->documentWidth = 80;
        }
    }

    public function render(): Glyph
    {
        $width = $this->getWidthByContent();
        $height = $this->getHeightByContent();
        var_dump(['Page', $this->width, $this->height]);
        parent::render();
        //$this->renderBySprites();
        return $this;
    }
}
