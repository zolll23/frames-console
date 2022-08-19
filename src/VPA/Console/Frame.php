<?php

namespace VPA\Console;

class Frame
{
    private $header;
    private $footer;
    private $lines;
    private $padding = 1;
    private $corners;

    public function __construct(array $lengths, array $config = [])
    {
        $this->config = $config;
        $startLength = 0;
        foreach ($lengths as $index => $length) {
            $startLength += $length + 1 + 2 * $this->padding;
            $this->corners[$index] = $startLength;
        }
        $this->lines = [];
        $this->header();
        $this->footer();
    }

    public function padding($padding=1)
    {
        $this->padding = $padding;
        return $this;
    }

    public function display()
    {
        printf(
            "%s%s%s",
            $this->header,
            implode("", $this->lines),
            $this->footer
        );
    }

    private function format($string, $nl = true)
    {
        return sprintf("\x1b(0%s\x1b(B%s", $string, $nl ? "\n" : "");
    }

    private function header()
    {
        $corners = $this->corners;
        $lastCorner = array_pop($corners);
        $line = array_fill(0, $lastCorner, "\x71");
        $line[0] = "\x6c";
        $line[$lastCorner] = "\x6b";
        foreach ($corners as $corner) {
            $line[$corner] = "\x77";
        }
        $this->header = $this->format(implode("", $line), true);
    }

    private function footer()
    {
        $corners = $this->corners;
        $lastCorner = array_pop($corners);
        $line = array_fill(0, $lastCorner, "\x71");
        $line[0] = "\x6d";
        $line[$lastCorner] = "\x6a";
        foreach ($corners as $corner) {
            $line[$corner] = "\x76";
        }
        $this->footer = $this->format(implode("", $line), true);
    }

    public function row(array $values)
    {
        $row = $this->format("\x78", false);
        $startCorner = 0;
        foreach ($this->corners as $index => $corner) {
            $cellLength = $corner - $startCorner - 2 * $this->padding;
            $startCorner = $corner;
            if (isset($values[$index])) {
                $newValue = $values[$index];
                if (strlen($newValue) > $cellLength) {
                    $row .= $this->pad() .
                        substr($newValue, 0, $cellLength) .
                        $this->pad();
                } else {
                    $row .= $this->pad() . str_pad($newValue, $cellLength + $this->padding - 1);
                }
            } else {
                $row .= str_repeat(" ", $cellLength);
            }
            $row .= $this->format("\x78", $index == count($this->corners) - 1);
        }
        $this->lines[] = $row;
    }

    private function pad()
    {
        return str_repeat(" ", $this->padding);
    }
}
