<?php
/**
 * Tests for Block Elements
 */
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use VPA\Console\FrameConsoleConfig;
use VPA\Console\Glyphs\Div;
use VPA\Console\Glyphs\GlyphBlock;
use VPA\Console\Shell;

class DivTest extends TestCase
{
    private GlyphBlock $glyph;
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|Shell
     */
    private \PHPUnit\Framework\MockObject\MockObject|Shell $shell;

    public function setUp(): void
    {
        parent::setUp();
        $this->shell = $this->createMock(Shell::class);
        $this->shell->method('getDocumentWidthFromOS')->willReturn(256);
        $config = new FrameConsoleConfig($this->shell);
        $this->glyph = new Div($config);
    }

    public function testSetBorder(): void
    {
        $this->glyph->setBorder(1, 1, 1, 1);
        $this->assertTrue($this->glyph->__get('borderLeft') === 1);
        $this->assertTrue($this->glyph->__get('borderRight') === 1);
        $this->assertTrue($this->glyph->__get('borderTop') === 1);
        $this->assertTrue($this->glyph->__get('borderBottom') === 1);
        $deltaHeight = $this->glyph->getDeltaHeight();
        $deltaWidth = $this->glyph->getDeltaWidth();
        ob_start();
        $ret = $this->glyph->assign();
        ob_end_clean();
        $this->assertTrue($ret[0][0]->equalsCode('6c'));
        $this->assertTrue($ret[0][1]->equalsCode('6b'));
        $this->assertTrue($ret[1][0]->equalsCode('6d'));
        $this->assertTrue($ret[1][1]->equalsCode('6a'));
        $this->assertTrue($deltaHeight === 2);
        $this->assertTrue($deltaWidth === 2);
    }

    public function testSetBorderNotEmptyDiv(): void
    {
        $this->glyph->setBorder(1, 1, 1, 1)->addText()->setValue('1');
        $this->assertTrue($this->glyph->__get('borderLeft') === 1);
        $this->assertTrue($this->glyph->__get('borderRight') === 1);
        $this->assertTrue($this->glyph->__get('borderTop') === 1);
        $this->assertTrue($this->glyph->__get('borderBottom') === 1);
        ob_start();
        $ret = $this->glyph->assign();
        ob_end_clean();
        $this->assertTrue($ret[0][0]->equalsCode('6c'));
        $this->assertTrue($ret[0][2]->equalsCode('6b'));
        $this->assertTrue($ret[2][0]->equalsCode('6d'));
        $this->assertTrue($ret[2][2]->equalsCode('6a'));
    }

    public function testSetPadding(): void
    {
        $this->glyph->setPadding(1, 1, 1, 1);
        $this->assertTrue($this->glyph->__get('paddingLeft') === 1);
        $this->assertTrue($this->glyph->__get('paddingRight') === 1);
        $this->assertTrue($this->glyph->__get('paddingTop') === 1);
        $this->assertTrue($this->glyph->__get('paddingBottom') === 1);
    }

    public function testGetConfig(): void
    {
        $config = $this->glyph->getConfig();
        $this->assertTrue(is_array($config) && isset($config['paddingLeft']));
    }

    public function testGetWidthByZeroContent(): void
    {
        $width = $this->glyph->getWidthByContent();
        $this->assertTrue($width === 0);
    }

    public function testIssetConfigName(): void
    {
        $this->assertTrue($this->glyph->__isset('paddingLeft'));
    }

    public function testGetHeightByZeroContent(): void
    {
        $height = $this->glyph->getHeightByContent();
        $this->assertTrue($height === 0);
    }

    public function testGetWidthByContent(): void
    {
        $this->glyph->addText()->setValue('12345');
        $width = $this->glyph->getWidthByContent();
        $this->assertTrue($width === 5);
    }

    public function testAddDiv(): void
    {
        $div = $this->glyph->addDiv();
        $div->addText()->setValue('12345');
        $width = $this->glyph->getWidthByContent();
        $this->assertTrue($width === 5);
        $parent = $div->getParent();
        $this->assertTrue($parent === $this->glyph);
        $children = $this->glyph->getChildren();
        $this->assertTrue(count($children) === 1);
        $this->glyph->render();
        $height = $this->glyph->getHeightByContent();
        $contentHeight = $div->getContentHeight();
        $this->assertTrue($contentHeight === 1);
        $this->assertTrue($height === 1);
    }

    public function testGetHeightByContent(): void
    {
        $this->glyph->addText()->setValue('12345');
        $height = $this->glyph->getHeightByContent();
        $this->assertTrue($height === 1);
    }

    public function testSetWidth(): void
    {
        $this->glyph->setWidth(15);
        $width = $this->glyph->getWidth();
        $this->assertTrue($width === 15);
    }

    public function testSetWidthAuto(): void
    {
        $this->glyph->__set('width', '20');
        $this->glyph->setWidth(15);
        $width = $this->glyph->getWidth();
        $this->assertTrue($width === 20);
    }

    public function testGetHeightByMultilineContent(): void
    {
        $this->glyph->addText(['maxWidth' => 5])->setValue('1234567890');
        $height = $this->glyph->getHeightByContent();
        $this->assertTrue($height === 2);
    }

    public function testCheckInvalidConfigAttribute(): void
    {
        try {
            $this->glyph->setConfig(['incorrect_name' => 'any']);
            $this->assertTrue(false);
        } catch (\Exception $e) {
            $this->assertTrue(true);
            return;
        }
    }

    public function testSetInvalidConfigAttribute(): void
    {
        try {
            $this->glyph->__set('incorrect_name', 'any');
            $this->assertTrue(false);
        } catch (\Exception $e) {
            $this->assertTrue(true);
            return;
        }
    }

    public function testDisplay(): void
    {
        $config = new FrameConsoleConfig($this->shell);
        $root = new Div($config);
        $root->addDiv()->addText()->setValue('12345');
        ob_start();
        $root->display();
        $string = ob_get_clean();
        $this->assertTrue($string === "12345\n");
    }

    public function testDoubleDisplayRender(): void
    {
        $config = new FrameConsoleConfig($this->shell);
        $root = new Div($config);
        $root->addDiv()->addText()->setValue('12345');
        $root->assign();
        ob_start();
        $root->display();
        $string = ob_get_clean();
        $this->assertTrue($string === "12345\n");
    }


    public function testisFirstLastSibling(): void
    {
        $config = new FrameConsoleConfig($this->shell);
        $root = new Div($config);
        $first = $root->addDiv();
        $middle = $root->addDiv();
        $last = $root->addDiv();
        $this->assertTrue($root->isFirstSibling($first));
        $this->assertTrue(!$root->isFirstSibling($middle));
        $this->assertTrue(!$root->isFirstSibling($last));
        $this->assertTrue(!$root->isLastSibling($first));
        $this->assertTrue(!$root->isLastSibling($middle));
        $this->assertTrue($root->isLastSibling($last));

        $first->ifFirstSibling(['paddingLeft' => 2]);
        $this->assertTrue($first->__get('paddingLeft') === 2);
        $last->ifLastSibling(['paddingLeft' => 2]);
        $this->assertTrue($last->__get('paddingLeft') === 2);
    }

    public function testisFirstLastSiblingWithNoParent(): void
    {
        $config = new FrameConsoleConfig($this->shell);
        $root = new Div($config);
        $root->ifFirstSibling(['paddingLeft' => 2]);
        $this->assertTrue($root->__get('paddingLeft') === 0);
        $root->ifLastSibling(['paddingLeft' => 2]);
        $this->assertTrue($root->__get('paddingLeft') === 0);
    }
}
