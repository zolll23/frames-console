<?php

namespace VPA\Console\Glyphs;

use VPA\Console\FrameConfigInterface;
use VPA\DI\Injectable;

#[Injectable]
abstract class Glyph
{
    private array $children = [];
    private array $config = [];
    protected ?Glyph $parent = null;
    protected int $documentWidth = 80;
    protected int $width = 0;

    public function __construct(protected FrameConfigInterface $globalConfig)
    {
    }

    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    protected function addChild(Glyph $child): Glyph
    {
        $this->children[] = $child;
        $child->setParent($this);
        return $child;
    }

    public function display(): void
    {
    }

    protected function render()
    {

    }

    public function getDocumentWidth(): int
    {
        if (is_null($this->parent)) {
            return $this->documentWidth;
        } else {
            return $this->parent->getDocumentWidth();
        }
    }

    public function getWidth(int $endOffsetPreviousSibling = 0): int
    {
        echo "endOffsetPreviousSibling: $endOffsetPreviousSibling\n";
        if (is_null($this->children)) {
            return $this->width;
        } else {
            foreach ($this->children as $child) {
                $this->width += $child->getWidth($this->width);
            }
            return $this->width;
        }
    }

    public function setParent(Glyph $parent): void
    {
        $this->parent = $parent;
        //var_dump(get_class($this->parent));
    }

    public function addTable(array $config = []): Glyph
    {
        $table = new Table($this->globalConfig);
        $table->setConfig($config ?? $this->getConfig());
        $this->addChild($table);
        return $table;
    }

    public function addText(array $config = []): Glyph
    {
        $text = new Text($this->globalConfig);
        $text->setConfig($config ?? $this->getConfig());
        $this->addChild($text);
        return $text;
    }
}
