<?php

namespace VPA\Console\Glyphs;

use VPA\Console\FrameConfigInterface;
use VPA\DI\Injectable;

#[Injectable]
abstract class Glyph
{
    protected array $children = [];
    protected array $config = [];
    protected ?Glyph $parent = null;
    protected int $documentWidth = 80;
    protected int $widthByContent = 0;
    protected int $heightByContent = 0;
    protected ?bool $isFirstSibling = null;
    protected ?bool $isLastSibling = null;
    protected bool $directionX = true;
    protected bool $widthEqualsSibling = false;
    protected bool $heightEqualsSibling = false;
    protected array $renderMap = [];
    protected int $X = 0;
    protected int $Y = 0;
    protected int $offsetX = 0;
    protected int $offsetY = 0;


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
        $this->getWidthByContent();
        $this->getHeightByContent();
        $width = $this->getWidth();
        $height = $this->getHeight();
        for ($i = 0; $i < $height; $i++) {
           $this->renderMap[$i] = array_fill(0,$width, '_');
        }
        $this->printMap();
        $this->render();
        $this->printMap();
    }

    public function render(): array
    {
        if (!is_null($this->children)) {
            foreach ($this->children as $child) {
                $this->mergeMaps($child->render());
            }
        }
        return $this->renderMap;
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

    public function getWidthByContent(int $endOffsetPreviousSibling = 0): int
    {
        $this->X = $endOffsetPreviousSibling;
        if (is_null($this->children)) {
            return $this->widthByContent;
        } else {
            $maxWidth = 0;
            $directionX = true;
            foreach ($this->children as $child) {
                $directionX = $child->directionIsX();
                $width = $child->getWidthByContent($this->widthByContent);
                $maxWidth = $maxWidth < $width ? $width : $maxWidth;
                if ($directionX) {
                    $this->widthByContent += $width;
                }
            }
            if (!$directionX) {
                $this->widthByContent = $maxWidth;
            }
            return $this->widthByContent;
        }
    }

    protected function printMap(): void
    {
        echo "Print " . get_class($this) . "\n";
        echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\n";
        foreach ($this->renderMap as $y => $list) {
            echo "Line $y: " . implode("", $list) . "\n";
        }
        echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\n";
    }

    public function directionIsX(): bool
    {
        return $this->directionX;
    }

    public function getHeightByContent(int $endOffsetPreviousSibling = 0): int
    {
        $this->Y = $endOffsetPreviousSibling;
        if (is_null($this->children)) {
            return $this->heightByContent;
        } else {
            $maxHeight = 0;
            $directionX = false;
            foreach ($this->children as $child) {
                $directionX = $child->directionIsX();
                $height = $child->getHeightByContent($this->heightByContent);
                $maxHeight = $maxHeight < $height ? $height : $maxHeight;
                if (!$directionX) {
                    $this->heightByContent += $height;
                }
            }
            if ($directionX) {
                $this->heightByContent = $maxHeight;
            }
            return $this->heightByContent;
        }
    }

    public function getWidth(): int
    {
        return $this->widthByContent;
    }

    public function setWidth(int $width): void
    {
        $this->widthByContent = $width;
    }

    public function getHeight(): int
    {
        return $this->heightByContent;
    }

    public function setHeight(int $height): void
    {
        $this->heightByContent = $height;
    }

    public function setParent(Glyph $parent): void
    {
        $this->parent = $parent;
    }

    public function addTable(array $config = []): Glyph
    {
        $table = new Table($this->globalConfig);
        $table->setConfig(array_merge($this->getConfig(), $config));
        $this->addChild($table);
        return $table;
    }

    public function addText(array $config = []): Glyph
    {
        $text = new Text($this->globalConfig);
        $text->setConfig(array_merge($this->getConfig(), $config));
        $this->addChild($text);
        return $text;
    }

    private function mergeMaps(array $render)
    {
        foreach ($render as $y => $line) {
            foreach ($line as $x => $item) {
                $coordX = $this->X + $this->offsetX + $x;
                $coordY = $this->Y + $this->offsetY + $y;
                $this->renderMap[$coordY][$coordX] = $item;
            }
        }
    }
}
