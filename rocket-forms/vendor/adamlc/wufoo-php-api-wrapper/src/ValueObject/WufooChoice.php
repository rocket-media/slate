<?php namespace Adamlc\Wufoo\ValueObject;

class WufooChoice extends ValueObject
{
    public function __construct($obj = null)
    {
        parent::__construct($obj);
    }

    public $Score;
    public $Label;
}
