<?php

namespace VPA\Console;

use VPA\Console\Glyphs\Div;
use VPA\Console\Glyphs\Table;
use VPA\Console\Glyphs\Text;

trait Nodes
{
    abstract public function __get(string $name);

    public function addTable(array $config = []): Table
    {
        $table = new Table($this->globalConfig);
        $table->setConfig(array_merge($table->getConfig(), $config));
        $this->addChild($table);
        return $table;
    }

    public function addDiv(array $config = []): Div
    {
        $div = new Div($this->globalConfig);
        $div->setConfig(array_merge($div->getConfig(), $config));
        $this->addChild($div);
        return $div;
    }

    public function addText(array $config = []): Text
    {
        $text = new Text($this->globalConfig);
        // Colors are inherited from the parent and override default values
        $symbolConfig = [
            'mode' => $this->__get('mode'),
            'color' => $this->__get('color'),
            'borderColor' => $this->__get('borderColor'),
            'backgroundColor' => $this->__get('backgroundColor'),
        ];
        $text->setConfig(array_merge($text->getConfig(), $symbolConfig, $config));
        $this->addChild($text);
        return $text;
    }
}
