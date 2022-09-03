<?php

namespace VPA\Console\Glyphs;

use VPA\Console\FrameConfigInterface;
use VPA\Console\FrameSymbol;
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
    protected bool $isRendered = false;
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
            'width' => 'auto',
            'maxWidth' => 20,
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
            sprintf(
                "The property %s not exists for the %s element.",
                $name,
                get_class($this)
            )
        );
    }

    public function __get(string $name): mixed
    {
        return $this->config[$name] ?? null;
    }

    public function setConfig(array $config): Glyph
    {
        $this->config = array_merge($this->config, $config);
        return $this;
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

    public function assign(): array
    {
        if ($this->isRendered) {
            return $this->renderMap;
        }
        $this->isRendered = true;
        $width = $this->getWidth();
        $height = $this->getHeight();
        for ($i = 0; $i < $height; $i++) {
            $this->renderMap[$i] = array_fill(0, $width, $this->globalConfig->__get('space'));
        }
        $this->render();
        return $this->renderMap;
    }

    public function display(): void
    {
        $this->assign();
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
        if (!$this->parent) {
            return $this;
        }
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
        if (!$this->parent) {
            return $this;
        }
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
        foreach ($this->renderMap as $y => $list) {
            echo implode("", $list) . "\n";
        }
    }

    public function getWidthByContent(int $endOfPreviousSibling = 0): int
    {
        $this->X = $endOfPreviousSibling;
        foreach ($this->children as $child) {
            $this->width = $child->getWidthByContent(0);
        }
        return $this->width;
    }

    public function getHeightByContent(int $endOfPreviousSibling = 0): int
    {
        $this->Y = $endOfPreviousSibling;
        foreach ($this->children as $child) {
            $this->height = $child->getHeightByContent();
        }
        return $this->height;
    }

    protected function gc(string $name): object
    {
        return $this->globalConfig->__get($name);
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

    public function setWidth(int $width): Glyph
    {
        $configWidth = $this->__get('width');
        if ($configWidth == 'auto') {
            $this->width = $width;
        } else {
            $this->width = intval($configWidth);
        }
        foreach ($this->getChildren() as $child) {
            $child->__set('maxWidth', $width);
        }
        $this->renderedWidth = true;
        return $this;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight(int $height): Glyph
    {
        $this->height = $height;
        $this->renderedHeight = true;
        return $this;
    }

    public function setParent(Glyph $parent): void
    {
        $this->parent = $parent;
    }

    public function getParent(): Glyph|null
    {
        return $this->parent;
    }

    private function mergeMaps(Glyph $render): void
    {
        foreach ($render->renderMap as $y => $line) {
            foreach ($line as $x => $item) {
                $coordX = $render->X + $this->offsetX + $x;
                $coordY = $render->Y + $this->offsetY + intval($y);
                $this->renderMap[$coordY][$coordX] = $item;
            }
        }
    }
}
