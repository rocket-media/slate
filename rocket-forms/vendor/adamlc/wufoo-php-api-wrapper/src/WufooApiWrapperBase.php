<?php namespace Adamlc\Wufoo;

/**
 * Encapsulates the utility functions not required by user.
 *
 * @package default
 * @author Timothy S Sabat
 */
class WufooApiWrapperBase
{
    protected function getHelper($url, $type, $iterator, $index = 'Hash')
    {
        $arr = array();

        $this->curl = new WufooCurl();
        $response = $this->curl->getAuthenticated($url, $this->apiKey);
        $response = json_decode($response);
        $className = '\Adamlc\Wufoo\ValueObject\Wufoo' . $type;

        foreach ($response->$iterator as $obj) {

            if ($obj) {
                $arr[$obj->$index] = new $className($obj);
            }
        }

        return $arr;
    }

    protected function getFullUrl($url)
    {
        return 'https://'.$this->subdomain.'.'.$this->domain.'/api/v3/'.$url.'.json';
    }
}
