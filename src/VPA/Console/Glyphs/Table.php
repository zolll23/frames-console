<?php

namespace VPA\Console\Glyphs;


class Table extends GlyphBlock
{
    public function addRow(array $config = []): Glyph
    {
        $row = new Row($this->globalConfig);
        $this->addChild($row);
        return $row;
    }

    public function getWidthByContent(int $endOffsetPreviousSibling = 0): int
    {
        $cellLengths = [];
        foreach ($this->children as $rowIndex => $row) {
            $cells = $row->getChildren();
            foreach ($cells as $cellIndex => $cell) {
                $cellLengths[$cellIndex][$rowIndex] = $cell->getWidthByContent();
            }
        }
        $this->widthByContent = $endOffsetPreviousSibling;
        $maxCellLengths = [];
        foreach ($cellLengths as $rowIndex => $cells) {
            $maxCellLength = max($cells);
            $maxCellLengths[$rowIndex] = $maxCellLength;
            $this->widthByContent += $maxCellLength;
        }

        foreach ($this->children as $rowIndex => $row) {
            $row->setWidth($this->widthByContent);
            $cells = $row->getChildren();
            foreach ($cells as $cellIndex => $cell) {
                $cell->setWidth($maxCellLengths[$cellIndex]);
            }
        }
        return $this->widthByContent;
    }

    public function render(): array
    {
        return parent::render();
    }
}