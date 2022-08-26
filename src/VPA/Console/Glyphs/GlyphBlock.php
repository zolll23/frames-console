<?php

namespace VPA\Console\Glyphs;

use VPA\Console\FrameConfigInterface;
use VPA\Console\Symbol;

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
            ]
        );
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


    public function getWidthByContent(int $endOfPreviousSibling = 0): int
    {
        $this->offsetX = $this->__get('paddingLeft') + $this->__get('borderLeft');
        $this->width = parent::getWidthByContent($endOfPreviousSibling) +
            $this->__get('paddingLeft') +
            $this->__get('borderLeft') +
            $this->__get('paddingRight') +
            $this->__get('borderRight');
        //echo get_class($this) . " width = " . $this->width . ' offsetX: ' . $this->offsetX . "\n";
        return $this->width;
    }

    public function appendWidth(int $value): void
    {
        $this->offsetX = $this->__get('paddingLeft') + $this->__get('borderLeft');
        $this->width = $value +
            $this->__get('paddingLeft') +
            $this->__get('borderLeft') +
            $this->__get('paddingRight') +
            $this->__get('borderRight');
    }

    public function getHeight(): int
    {
        $this->offsetY = $this->__get('paddingTop') + $this->__get('borderTop');
        $this->height = parent::getHeight() +
            $this->__get('paddingTop') +
            $this->__get('borderTop') +
            $this->__get('paddingBottom') +
            $this->__get('borderBottom');
        //echo get_class($this) . " height = " . $this->height . ' offsetY: ' . $this->offsetX . "\n";
        return $this->height;
    }

    public function render(): Glyph
    {
        $width = $this->getWidthByContent();
        $height = $this->getHeight();
        for ($i = 0; $i < $height; $i++) {
            $this->renderMap[$i] = array_fill(0, $width, new Symbol('.'));
        }
        parent::render();
        $this->renderBorder();
        return $this;
    }

    private function renderBorder(): void
    {
        $width = $this->getWidth();
        $height = $this->getHeight();
        if ($this->__get('borderTop')) {
            for ($i = 0; $i < $width; $i++) {
                $this->renderMap[0][$i] = $this->globalConfig->lineHorizontal;
            }
        }
        if ($this->__get('borderBottom')) {
            for ($i = 0; $i < $width; $i++) {
                $this->renderMap[$height - 1][$i] = $this->globalConfig->lineHorizontal;
            }
        }
        if ($this->__get('borderLeft')) {
            for ($i = 0; $i < $height; $i++) {
                $this->renderMap[$i][0] = $this->globalConfig->lineVertical;
            }
        }
        if ($this->__get('borderRight')) {
            for ($i = 0; $i < $height; $i++) {
                $this->renderMap[$i][$width - 1] = $this->globalConfig->lineVertical;
            }
        }
    }

    protected function renderBySprites()
    {
        $width = $this->getWidth();
        $height = $this->getHeight();
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
            $y - 1 >= 0 ? $this->renderMap[$y - 1][$x]->getAlias() : '0',
            $x - 1 >= 0 ? $this->renderMap[$y][$x - 1]->getAlias() : '0',
            $this->renderMap[$y][$x]->getAlias(),
            $x + 1 < $width ? $this->renderMap[$y][$x + 1]->getAlias() : '0',
            $y + 1 < $height ? $this->renderMap[$y + 1][$x]->getAlias() : '0',
        ];
        return implode("", $codes);
    }

    private function pattern(string $codes): Symbol|bool
    {
        switch ($codes) {
            case '00|-|':
                return $this->globalConfig->cornerLeftTop;
            case '0-|0|':
                return $this->globalConfig->cornerRightTop;
            case '|-|00':
                return $this->globalConfig->cornerRightBottom;
            case '|0|-0':
                return $this->globalConfig->cornerLeftBottom;
            case '0---|':
            case '0-|-|':
                return $this->globalConfig->cornerMiddleTop;
            case '|---0':
            case '--|-0':
                return $this->globalConfig->cornerMiddleBottom;
        }
        return false;
    }
}
