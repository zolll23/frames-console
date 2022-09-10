<?php

namespace VPA\Console\Components;

use VPA\Console\FrameConfigInterface;
use VPA\Console\Glyphs\Glyph;
use VPA\Console\Glyphs\GlyphBlock;
use VPA\Console\Glyphs\Row;
use VPA\Console\TableDisplayMode;

class Table1D extends GlyphBlock
{
    private string $firstColumnTitle;
    private string $secondColumnTitle;
    private bool $haveHeader = false;
    private int|string $firstWidth;
    private int|string $secondWidth;
    private int|string $firstMaxWidth;
    private int|string $secondMaxWidth;
    private array $data = [];
    private int $columns = 1;
    private const PADDING_X = 1;

    public function __construct(protected FrameConfigInterface $globalConfig)
    {
        parent::__construct($globalConfig);
        $this->config = array_merge(
            parent::getConfig(),
            [
                'type' => TableDisplayMode::Slim,
                'columns' => '1',
                'firstColumnWidth' => 'auto',
                'firstColumnMaxWidth' => 'auto',
                'secondColumnWidth' => 'auto',
                'secondColumnMaxWidth' => 'auto',
            ]
        );
        $this->documentWidth = $this->gc('shell')->getDocumentWidthFromOS();
    }

    /**
     * Add header info to Table1D.
     * Can get names of columns as array setHeader([titleFirst, titleSecond])
     * or as 2 string params:setHeader(titleFirst, titleSecond)
     * @param string|array $firstTitle
     * @param string|null $secondTitle
     * @return Glyph
     */
    public function setHeader(string|array $firstTitle, ?string $secondTitle = ''): Glyph
    {
        $this->haveHeader = true;
        if (is_array($firstTitle) && count($firstTitle) == 2) {
            $this->firstColumnTitle = reset($firstTitle);
            $this->secondColumnTitle = end($firstTitle);
            return $this;
        }
        $this->firstColumnTitle = $firstTitle ?? '';
        $this->secondColumnTitle = $secondTitle ?? '';
        return $this;
    }

    public function getHeader(): array
    {
        return [$this->firstColumnTitle, $this->secondColumnTitle];
    }

    private function getAutoColumnsNum(): int
    {
        $columns = intval($this->__get('columns'));
        $this->firstWidth = $this->__get('firstColumnWidth');
        $this->firstMaxWidth = $this->__get('firstColumnMaxWidth');
        $this->secondWidth = $this->__get('secondColumnWidth');
        $this->secondMaxWidth = $this->__get('secondColumnMaxWidth');
        $type = $this->__get('type');
        $deltaWidth = self::PADDING_X * 4;
        $deltaWidth += match ($type) {
            TableDisplayMode::Slim, TableDisplayMode::Frame => 3,
            default => 0,
        };
        if ($columns == 0) {
            $valueWidth = max(array_map(function ($it) {
                return strlen($it);
            }, $this->data));
            $keyWidth = max(array_map(function ($it) {
                return strlen($it);
            }, array_keys($this->data)));
            $keyRealWidth = min([$keyWidth, $this->firstWidth, $this->firstMaxWidth]);
            $valueRealWidth = min([$valueWidth, $this->secondWidth, $this->secondMaxWidth]);
            $width = $keyRealWidth + $valueRealWidth + $deltaWidth;
            return intval(floor($this->getDocumentWidth() / $width));
        }
        return $columns;
    }

    public function setData($data): Glyph
    {
        $this->data = $data;
        $table = $this->addTable();
        $type = $this->__get('type');
        $this->columns = $this->getAutoColumnsNum();

        $borderHead = $borderV = $borderH = 0;
        switch ($type) {
            case TableDisplayMode::Slim:
                $borderV = 1;
                $borderHead = 1;
                $table->setBorder(1, 1, 1, 1);
                break;
            case TableDisplayMode::Frame:
                $borderV = 1;
                $borderH = 1;
                $borderHead = 1;
                $table->setBorder(1, 1, 1, 0);
                break;
        }

        if ($this->haveHeader) {
            $header = $table->addRow();
            for ($i = 0; $i < $this->columns; $i++) {
                $this->addCells($header, $this->firstColumnTitle, $this->secondColumnTitle, $borderHead, $borderV, $i);
            }
        }
        $value = current($data);
        $key = key($data);
        do {
            $row = $table->addRow();
            for ($i = 0; $i < $this->columns; $i++) {
                if ($key) {
                    $this->addCells($row, $key, $value, $borderH, $borderV, $i);
                    $value = next($data);
                    $key = key($data);
                } else {
                    $this->addCells($row, '', '', $borderH, $borderV, $i);
                }
            }
        } while ($value);
        return $this;
    }

    public function output($data): Glyph
    {
        $this->setData($data);
        $this->display();
        return $this;
    }

    private function addCells(
        Row $row,
        string $first,
        string $second,
        int $borderBottom = 0,
        int $borderV = 1,
        int $column = 1
    ): array {
        $firstCell = $row->addCell()
            ->setPadding(self::PADDING_X, self::PADDING_X, 0, 0)->setBorder(0, $borderV, 0, $borderBottom);
        $this->firstWidth !== 'auto' && $firstCell = $firstCell->setWidth(intval($this->firstWidth));
        $firstText = $firstCell->addText();
        $this->firstMaxWidth != 'auto' && $firstText = $firstText->setConfig(['maxWidth' => $this->firstMaxWidth]);
        $firstText->setValue($first);
        $secondCell = $row->addCell()
            ->setPadding(self::PADDING_X, self::PADDING_X, 0, 0)
            ->setBorder(0, $this->columns - 1 == $column ? 0 : $borderV, 0, $borderBottom);
        $this->secondWidth !== 'auto' && $firstCell = $secondCell->setWidth(intval($this->secondWidth));
        $secondText = $secondCell->addText();
        $this->secondMaxWidth != 'auto' && $secondText = $secondText->setConfig(['maxWidth' => $this->secondMaxWidth]);
        $secondText->setValue($second);
        return [&$firstCell, &$secondCell];
    }
}
