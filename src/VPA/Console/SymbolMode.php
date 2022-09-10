<?php
/**
 * Escape sequences:
 * The ’0’ after the first escape sequence is the default color setting for the text of
 * the shell prompt. For the text properties the following values make sense: 0, 1, 22, 4, 24, 5, 25, 7, 27
 * with the following meaning: default, bold, not bold, underlined, not underlined, blinking and not
 * blinking, inverse, not inverse.
 */


namespace VPA\Console;

class SymbolMode
{
    public const DEFAULT = 0;
    public const BOLD = 1;
    public const NOT_BOLD = 22;
    public const UNDERLINE = 4;
    public const NOT_UNDERLINE = 24;
    public const BLINKING = 5;
    public const NOT_BLINKING = 25;
    public const INVERSE = 7;
    public const NOT_INVERSE = 7;
}
