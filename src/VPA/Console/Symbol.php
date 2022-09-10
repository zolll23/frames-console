<?php

namespace VPA\Console;

class Symbol
{
    private string $code;
    private int $mode = SymbolMode::DEFAULT;
    private int $color = Color::WHITE;
    private int $backgroundColor = Color::BLACK;

    public function __construct(protected string $string)
    {
        $this->code = dechex(ord($string));
    }

    public function setMode(int $mode = SymbolMode::DEFAULT): void
    {
        $this->mode = $mode;
    }

    public function setColor(int $color = Color::WHITE): void
    {
        $this->color = $color;
    }

    public function setBackgroundColor(int $color = Color::BLACK): void
    {
        $this->backgroundColor = $color;
    }

    public function setConfig(array $config): Symbol
    {
        $this->setMode($config['mode'] ?? $this->mode);
        $this->setColor($config['color'] ?? $this->color);
        $this->setBackgroundColor($config['backgroundColor'] ?? $this->backgroundColor);
        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getAlias(): string
    {
        return '';
    }

    public function __toString(): string
    {
        list($start, $end) = $this->colorizeText();
        return sprintf("%s%s%s", $start, $this->string, $end);
    }

    public function equalsCode(string $hexCode): bool
    {
        return $this->code === $hexCode;
    }

    public function equals(string $symbol): bool
    {
        return $this->string === $symbol;
    }

    public function is(Symbol $symbol): bool
    {
        return $this->code === $symbol->getCode();
    }

    protected function colorizeText(): array
    {
        $start = $end = "";
        if (
            $this->mode != SymbolMode::DEFAULT
            || $this->color != Color::WHITE
            || $this->backgroundColor != Color::BLACK
        ) {
            $start = sprintf("\033[%s", $this->mode);
            $end = "\033[0m";
        }
        if ($this->backgroundColor != Color::BLACK) {
            $start .= sprintf(";3%s;4%sm", $this->color, $this->backgroundColor);
        } elseif ($start) {
            $start .= sprintf(";3%sm", $this->color);
        }
        return [$start, $end];
    }
}
