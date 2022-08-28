<?php

namespace VPA\Console\Glyphs;

use VPA\Console\FrameConfigInterface;
use VPA\Console\Nodes;
use VPA\Console\Symbol;
use VPA\DI\Injectable;

#[Injectable]
abstract class Glyph
{
    use Nodes;

    protected array $children = [];
    protected array $config = [];
    protected ?Glyph $parent = null;
    protected int $documentWidth = 80;
    protected int $width = 0;
    protected int $height = 0;
    protected ?bool $isFirstSibling = null;
    protected ?bool $isLastSibling = null;
    protected array $renderMap = [];
    protected array $cachedRenderMap = [];
    protected int $X = 0;
    protected int $Y = 0;
    protected int $offsetX = 0;
    protected int $offsetY = 0;
    protected bool $renderedWidth = false;
    protected bool $renderedHeight = false;
    protected int $contentWidth = 0;
    protected int $contentHeight = 0;


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
        echo "     ";
        for ($i = 0; $i < 10; $i++) {
            echo str_pad($i, "10", ".", STR_PAD_RIGHT);
        }
        echo "\n";
        foreach ($this->renderMap as $y => $list) {
            echo str_pad($y, 3, '0', STR_PAD_LEFT) . ": " . implode("", $list) . "\n";
        }
    }

    public function getWidthByContent(int $endOfPreviousSibling = 0): int
    {
        $this->X = $endOfPreviousSibling;
        foreach ($this->children as $child) {
            $this->width = $child->getWidth($this->width);
        }
        return $this->width;
    }

    public function getHeightByContent(int $endOfPreviousSibling = 0): int
    {
        $this->Y = $endOfPreviousSibling;
        foreach ($this->children as $child) {
            $this->height = $child->getHeight($this->height);
        }
        return $this->height;
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
        $this->renderedWidth = true;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight(int $height): void
    {
        $this->height = $height;
        $this->renderedHeight = true;
    }

    public function setParent(Glyph $parent): void
    {
        $this->parent = $parent;
    }

    public function getParent(): Glyph
    {
        return $this->parent;
    }

    private function mergeMaps(Glyph $render)
    {
        //echo implode(",\t", [basename(__FILE__) . ":" . __LINE__,get_class($render) . "\t", $render->Y, $render->X, $render->width, $render->height]) . "\n";
        foreach ($render->renderMap as $y => $line) {
            foreach ($line as $x => $item) {
                $coordX = $render->X + $this->offsetX + $x;
                $coordY = $render->Y + $this->offsetY + $y;
                $this->renderMap[$coordY][$coordX] = $item;
            }
        }
    }
}
