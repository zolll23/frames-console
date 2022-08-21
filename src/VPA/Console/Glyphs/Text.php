<?php


namespace VPA\Console\Glyphs;


class Text extends GlyphInline
{
    private string $text;

    public function setValue(string $text)
    {
        $this->text = $text;
        $this->widthByContent = strlen($this->text);
        $this->heightByContent = 1;
    }

    public function render(): array
    {
        switch ($this->__get('textAlign')) {
            default:
                $resultString = str_pad($this->text,$this->getWidth(),' ', STR_PAD_RIGHT);
                break;
            case 'right':
                $resultString = str_pad($this->text,$this->getWidth(),' ', STR_PAD_LEFT);
                break;
            case 'center':
                $resultString = str_pad($this->text,$this->getWidth(),' ', STR_PAD_BOTH);
                break;
        }
        $this->renderMap[] = str_split($resultString);
        $this->printMap();
        return $this->renderMap;
    }
}