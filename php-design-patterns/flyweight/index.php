<?php

class DataType
{
    const TYPE_STRING = 's';
    const TYPE_FORMULA = 'f';
    const TYPE_NULL = 'null';

    private $type;

    private function __construct($type)
    {
        $this->type = $type;
    }

    private static $typeMap = [];

    public static function create($type)
    {
        if (!array_key_exists($type, self::$typeMap)) {
            self::$typeMap[$type] = new DataType($type);
        }
        return self::$typeMap[$type];
    }
}

class Cell
{
    private $value;
    private $dataType;

    public function __construct(DataType $dataType, $value = '')
    {
        $this->dataType = $dataType;
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getDataType()
    {
        return $this->dataType;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function setDataType(DataType $dataType)
    {
        $this->dataType = $dataType;
    }
}

for ($x = 0; $x < 65535; $x++) {
    $cells[$x] = new Cell(DataType::create(DataType::TYPE_NULL));
}

var_dump($cells[500]);
$cells[15000]->setDataType(DataType::create(DataType::TYPE_FORMULA));
var_dump($cells[15000]);
