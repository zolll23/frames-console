<?php

namespace VPA\Console;

use VPA\DI\Injectable;

#[Injectable]
class Shell
{
    public function getDocumentWidthFromOS(): int
    {
        $os = (PHP_OS_FAMILY === 'Windows' ? 'Windows' : isset($_SERVER['TERM'])) ? 'Unix' : 'undefined';
        return match ($os) {
            'Windows' => $this->getDocumentWidthWindows(),
            'Unix' => $this->getDocumentWidthUnix(),
            default => 80
        };
    }

    /**
     * @codeCoverageIgnore
     */
    private function getDocumentWidthWindows(): int
    {
        $arr = explode("\n", shell_exec('mode con') ?? "");
        return intval(explode(':', $arr[4])[1]);
    }

    /**
     * @codeCoverageIgnore
     */
    private function getDocumentWidthUnix(): int
    {
        return intval(exec('tput cols'));
    }
}
