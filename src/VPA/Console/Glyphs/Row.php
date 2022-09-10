<?php


namespace VPA\Console\Glyphs;

class Row extends GlyphBlock
{
    public function addCell(array $config = []): Cell
    {
        $cell = new Cell($this->globalConfig);
        // Colors are inherited from the parent and override default values
        $symbolConfig = [
            'color' => $this->__get('color'),
            'borderColor' => $this->__get('borderColor'),
            'backgroundColor' => $this->__get('backgroundColor'),
        ];
        $cell->setConfig(array_merge($cell->getConfig(), $symbolConfig, $config));
        $this->addChild($cell);
        return $cell;
    }

    public function getWidthByContent(int $endOfPreviousSibling = 0): int
    {
        return $this->width;
    }

    public function getHeightByContent(int $endOfPreviousSibling = 0): int
    {
        return $this->height;
    }
}