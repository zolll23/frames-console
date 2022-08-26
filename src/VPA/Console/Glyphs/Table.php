<?php

namespace VPA\Console\Glyphs;


use VPA\Console\Symbol;

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
        $this->width = $endOffsetPreviousSibling;
        $maxCellLengths = [];
        foreach ($cellLengths as $rowIndex => $cells) {
            $maxCellLength = max($cells);
            $maxCellLengths[$rowIndex] = $maxCellLength;
            $this->width += $maxCellLength;
        }
        echo "LS:\n";
        var_dump($maxCellLengths);
        foreach ($this->children as $rowIndex => $row) {
            $row->setWidth($this->width);
            $cells = $row->getChildren();
            $offset = 0;
            foreach ($cells as $cellIndex => $cell) {
                $cell->setWidth($maxCellLengths[$cellIndex]);
                $cell->setX($offset);
                $offset += $maxCellLengths[$cellIndex];
            }
        }

        $this->appendWidth($this->width);
        return $this->width;
    }

    public function render(): Glyph
    {
        $width = $this->getWidthByContent();
        $height = $this->getHeight();
        var_dump(['Table', $width, $height]);
        parent::render();
        $this->renderBySprites();
        return $this;
    }
}