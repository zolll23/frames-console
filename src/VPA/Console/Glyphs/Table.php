<?php

namespace VPA\Console\Glyphs;



class Table extends GlyphBlock
{
    public function addRow(array $config = []): Row
    {
        $row = new Row($this->globalConfig);
        // Colors are inherited from the parent
        $symbolConfig = [
            'mode' => $this->__get('mode'),
            'color' => $this->__get('color'),
            'borderColor' => $this->__get('borderColor'),
            'backgroundColor' => $this->__get('backgroundColor'),
        ];
        $row->setConfig(array_merge($row->getConfig(), $symbolConfig, $config));
        $this->addChild($row);
        return $row;
    }

    public function getWidthByContent(int $endOfPreviousSibling = 0): int
    {
        $cellLengths = [];
        foreach ($this->children as $rowIndex => $row) {
            $cells = $row->getChildren();
            foreach ($cells as $cellIndex => $cell) {
                $cellLengths[$cellIndex][$rowIndex] = $cell->getWidthByContent();
            }
        }
        $this->width = $endOfPreviousSibling;
        $maxCellLengths = [];
        foreach ($cellLengths as $rowIndex => $cells) {
            $maxCellLength = !empty($cells) ? max($cells) : 0;
            $maxCellLengths[$rowIndex] = $maxCellLength;
            $this->width += $maxCellLength;
        }
        foreach ($this->children as $rowIndex => $row) {
            $row->setWidth($this->width);
            $cells = $row->getChildren();
            $offset = 0;
            foreach ($cells as $cellIndex => $cell) {
                $cell->setWidth($maxCellLengths[$cellIndex]);
                $cell->setX($offset);
                $offset += $maxCellLengths[$cellIndex];
                $nodes = $cell->getChildren();
                foreach ($nodes as $node) {
                    $node->setWidth($cell->width);
                }
            }
        }
        $this->renderedWidth = true;
        $this->appendWidth($this->width);
        return $this->width;
    }

    public function getHeightByContent(int $endOfPreviousSibling = 0): int
    {
        $countCells = [];
        $cellHeights = [];
        // Find the height of all cells
        foreach ($this->children as $rowIndex => $row) {
            $cells = $row->getChildren();
            $countCells[] = count($cells);
            foreach ($cells as $cellIndex => $cell) {
                $cellHeights[$rowIndex][$cellIndex] = $cell->getHeightByContent();
            }
        }
        if (!empty($countCells) && min($countCells) != max($countCells)) {
            throw new \RuntimeException("Number of cells in rows does not match");
        }
        $this->height = $endOfPreviousSibling;
        $maxCellHeights = [];
        // Find the max height of cell
        foreach ($cellHeights as $rowIndex => $cells) {
            $maxCellHeight = !empty($cells) ? max($cells) : 0;
            $maxCellHeights[$rowIndex] = $maxCellHeight;
        }
        $offset = 0;
        foreach ($this->children as $rowIndex => $row) {
            $maxHeight = $maxCellHeights[$rowIndex];
            $row->setHeight($maxHeight);
            $row->setY($offset);
            $this->height += $row->getHeight();
            $offset += $row->getHeight();
            $cells = $row->getChildren();
            foreach ($cells as $cellIndex => $cell) {
                $cell->setHeight($maxHeight);
            }
        }
        $this->renderedHeight = true;
        $this->appendHeight($this->height);
        return $this->height;
    }
}