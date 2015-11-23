<?php namespace Adamlc\Wufoo\ValueObject;

class WufooUser extends ValueObject
{
    public function __construct($obj = null)
    {
        parent::__construct($obj);
    }

    public $Name;
    public $IsPublic;
    public $Url;
    public $Description;
    public $DateCreated;
    public $DateUpdated;
    public $Hash;
    public $LinkFields;
    public $LinkEntries;
    public $LinkEntriesCount;
    public $LinkWidgets;
}
