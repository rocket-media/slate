<?php namespace Adamlc\Wufoo\ValueObject;

abstract class ValueObject
{
    public function __construct($obj)
    {
        if ($obj) {
            foreach ($obj as $key => $value) {
                $this->setProperty($key, $value, (isset($obj->ID) ? $obj->ID : ''));
            }
        }
    }

    protected function setProperty($key, $value, $parentId)
    {
        $this->$key = $value;
    }
}
