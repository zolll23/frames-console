Simple use without any attributes:

```
$data = [
        'Company' => 'Everyone Inc',
        'Date' => '2002',
        'Description' => 'You may recognise the first few words as "lorem ipsum dolor sit amet".',
        'Code 1' => 'XU19299',
        'Code IPU' => 'UI2929I199',
        'Serial No' => '19889',
    ];
$table1d = new Table1D($config);
$table1d->output($data);
```

Output(first column - key of array, second - value):

![Simple Table1D](Table1DSimple.png)

Table1D with Header row:
```
$table1d = (new Table1D($config))->setHeader("Attribute", "Value");
// we use $data from prevoius example
$table1d->output($data);
```

![Table1D With Header](Table1DWithHeader.png)

The default display type is slim (the frame is only around the entire table and title bar). 
You can use types: 
1. "frame" - frame around each cell
2. "frameless" - the frame is not shown

How to set type:

```
$table1d = (new Table1D($config))
        ->setConfig(['type' => TableDisplayMode::Frame,])
        ->setHeader("Attribute", "Value");
$table1d->output($data);
```

Result:

![Table1D With Header and Type=Frame](Table1DFrame.png)

**Type=Frameless**

```
$table1d = (new Table1D($config))
        ->setConfig(['type' => TableDisplayMode::Frameless,])
        ->setHeader("Attribute", "Value");
$table1d->output($data);
```
![Table1D With Header and Type=Frameless](Table1DFrameless.png)

Other supported attributes for Table1D:

| Attribute | Default value | Values | Description |
--- | --- | --- | ---
type | TableDisplayMode::Slim | Slim, Frame, Frameless | Describes how show frames for table
columns | '1' | 'auto', positive int(2,3,4...) | Pack data into multiple columns
firstColumnWidth | 'auto' | 'auto', positive int(2,3,4...) | Width of first column in symbols
firstColumnMaxWidth | 'auto' | 'auto', positive int(2,3,4...) | Width of second column in symbols
secondColumnWidth | 'auto' | 'auto', positive int(2,3,4...) | Max width of first column in symbols (if the content length is less than the maximum width - the cell size is equal to the actual content length)
secondColumnMaxWidth | 'auto' | 'auto', positive int(2,3,4...) | Max width of second column in symbols (if the content length is less than the maximum width - the cell size is equal to the actual content length)

Examples:
```
$table1d = (new Table1D($config))
        ->setConfig([
                'columns'=>2,
                'secondColumnWidth' => 30,
                ]);
$table1d->output($data);
```

![Table1D 2 Columns secondMaxWidth=30](Table1D2colsMaxWidth30.png)
