**Table**

Example of table with different aligns for text, limitation of max length of cell, long string with new lines, borders and paddings:
```$table = $page->addTable()->setBorder(1, 1, 1, 1);
    $row = $table->addRow();
    $row->addCell()
        ->setPadding(1, 1, 0, 0)
        ->setBorder(0, 1, 0, 1)
        ->addText()->setValue("First column");
    $row->addCell()
        ->setPadding(1, 1, 0, 0)
        ->setBorder(0, 0, 0, 1)
        ->addText(['maxWidth' => 25])
        ->setValue("Lorem ipsum dolor sit amet,\n consectetur adipiscing elit, sed do eiusmod tempor");
    $row2 = $table->addRow();
    $row2->addCell()
        ->setPadding(1, 1, 0, 0)
        ->setBorder(0, 1, 0, 0)
        ->addText(['textAlign' => 'center'])->setValue("Center");
    $row2->addCell()
        ->setPadding(1, 1, 0, 0)
        ->setBorder(0, 0, 0, 0)
        ->addText(['textAlign' => 'right'])->setValue("Right text");
    $page->display();
```

Result:

![Screenshot of Div example](docs/table.png)

Like DIV, table supports nested calls. You can just do it:
```
$table = $page->addTable()->setBorder(1, 1, 1, 1);
$row = $table->addRow();
$row->addCell()->setPadding(1, 1, 0, 0)->setBorder(0, 1, 0, 0)->addText()->setValue("Table 1\nCell 1");
$cell1 = $row->addCell()->setPadding(0, 0, 0, 0);
$table2 = $cell1->addTable()->setBorder(1, 1, 1, 1);
$row2 = $table2->addRow();
$row2->addCell()->setPadding(1, 1, 0, 0)->setBorder(0, 1, 0, 0)->addText()->setValue("Table 2\nCell 1");
$cell2 = $row2->addCell()->setPadding(1, 1, 0, 0)->setBorder(0, 0, 0, 0);
$table3 = $cell2->addTable()->setBorder(1, 1, 1, 1);
$row3 = $table3->addRow();
$row3->addCell()->setPadding(1, 1, 0, 0)->setBorder(0, 1, 0, 0)->addText()->setValue("Table 3\nCell 1");
$row3->addCell()->setPadding(1, 1, 0, 0)->setBorder(0, 0, 0, 0)->addText()->setValue("Table 3\nCell 2");

$page->display();
```

And get it:

![Screenshot of Div example](docs/nestedTable.png)
