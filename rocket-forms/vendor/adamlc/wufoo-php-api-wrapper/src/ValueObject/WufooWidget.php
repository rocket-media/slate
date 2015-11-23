<?php namespace Adamlc\Wufoo\ValueObject;

class WufooWidget extends ValueObject
{
    public function __construct($obj = null)
    {
        parent::__construct($obj);
    }

    public $Name;
    public $Size;
    public $Type;
    public $TypeDesc;
    public $Hash;
}
