<?php namespace Adamlc\Wufoo\ValueObject;

class WufooForm extends ValueObject
{
    public function __construct($obj = null)
    {
        parent::__construct($obj);
    }

    public $Name;
    public $Description;
    public $RedirectMessage;
    public $Url;
    public $Email;
    public $IsPublic;
    public $Language;
    public $StartDate;
    public $EndDate;
    public $EntryLimit;
    public $DateCreated;
    public $DateUpdated;
    public $Hash;
}
