<?php namespace Adamlc\Wufoo;

/**
 * You don't need to directly call this class.  Used to encapsulate the filter string.
 *
 * @package default
 * @author Timothy S Sabat
 */
class Filter
{
    private $id;
    private $operator;
    private $value;

    public function __construct($id, $operator, $value)
    {
        $this->id = str_replace('Filter', '', $id);
        $this->operator = $operator;
        $this->value = $value;
    }

    public function getFilterString()
    {
        return $this->getField().'+'.$this->getOperator().'+'.$this->getValue();
    }

    private function getField()
    {
        $ret = $this->id;

        if (is_numeric($this->id)) {
            $ret = 'Field'.$this->id;
        }

        return $ret;
    }

    private function getOperator()
    {
        $ret = strtolower(str_replace('_', ' ', $this->operator));
        if (in_array($ret, $this->getWhiteList())) {
            $ret = str_replace(' ', '_', $ret);
            $ret = str_replace('_null', '_NULL', $ret);
        } else {
            throw new WufooException('Invalid Filter Operator: '.$this->operator, '400');
        }

        return ucfirst($ret);
    }

    private function getValue()
    {
        return urlencode($this->value);
    }

    private function getWhiteList()
    {
        return array(
            'contains', 'does not contain', 'begins with', 'ends with',
            'is less than', 'is greater than', 'is on', 'is before', 'is after',
            'is not equal to', 'is equal to', 'is not null'
        );
    }
}
