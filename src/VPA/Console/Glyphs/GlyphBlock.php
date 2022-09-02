<?php

namespace VPA\Console\Glyphs;

use VPA\Console\FrameConfigInterface;
use VPA\Console\Symbol;

abstract class GlyphBlock extends Glyph
{
    /**
     * @var mixed
     */
    private int $delta;

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
            ]
        );
        $this->calculateDeltaWidth();
    }

    public function getContentWidth(): int
    {
        return $this->width - $this->delta;
    }

    public function getContentHeight(): int
    {
        return $this->contentHeight;
    }

    private function calculateDeltaWidth(): void
    {
        $this->delta = $this->__get('paddingLeft') +
            $this->__get('borderLeft') +
            $this->__get('paddingRight') +
            $this->__get('borderRight');
    }

    public function setPadding(int $paddingLeft, int $paddingRight, int $paddingTop, int $paddingBottom): GlyphBlock
    {
        $this->__set('paddingTop', $paddingTop);
        $this->__set('paddingLeft', $paddingLeft);
        $this->__set('paddingRight', $paddingRight);
        $this->__set('paddingBottom', $paddingBottom);
        $this->calculateDeltaWidth();
        return $this;
    }

    public function setBorder(int $borderLeft, int $borderRight, int $borderTop, int $borderBottom): GlyphBlock
    {
        $this->__set('borderTop', $borderTop);
        $this->__set('borderLeft', $borderLeft);
        $this->__set('borderRight', $borderRight);
        $this->__set('borderBottom', $borderBottom);
        $this->calculateDeltaWidth();
        return $this;
    }


    public function getWidthByContent(int $endOfPreviousSibling = 0): int
    {
        $this->offsetX = $this->__get('paddingLeft') + $this->__get('borderLeft');
        $this->contentWidth = parent::getWidthByContent($endOfPreviousSibling);
        $this->width = $this->contentWidth + $this->delta;
        return $this->width;
    }

    public function appendWidth(int $value): void
    {
        $this->offsetX = $this->__get('paddingLeft') + $this->__get('borderLeft');
        $this->contentWidth = $value;

        $this->width = $value + $this->delta;
    }

    public function appendHeight(int $value): void
    {
        $this->offsetY = $this->__get('paddingTop') + $this->__get('borderTop');
        $this->contentHeight = $value;
        $this->height = $value +
            $this->__get('paddingTop') +
            $this->__get('borderTop') +
            $this->__get('paddingBottom') +
            $this->__get('borderBottom');
    }


    public function getHeightByContent(int $endOfPreviousSibling = 0): int
    {
        $this->offsetY = $this->__get('paddingTop') + $this->__get('borderTop');
        $this->contentHeight = parent::getHeightByContent($endOfPreviousSibling);
        $this->height = $this->contentHeight +
            $this->__get('paddingTop') +
            $this->__get('borderTop') +
            $this->__get('paddingBottom') +
            $this->__get('borderBottom');
        return $this->height;
    }

    public function render(): Glyph
    {
        $width = $this->getWidthByContent();
        $height = $this->getHeightByContent();
        for ($i = 0; $i < $height; $i++) {
            $this->renderMap[$i] = array_fill(0, $width, $this->globalConfig->__get('space'));
        }
        parent::render();
        $this->renderBorder();
        $this->renderBySprites();
        return $this;
    }

    protected function renderBorder(): void
    {
        $width = $this->getWidth();
        $height = $this->getHeight();
        if ($this->__get('borderTop')) {
            for ($i = 0; $i < $width; $i++) {
                $this->renderMap[0][$i] = $this->globalConfig->__get('lineHorizontal');
            }
        }
        if ($this->__get('borderBottom')) {
            for ($i = 0; $i < $width; $i++) {
                $this->renderMap[$height - 1][$i] = $this->globalConfig->__get('lineHorizontal');
            }
        }
        if ($this->__get('borderLeft')) {
            for ($i = 0; $i < $height; $i++) {
                $this->renderMap[$i][0] = $this->globalConfig->__get('lineVertical');
            }
        }
        if ($this->__get('borderRight')) {
            for ($i = 0; $i < $height; $i++) {
                $this->renderMap[$i][$width - 1] = $this->globalConfig->__get('lineVertical');
            }
        }
    }

    protected function renderBySprites(): void
    {
        $width = $this->getWidth();
        $height = $this->getHeight();
        $this->cachedRenderMap = $this->renderMap;
        foreach ($this->renderMap as $y => $line) {
            foreach ($line as $x => $symbol) {
                $codes = $this->getSprite($x, $y, $width, $height);
                $newSymbol = $this->pattern($codes);
                if ($newSymbol) {
                    $this->renderMap[$y][$x] = $newSymbol;
                }
                //echo $codes . "\t";
            }
        }
    }

    private function getSprite(int $x, int $y, int $width, int $height): string
    {
        $codes = [
            isset($this->cachedRenderMap[$y - 1][$x]) ? $this->cachedRenderMap[$y - 1][$x]->getAlias() : '0',
            isset($this->cachedRenderMap[$y][$x - 1]) ? $this->cachedRenderMap[$y][$x - 1]->getAlias() : '0',
            isset($this->cachedRenderMap[$y][$x])     ? $this->cachedRenderMap[$y][$x]->getAlias() : '0',
            isset($this->cachedRenderMap[$y][$x + 1]) ? $this->cachedRenderMap[$y][$x + 1]->getAlias() : '0',
            isset($this->cachedRenderMap[$y + 1][$x]) ? $this->cachedRenderMap[$y + 1][$x]->getAlias() : '0',
        ];
        return implode("", $codes);
    }

    private function pattern(string $codes): Symbol|bool
    {
        switch ($codes) {
            case '00|-|':
            case '00--|':
                return $this->globalConfig->__get('cornerLeftTop');
            case '0-|0|':
            case '0--0|':
                return $this->globalConfig->__get('cornerRightTop');
            case '|0|-|':
                return $this->globalConfig->__get('cornerLeftMiddle');
            case '|-|0|':
                return $this->globalConfig->__get('cornerRightMiddle');
            case '|-|00':
                return $this->globalConfig->__get('cornerRightBottom');
            case '|0|-0':
                return $this->globalConfig->__get('cornerLeftBottom');
            case '0---|':
            case '0-|-|':
                return $this->globalConfig->__get('cornerMiddleTop');
            case '|---0':
            case '--|-0':
                return $this->globalConfig->__get('cornerMiddleBottom');
            case '|-|-|':
            case '|-c-|':
            case '|-q-|':
                return $this->globalConfig->__get('cornerMiddleMiddle');
        }
        return false;
    }
}
