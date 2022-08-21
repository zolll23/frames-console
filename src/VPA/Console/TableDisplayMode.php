<?php

namespace VPA\Console;

class TableDisplayMode
{
    /**
     * Displaying a border only around the table and columns
     */
    public const Slim = 'slim';
    /**
     * Displaying a table without a border
     */
    public const Frameless = 'frameless';
    /**
     * Displaying a border around all cells
     */
    public const Frame = 'frame';
}