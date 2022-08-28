<?php


namespace VPA\Console;


use VPA\Console\Glyphs\Div;
use VPA\Console\Glyphs\Glyph;
use VPA\Console\Glyphs\Table;
use VPA\Console\Glyphs\Text;

trait Nodes
{
    public function addTable(array $config = []): Glyph
    {
        $table = new Table($this->globalConfig);
        $table->setConfig(array_merge($table->getConfig(), $config));
        $this->addChild($table);
        return $table;
    }

    public function addDiv(array $config = []): Glyph
    {
        $div = new Div($this->globalConfig);
        $div->setConfig(array_merge($div->getConfig(), $config));
        $this->addChild($div);
        return $div;
    }

    public function addText(array $config = []): Glyph
    {
        $text = new Text($this->globalConfig);
        $text->setConfig(array_merge($text->getConfig(), $config));
        $this->addChild($text);
        return $text;
    }
}