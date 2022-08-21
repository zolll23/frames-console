<?php


namespace VPA\Console;


use VPA\Console\Glyphs\Table;
use VPA\DI\Container;

class Table1D
{
    private object $table;

    public function __construct(protected array $data1D, private string $mode = TableDisplayMode::Slim)
    {
        $di = new Container();
        $this->table = $page = $di->get(Table::class);
        var_dump($data1D);
        foreach ($this->data1D as $name => $value) {
            $row = $this->table->addRow()->ifFirstSibling(['borderTop'=>1]);
            $row->addCell()->setBorder(1,0,0,0)->addText()->setValue($name);
            $row->addCell()->setBorder(1,1,0,0)->addText()->setValue($value);
        }
        $row->ifLastSibling(['borderBottom'=>1]);
        if ($this->table->isLastSibling($row)) {
            var_dump($row->getConfig());
        }

        $this->table->display();
        echo "width: ".$this->table->getWidth()."\n";
        echo "height: ".$this->table->getHeight()."\n";
    }
}