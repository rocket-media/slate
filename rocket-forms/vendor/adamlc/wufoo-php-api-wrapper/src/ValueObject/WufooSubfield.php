<?php namespace Adamlc\Wufoo\ValueObject;

class WufooSubfield extends ValueObject
{

    public function __construct($obj = null)
    {
        parent::__construct($obj);
    }

    public $ID;
    public $Label;
}
