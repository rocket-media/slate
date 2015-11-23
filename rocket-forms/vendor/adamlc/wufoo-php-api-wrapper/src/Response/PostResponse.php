<?php namespace Adamlc\Wufoo\Response;

class PostResponse
{
    public $Success;
    public $ErrorText;
    public $EntryLink;
    public $FieldErrors;

    public function __construct($response)
    {
        $response = json_decode($response);
        foreach ($response as $key => $value) {
            $this->$key = $value;
        }
    }
}
