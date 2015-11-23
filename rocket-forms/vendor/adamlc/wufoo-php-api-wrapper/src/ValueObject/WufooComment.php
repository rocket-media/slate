<?php namespace Adamlc\Wufoo\ValueObject;

class WufooComment extends ValueObject
{
    public function __construct($obj = null)
    {
        parent::__construct($obj);
    }

    public $CommentId;
    public $CommentedBy;
    public $DateCretaed;
    public $EntryId;
    public $Text;
}
