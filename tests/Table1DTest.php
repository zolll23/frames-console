<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use VPA\Console\Components\Table1D;
use VPA\Console\FrameConsoleConfig;
use VPA\Console\Glyphs\Cell;
use VPA\Console\Glyphs\Div;
use VPA\Console\Glyphs\GlyphBlock;
use VPA\Console\Glyphs\Row;
use VPA\Console\Glyphs\Table;
use VPA\Console\Glyphs\Text;
use VPA\Console\Shell;
use VPA\Console\TableDisplayMode;

class Table1DTest extends TestCase
{
    private GlyphBlock $glyph;

    public function setUp(): void
    {
        parent::setUp();
        $this->shell = $this->createMock(Shell::class);
        $this->shell->method('getDocumentWidthFromOS')->willReturn(40);
        $config = new FrameConsoleConfig($this->shell);
        $this->glyph = new Table1D($config);
    }

    public function testAddHeader(): void
    {
        $this->glyph->setHeader(['first', 'second']);
        list($first, $second) = $this->glyph->getHeader();
        $this->assertTrue($first === 'first');
        $this->assertTrue($second === 'second');
        $this->glyph->setHeader('first', 'second');
        list($first, $second) = $this->glyph->getHeader();
        $this->assertTrue($first === 'first');
        $this->assertTrue($second === 'second');
    }

    public function testSetData(): void
    {
        $data = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];
        $this->glyph->setData($data);
        $tables = $this->glyph->getChildren();
        $table = reset($tables);
        $this->assertTrue(count($tables) === 1);
        $this->assertTrue($table instanceof Table);
        $rows = $table->getChildren();
        $row = reset($rows);
        $this->assertTrue(count($rows) === 2);
        $this->assertTrue($row instanceof Row);
        $cells = $row->getChildren();
        $cell = reset($cells);
        $this->assertTrue(count($cells) === 2);
        $this->assertTrue($cell instanceof Cell);
        $texts = $cell->getChildren();
        $text = reset($texts);
        $this->assertTrue(count($texts) === 1);
        $this->assertTrue($text instanceof Text);
    }

    public function testOutput(): void
    {
        ob_start();
        $data = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];
        $this->glyph->output($data);
        $display = ob_get_clean();
        $this->assertTrue(str_contains($display, "key1"));
        $this->assertTrue(str_contains($display, "value1"));
        $this->assertTrue(str_contains($display, "key2"));
        $this->assertTrue(str_contains($display, "value2"));
    }

    public function testTypeSlimWithHeader(): void
    {
        $this->glyph->setConfig([
            'type' => TableDisplayMode::Slim,
        ])->setHeader("Key", "Value");
        $data = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];
        $this->glyph->setData($data);
        $map = $this->glyph->assign();
        $this->assertTrue($map[0][0]->getAlias() === 'q');
        $this->assertTrue($map[0][7]->getAlias() === 'w');
        $this->assertTrue($map[0][16]->getAlias() === 'e');
        $this->assertTrue($map[2][0]->getAlias() === 'a');
        $this->assertTrue($map[2][7]->getAlias() === 's');
        $this->assertTrue($map[2][16]->getAlias() === 'd');
        $this->assertTrue($map[5][0]->getAlias() === 'z');
        $this->assertTrue($map[5][7]->getAlias() === 'x');
        $this->assertTrue($map[5][16]->getAlias() === 'c');
    }

    public function testTypeSlim(): void
    {
        $this->glyph->setConfig([
            'type' => TableDisplayMode::Slim,
        ]);
        $data = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];
        $this->glyph->setData($data);
        $map = $this->glyph->assign();
        $this->assertTrue($map[0][0]->getAlias() === 'q');
        $this->assertTrue($map[0][7]->getAlias() === 'w');
        $this->assertTrue($map[0][16]->getAlias() === 'e');
        $this->assertTrue($map[3][0]->getAlias() === 'z');
        $this->assertTrue($map[3][7]->getAlias() === 'x');
        $this->assertTrue($map[3][16]->getAlias() === 'c');
    }

    public function testTypeFrame(): void
    {
        $this->glyph->setConfig([
            'type' => TableDisplayMode::Frame,
        ]);
        $data = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];
        $this->glyph->setData($data);
        $map = $this->glyph->assign();
        $this->assertTrue($map[0][0]->getAlias() === 'q');
        $this->assertTrue($map[0][7]->getAlias() === 'w');
        $this->assertTrue($map[0][16]->getAlias() === 'e');
        $this->assertTrue($map[2][0]->getAlias() === 'a');
        $this->assertTrue($map[2][7]->getAlias() === 's');
        $this->assertTrue($map[2][16]->getAlias() === 'd');
        $this->assertTrue($map[4][0]->getAlias() === 'z');
        $this->assertTrue($map[4][7]->getAlias() === 'x');
        $this->assertTrue($map[4][16]->getAlias() === 'c');
    }

    public function testTypeFrameless(): void
    {
        $this->glyph->setConfig([
            'type' => TableDisplayMode::Frameless,
        ]);
        $data = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];
        $this->glyph->setData($data);
        $map = $this->glyph->assign();
//        foreach ($map[0] as $i => $s) {
//            printf("%d:%s ", $i, $s->getCode());
//        }
        $this->assertTrue($map[0][0] == ' ');
        $this->assertTrue($map[0][5] == ' ');
        $this->assertTrue($map[0][6] == ' ');
        $this->assertTrue($map[0][13] == ' ');
        $this->assertTrue($map[0][1] == 'k');
        $this->assertTrue($map[0][7] == 'v');
    }

    public function testColumnsAuto(): void
    {
        $this->glyph->setConfig([
            'columns' => 'auto',
            'type' => TableDisplayMode::Slim,
        ]);
        $data = [
            '012345' => '012345',
            '012354' => '012345',
        ];
        $this->glyph->setData($data);
        $map = $this->glyph->assign();
//        foreach ($map[0] as $i => $s) {
//            printf("%d:%s ", $i, $s->getAlias());
//        }
        $this->assertTrue($map[0][0]->getAlias() === 'q');
        $this->assertTrue($map[0][18]->getAlias() === 'w');
        $this->assertTrue($map[0][36]->getAlias() === 'e');
        $this->assertTrue($map[2][0]->getAlias() === 'z');
        $this->assertTrue($map[2][18]->getAlias() === 'x');
        $this->assertTrue($map[2][36]->getAlias() === 'c');
    }
}
