<?php namespace Adamlc\Wufoo\Response;

class WebHookResponse
{
    public $Hash;

    public function __construct($hash)
    {
        $this->Hash = $hash;
    }
}
