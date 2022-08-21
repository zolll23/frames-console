<?php


namespace VPA\Console\Glyphs;


use VPA\Console\FrameConfigInterface;

abstract class GlyphBlock extends Glyph
{
    public function __construct(protected FrameConfigInterface $globalConfig)
    {
        parent::__construct($globalConfig);
        $this->config = array_merge(
            parent::getConfig(),
            [
                'paddingTop' => 0,
                'paddingLeft' => 0,
                'paddingRight' => 0,
                'paddingBottom' => 0,
                'borderTop' => 0,
                'borderLeft' => 0,
                'borderRight' => 0,
                'borderBottom' => 0,
            ]);
    }

    public function setPadding(int $paddingLeft, int $paddingRight, int $paddingTop, int $paddingBottom): GlyphBlock
    {
        $this->__set('paddingTop', $paddingTop);
        $this->__set('paddingLeft', $paddingLeft);
        $this->__set('paddingRight', $paddingRight);
        $this->__set('paddingBottom', $paddingBottom);
        return $this;
    }

    public function setBorder(int $borderLeft, int $borderRight, int $borderTop, int $borderBottom): GlyphBlock
    {
        $this->__set('borderTop', $borderTop);
        $this->__set('borderLeft', $borderLeft);
        $this->__set('borderRight', $borderRight);
        $this->__set('borderBottom', $borderBottom);
        return $this;
    }


    public function getWidthByContent(int $endOffsetPreviousSibling = 0): int
    {
        $this->offsetX = $this->__get('paddingLeft') + $this->__get('borderLeft');
        $this->widthByContent = parent::getWidthByContent($endOffsetPreviousSibling) +
            $this->__get('paddingLeft') +
            $this->__get('borderLeft') +
            $this->__get('paddingRight') +
            $this->__get('borderRight');
        echo get_class($this) . " width = " . $this->widthByContent . ' offsetX: ' . $this->offsetX . "\n";
        return $this->widthByContent;
    }

    public function getHeightByContent(int $endOffsetPreviousSibling = 0): int
    {
        $this->offsetY = $this->__get('paddingTop') + $this->__get('borderTop');
        $this->heightByContent = parent::getHeightByContent($endOffsetPreviousSibling) +
            $this->__get('paddingTop') +
            $this->__get('borderTop') +
            $this->__get('paddingBottom') +
            $this->__get('borderBottom');
        echo get_class($this) . " height = " . $this->heightByContent . ' offsetY: ' . $this->offsetX . "\n";
        echo $this->__get('borderBottom') . "\n";
        return $this->heightByContent;
    }

    public function render(): array
    {
        parent::render();
        $this->renderBorder();
        return $this->renderMap;
    }

    private function renderBorder(): void
    {
        $width = $this->getWidth();
        $height = $this->getHeight();
        if ($this->__get('borderTop')) {
            for ($i = 0, $start = true, $end = false; $i < $width; $i++, $start = $i == 0, $end = $i == $width - 1) {
                if ($start) {
                    $this->renderMap[0][$i] = $this->globalConfig->start('lineHorizontal');
                } else if ($end) {
                    $this->renderMap[0][$i] = $this->globalConfig->end('lineHorizontal');
                } else {
                    $this->renderMap[0][$i] = $this->globalConfig->lineHorizontal;
                }
            }
        }
    }
}