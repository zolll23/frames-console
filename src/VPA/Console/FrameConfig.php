<?php


namespace VPA\Console;


class FrameConfig implements FrameConfigInterface
{
    private Symbol $space;
    private Symbol $cornerLeftTop;
    private Symbol $cornerRightTop;
    private Symbol $cornerMiddleTop;
    private Symbol $cornerLeftBottom;
    private Symbol $cornerRightBottom;
    private Symbol $cornerMiddleBottom;
    private Symbol $lineVertical;
    private Symbol $lineHorizontal;
    private Symbol $cornerLeftMiddle;
    private Symbol $cornerRightMiddle;
    private Symbol $cornerMiddleMiddle;


    public function __construct(private Shell $shell)
    {
        $this->space = new Symbol(".");
        $this->lineVertical = new Symbol("|");
        $this->lineHorizontal = new Symbol("-");
        $this->cornerLeftMiddle = new Symbol("+");
        $this->cornerRightMiddle = new Symbol("+");
        $this->cornerMiddleMiddle = new Symbol("+");

        $this->cornerLeftTop = new Symbol("+");
        $this->cornerRightTop = new Symbol("+");
        $this->cornerMiddleTop = new Symbol("+");
        $this->cornerLeftBottom = new Symbol("+");
        $this->cornerRightBottom = new Symbol("+");
        $this->cornerMiddleBottom = new Symbol("+");
    }


    public function __get(string $name): Symbol
    {
        return $this->$name ?? $this->space;
    }
}