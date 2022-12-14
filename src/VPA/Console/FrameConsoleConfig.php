<?php

/**
 *  Symbols for frames take from article https://en.wikipedia.org/wiki/Box-drawing_character [Unix, CP/M, BBS]
 */

namespace VPA\Console;

use VPA\DI\Injectable;

#[Injectable]
class FrameConsoleConfig implements FrameConfigInterface
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
    private Symbol $space;

    public function __construct(private Shell $shell)
    {
        $this->space = new Symbol(" ");
        $this->lineVertical = new FrameSymbol("\x78", "|");
        $this->lineHorizontal = new FrameSymbol("\x71", "-");
        $this->cornerLeftMiddle = new FrameSymbol("\x74", "a");
        $this->cornerRightMiddle = new FrameSymbol("\x75", "d");
        $this->cornerMiddleMiddle = new FrameSymbol("\x6e", "s");

        $this->cornerLeftTop = new FrameSymbol("\x6c", "q");
        $this->cornerRightTop = new FrameSymbol("\x6b", "e");
        $this->cornerMiddleTop = new FrameSymbol("\x77", "w");
        $this->cornerLeftBottom = new FrameSymbol("\x6d", "z");
        $this->cornerRightBottom = new FrameSymbol("\x6a", "c");
        $this->cornerMiddleBottom = new FrameSymbol("\x76", "x");
    }

    public function __get(string $name): object
    {
            return $this->$name ?? $this->space;
    }
}
