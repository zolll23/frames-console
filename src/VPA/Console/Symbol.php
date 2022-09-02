<?php


namespace VPA\Console;


class Symbol
{
    private string $code;

    public function __construct(protected string $string)
    {
        $this->code = dechex(ord($string));
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
        return $this->string;
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
}