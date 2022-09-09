<?php

namespace VPA\Console\Glyphs;

use VPA\Console\FrameConfigInterface;
use VPA\Console\Symbol;

abstract class GlyphBlock extends Glyph
{
    /**
     * @var mixed
     */
    private int $deltaWidth;
    private int $deltaHeight;

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
        $this->calculateDeltaHeight();
    }

    public function getContentWidth(): int
    {
        return $this->width - $this->deltaWidth;
    }

    public function getDeltaWidth(): int
    {
        return $this->deltaWidth;
    }

    public function getDeltaHeight(): int
    {
        return $this->deltaHeight;
    }


    public function getContentHeight(): int
    {
        return $this->height - $this->deltaHeight;
    }

    private function calculateDeltaWidth(): void
    {
        $this->deltaWidth = $this->__get('paddingLeft') +
            $this->__get('borderLeft') +
            $this->__get('paddingRight') +
            $this->__get('borderRight');
    }

    private function calculateDeltaHeight(): void
    {
        $this->deltaHeight = $this->__get('paddingTop') +
            $this->__get('borderTop') +
            $this->__get('paddingBottom') +
            $this->__get('borderBottom');
    }

    public function setPadding(int $paddingLeft, int $paddingRight, int $paddingTop, int $paddingBottom): GlyphBlock
    {
        $this->__set('paddingTop', $paddingTop);
        $this->__set('paddingLeft', $paddingLeft);
        $this->__set('paddingRight', $paddingRight);
        $this->__set('paddingBottom', $paddingBottom);
        $this->calculateDeltaWidth();
        $this->calculateDeltaHeight();
        return $this;
    }

    public function setBorder(int $borderLeft, int $borderRight, int $borderTop, int $borderBottom): GlyphBlock
    {
        $this->__set('borderTop', $borderTop);
        $this->__set('borderLeft', $borderLeft);
        $this->__set('borderRight', $borderRight);
        $this->__set('borderBottom', $borderBottom);
        $this->calculateDeltaWidth();
        $this->calculateDeltaHeight();
        return $this;
    }

    public function appendWidth(int $value): void
    {
        $this->offsetX = $this->__get('paddingLeft') + $this->__get('borderLeft');
        $this->contentWidth = $value;
        $this->width = $value + $this->deltaWidth;
    }

    public function appendHeight(int $value): void
    {
        $this->offsetY = $this->__get('paddingTop') + $this->__get('borderTop');
        $this->contentHeight = $value;
        $this->height = $value + $this->deltaHeight;
    }

    public function getWidthByContent(int $endOfPreviousSibling = 0): int
    {
        if ($this->renderedWidth) {
            return $this->width;
        }
        $this->offsetX = $this->__get('paddingLeft') + $this->__get('borderLeft');
        $this->setX($endOfPreviousSibling);
        $this->contentWidth = 0;
        foreach ($this->getChildren() as $child) {
            $childWidth = $child->getWidthByContent();
            $this->contentWidth = ($childWidth > $this->contentWidth) ? $childWidth : $this->contentWidth;
        }
        $this->width = $this->contentWidth + $this->getDeltaWidth();
        $this->renderedWidth = true;
        return $this->width;
    }

    public function getHeightByContent(int $endOfPreviousSibling = 0): int
    {
        if ($this->renderedHeight) {
            return $this->height;
        }
        $deltaTop = $this->__get('paddingTop') + $this->__get('borderTop');
        $this->setY($endOfPreviousSibling);
        $this->offsetY = $deltaTop;
        $this->height = 0;
        $offset = 0;
        foreach ($this->children as $child) {
            $height = $child->getHeightByContent($offset);
            $offset += $height;
            $this->contentHeight = $offset;
        }
        $this->height = $this->contentHeight + $this->getDeltaHeight();
        $this->renderedHeight = true;
        return $this->height;
    }

    public function render(): Glyph
    {
        $width = $this->getWidthByContent();
        $height = $this->getHeightByContent();
        for ($i = 0; $i < $height; $i++) {
            $this->renderMap[$i] = array_fill(0, $width, $this->gc('space'));
        }
        parent::render();
        $this->renderBorder();
        $this->renderBySprites();
        return $this;
    }

    protected function renderBorder(): void
    {
        $this->renderBorderTop();
        $this->renderBorderBottom();
        $this->renderBorderLeft();
        $this->renderBorderRight();
    }

    private function renderBorderTop(): void
    {
        if ($this->__get('borderTop')) {
            for ($i = 0; $i < $this->getWidth(); $i++) {
                $this->renderMap[0][$i] = $this->gc('lineHorizontal');
            }
        }
    }

    private function renderBorderBottom()
    {
        $height = $this->getHeight();
        if ($this->__get('borderBottom')) {
            for ($i = 0; $i < $this->getWidth(); $i++) {
                $this->renderMap[$height - 1][$i] = $this->gc('lineHorizontal');
            }
        }
    }

    private function renderBorderLeft()
    {
        $height = $this->getHeight();
        if ($this->__get('borderLeft')) {
            for ($i = 0; $i < $height; $i++) {
                if ($i == 0 && $this->renderMap[$i][0]->is($this->gc('lineHorizontal'))) {
                    $this->renderMap[$i][0] = $this->gc('cornerLeftTop');
                } elseif ($i == $height - 1 && $this->renderMap[$i][0]->is($this->gc('lineHorizontal'))) {
                    $this->renderMap[$i][0] = $this->gc('cornerLeftBottom');
                } else {
                    $this->renderMap[$i][0] = $this->gc('lineVertical');
                }
            }
        }
    }

    private function renderBorderRight(): void
    {
        $width = $this->getWidth();
        $height = $this->getHeight();
        if ($this->__get('borderRight')) {
            for ($i = 0; $i < $height; $i++) {
                if ($i == 0 && $this->renderMap[$i][$width - 1]->is($this->gc('lineHorizontal'))) {
                    $this->renderMap[$i][$width - 1] = $this->gc('cornerRightTop');
                } elseif (
                    $i == $height - 1 &&
                    $this->renderMap[$i][$width - 1]->is($this->gc('lineHorizontal'))
                ) {
                    $this->renderMap[$i][$width - 1] = $this->gc('cornerRightBottom');
                } else {
                    $this->renderMap[$i][$width - 1] = $this->gc('lineVertical');
                }
            }
        }
    }

    protected function renderBySprites(): void
    {
        $this->cachedRenderMap = $this->renderMap;
        foreach ($this->renderMap as $y => $line) {
            foreach ($line as $x => $symbol) {
                $codes = $this->getSprite($x, $y);
                $newSymbol = $this->pattern($codes);
                if ($newSymbol) {
                    $this->renderMap[$y][$x] = $newSymbol;
                }
            }
        }
    }

    private function getSprite(int $x, int $y): string
    {
        $codes = [
            $this->isFS($y - 1, $x) ? $this->cachedRenderMap[$y - 1][$x]->getAlias() : '0',
            $this->isFS($y, $x - 1) ? $this->cachedRenderMap[$y][$x - 1]->getAlias() : '0',
            $this->isFS($y, $x) ? $this->cachedRenderMap[$y][$x]->getAlias() : '0',
            $this->isFS($y, $x + 1) ? $this->cachedRenderMap[$y][$x + 1]->getAlias() : '0',
            $this->isFS($y + 1, $x) ? $this->cachedRenderMap[$y + 1][$x]->getAlias() : '0',
        ];
        return implode("", $codes);
    }

    private function isFS(int $y, int $x): bool
    {
        return isset($this->cachedRenderMap[$y][$x]) && $this->cachedRenderMap[$y][$x]->getAlias() != '';
    }

    private function pattern(string $codes): object|bool
    {
        $codesTable = [
            '00|-|' => 'cornerLeftTop',
            '00--|' => 'cornerLeftTop',
            '0-|0|' => 'cornerRightTop',
            '0--0|' => 'cornerRightTop',
            '|0|-|' => 'cornerLeftMiddle',
            '|0|-z' => 'cornerLeftMiddle',
            '|0z-|' => 'cornerLeftMiddle',
            '|-|0|' => 'cornerRightMiddle',
            '|-|0c' => 'cornerRightMiddle',
            '|-c0|' => 'cornerRightMiddle',
            '|-|00' => 'cornerRightBottom',
            '|0|-0' => 'cornerLeftBottom',
            '0---|' => 'cornerMiddleTop',
            '0-|-|' => 'cornerMiddleTop',
            '0-e-|' => 'cornerMiddleTop',
            '|---0' => 'cornerMiddleBottom',
            '--|-0' => 'cornerMiddleBottom',
            '|-c-0' => 'cornerMiddleBottom',
            '|-|-|' => 'cornerMiddleMiddle',
            '|-c-|' => 'cornerMiddleMiddle',
            '|-q-|' => 'cornerMiddleMiddle',
            '|-x-|' => 'cornerMiddleMiddle',
        ];
        if (array_key_exists($codes, $codesTable) && $codesTable[$codes]) {
            return $this->gc($codesTable[$codes]);
        }
        return false;
    }


}
