<?php

namespace VPA\Console\Glyphs;

use VPA\Console\FrameConfigInterface;
use VPA\Console\Symbol;
use VPA\DI\Injectable;

#[Injectable]
abstract class Glyph
{
    protected array $children = [];
    protected array $config = [];
    protected ?Glyph $parent = null;
    protected int $documentWidth = 80;
    protected int $width = 0;
    protected int $height = 0;
    protected ?bool $isFirstSibling = null;
    protected ?bool $isLastSibling = null;
    protected array $renderMap = [];
    protected int $X = 0;
    protected int $Y = 0;
    protected int $offsetX = 0;
    protected int $offsetY = 0;
    protected bool $rendered = false;


    public function __construct(protected FrameConfigInterface $globalConfig)
    {
        $this->config = [
            'overflowX' => 'none',
            'overflowY' => 'none',
            'paddingLeft' => 0,
            'paddingRight' => 0,
            'paddingTop' => 0,
            'paddingBottom' => 0,
        ];
    }

    public function __isset(string $name): bool
    {
        return array_key_exists($name, $this->config);
    }

    public function __set(string $name, mixed $value): void
    {
        if ($this->__isset($name)) {
            $this->config[$name] = $value;
            return;
        }
        throw new \Exception(
            sprintf("The property %s not exists for the %s element.", $name, get_class($this))
        );
    }

    public function __get(string $name): mixed
    {
        return $this->config[$name] ?? null;
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

    public function getChildren(): array
    {
        return $this->children;
    }

    public function display(): void
    {
        $width = $this->getWidth();
        $height = $this->getHeight();
        //var_dump([get_class($this), $width, $height]);
        for ($i = 0; $i < $height; $i++) {
            $this->renderMap[$i] = array_fill(0, $width, new Symbol('.'));
        }
        $this->render();
        $this->printMap();
    }

    public function render(): Glyph
    {
        foreach ($this->children as $child) {
            $this->mergeMaps($child->render());
        }
        return $this;
    }

    public function isFirstSibling(Glyph $child): bool
    {
        $first = reset($this->children);
        return $first !== false && $first === $child;
    }

    public function isLastSibling(Glyph $child): bool
    {
        $last = end($this->children);
        return $last !== false && $last === $child;
    }

    public function ifFirstSibling(array $config): Glyph
    {
        $first = $this->parent->isFirstSibling($this);
        if ($first) {
            foreach ($config as $property => $value) {
                $this->__set($property, $value);
            }
        }
        return $this;
    }

    public function ifLastSibling(array $config): Glyph
    {
        $last = $this->parent->isLastSibling($this);
        if ($last) {
            foreach ($config as $property => $value) {
                $this->__set($property, $value);
            }
        }
        return $this;
    }

    public function getDocumentWidth(): int
    {
        if (is_null($this->parent)) {
            return $this->documentWidth;
        } else {
            return $this->parent->getDocumentWidth();
        }
    }

    protected function printMap(): void
    {
        echo "Print " . get_class($this) . "\n";
        echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\n";
        foreach ($this->renderMap as $y => $list) {
            echo str_pad($y,3,'0',STR_PAD_LEFT).": " . implode("", $list) . "\n";
        }
        echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\n";
    }

    public function getSize(): array
    {
        if ($this->rendered) {
            return [$this->width, $this->height];
        }
        foreach ($this->children as $child) {
            $renderedChild = $child->render();
        }
        $this->rendered = true;
        if (isset($renderedChild)) {
            $this->width = $renderedChild->getWidthByContent();
            $this->height = $renderedChild->getHeight();
        }
        return [$this->width, $this->height];
    }

    public function getWidthByContent(int $endOfPreviousSibling = 0): int
    {
        $this->X = $endOfPreviousSibling;
        foreach ($this->children as $child) {
            $this->width = $child->getWidth($this->width);
        }
        return $this->width;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function setX(int $x): void
    {
        $this->X = $x;
    }

    public function setY(int $y): void
    {
        $this->Y = $y;
    }

    public function setWidth(int $width): void
    {
        $this->width = $width;
    }

    public function getHeight(): int
    {
        foreach ($this->children as $child) {
            $this->height = $child->getHeight();
        }
        return $this->height;
    }

    public function setHeight(int $height): void
    {
        $this->height = $height;
    }

    public function setParent(Glyph $parent): void
    {
        $this->parent = $parent;
    }

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

    private function mergeMaps(Glyph $render)
    {
        var_dump([
            get_class($render), $render->X, $render->Y
        ]);
        foreach ($render->renderMap as $y => $line) {
            foreach ($line as $x => $item) {
                $coordX = $render->X + $this->offsetX + $x;
                $coordY = $render->Y + $this->offsetY + $y;
                $this->renderMap[$coordY][$coordX] = $item;
            }
        }
    }
}
