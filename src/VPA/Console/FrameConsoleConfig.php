<?php


namespace VPA\Console;


use VPA\DI\Injectable;

#[Injectable]
class FrameConsoleConfig implements FrameConfigInterface
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
        $this->lineVertical = "\x71";
        $this->lineHorizontal = "\x78";
        $this->cornerLeftMiddle = "\x74";
        $this->cornerRightMiddle = "\x75";
        $this->cornerMiddleMiddle = "\x6e";

        $this->cornerLeftTop = "\x6c";
        $this->cornerRightTop = "\x6b";
        $this->cornerMiddleTop = "\x77";
        $this->cornerLeftBottom = "\x6d";
        $this->cornerRightBottom = "\x6b";
        $this->cornerMiddleBottom = "\x76";
    }
}