<?php


namespace VPA\Console;


use VPA\Console\FrameSymbol;
use VPA\DI\Injectable;

#[Injectable]
class FrameSimpleConfig implements FrameConfigInterface
{
    private FrameSymbol $cornerLeftTop;
    private FrameSymbol $cornerRightTop;
    private FrameSymbol $cornerMiddleTop;
    private FrameSymbol $cornerLeftBottom;
    private FrameSymbol $cornerRightBottom;
    private FrameSymbol $cornerMiddleBottom;
    private FrameSymbol $lineVertical;
    private FrameSymbol $lineHorizontal;
    private FrameSymbol $cornerLeftMiddle;
    private FrameSymbol $cornerRightMiddle;
    private FrameSymbol $cornerMiddleMiddle;

    public function __construct()
    {
        $this->lineVertical = new FrameSymbol("|");
        $this->lineHorizontal = new FrameSymbol("-");
        $this->cornerLeftMiddle = new FrameSymbol("+");
        $this->cornerRightMiddle = new FrameSymbol("+");
        $this->cornerMiddleMiddle = new FrameSymbol("+");

        $this->cornerLeftTop = new FrameSymbol("+");
        $this->cornerRightTop = new FrameSymbol("+");
        $this->cornerMiddleTop = new FrameSymbol("+");
        $this->cornerLeftBottom = new FrameSymbol("+");
        $this->cornerRightBottom = new FrameSymbol("+");
        $this->cornerMiddleBottom = new FrameSymbol("+");
    }

    public function __get(string $name): FrameSymbol
    {
        if ($this->$name) {
            return $this->$name;
        }
    }
}