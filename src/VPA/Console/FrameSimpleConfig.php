<?php


namespace VPA\Console;


use VPA\DI\Injectable;

#[Injectable]
class FrameSimpleConfig implements FrameConfigInterface
{
    private string $cornerLeftTop;
    private string $cornerRightTop;
    private string $cornerMiddleTop;
    private string $cornerLeftBottom;
    private string $cornerRightBottom;
    private string $cornerMiddleBottom;
    private string $lineVertical;
    private string $lineHorizontal;
    private string $cornerLeftMiddle;
    private string $cornerRightMiddle;
    private string $cornerMiddleMiddle;

    public function __construct()
    {
        $this->lineVertical = "|";
        $this->lineHorizontal = "-";
        $this->cornerLeftMiddle = "+";
        $this->cornerRightMiddle = "+";
        $this->cornerMiddleMiddle = "+";

        $this->cornerLeftTop = "+";
        $this->cornerRightTop = "+";
        $this->cornerMiddleTop = "+";
        $this->cornerLeftBottom = "+";
        $this->cornerRightBottom = "+";
        $this->cornerMiddleBottom = "+";
    }

    public function __get(string $name): string
    {
        if ($this->$name) {
            return $this->$name;
        }
    }

    public function start(string $symbol)
    {
        return $this->__get($symbol);
    }
    public function end(string $symbol)
    {
        return $this->__get($symbol);
    }
}