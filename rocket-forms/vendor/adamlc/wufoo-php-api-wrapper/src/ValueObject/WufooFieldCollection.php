<?php namespace Adamlc\Wufoo\ValueObject;

class WufooFieldCollection
{

    public $Fields = array();
    public $Hash = array();

    public function getField($id)
    {
        return $this->Hash[$id];
    }

    public function getParent($subfield)
    {
        return $this->Fields[$subfield->ParentID];
    }
}
