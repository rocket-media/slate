<?php namespace Adamlc\Wufoo\ValueObject;

/**
 * A bit of logic to ensure that field IDs are sent with proper prefix
 *
 * @author Timothy S Sabat
 */
class WufooSubmitField
{
    private $id;
    private $value;
    private $isFile;

    public function __construct($id, $value, $isFile = false)
    {
        $this->id = $id;
        $this->value = $value;
        $this->isFile = $isFile;
    }

    public function getId()
    {
        $ret = str_replace('Field', '', $this->id);
        if (is_numeric($ret)) {
            $ret = 'Field'.$ret;
        }

        return $ret;
    }

    public function getValue()
    {
        if ($this->isFile) {
            return "@$this->value";
        } else {
            return $this->value;
        }
    }
}
