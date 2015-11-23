<?php namespace Adamlc\Wufoo\ValueObject;

class WufooField extends ValueObject
{

    public function __construct($obj = null)
    {
        parent::__construct($obj);
    }

    public $Title;
    public $Type;
    public $ID;

    protected function setProperty($key, $value, $parentID)
    {
        switch ($key) {
            case 'SubFields':
                if ($value) {
                    foreach ($value as $subfield) {
                        $subfield->ParentID = $parentID;
                        $this->SubFields[$subfield->ID] = $subfield;
                    }
                }
                break;
            case 'Choices':
                foreach ($value as $choice) {
                    $this->Choices[] = $choice;
                }
                break;
            default:
                $this->$key = $value;
                break;
        }
    }
}
