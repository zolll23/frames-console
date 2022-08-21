<?php

namespace VPA\Console\Glyphs;


class Table extends GlyphBlock
{
    public function addRow(array $config = []): Glyph
    {
        $row = new Row($this->globalConfig);
        $row->setConfig($config ?? $this->getConfig());
        $this->addChild($row);
        return $row;
    }
}