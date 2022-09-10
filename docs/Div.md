**DIV elements**

Div element - it is simple method add paddings or borders. Any block element (table, cell, div) have methods:
```
setPadding(left, right, top, bottom); // can take any positive int values
setBorder(left, right, top, bottom); // can take values 0 or 1
```

Example:
```
// Single Div
$div = $page->addDiv()->setPadding(1, 1, 0, 0)->setBorder(1, 1, 1, 1)->addText()->setValue("Line 1\nLine 2\nLine 3");
// Div with text and another div
$div = $page->addDiv()->setPadding(3, 3, 1, 1)->setBorder(1, 1, 1, 1);
$div->addText()->setValue("Line 1\nLine 2\nLine 3");
$div2 = $div->addDiv()->setPadding(1, 1, 0, 0)->setBorder(1, 1, 1, 1);
$div2->addText()->setValue("Line 1\nLine 2\nLine 3");

$page->display();
```

Result:

![Screenshot of Div example](docs/div.png)

