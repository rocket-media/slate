<?php namespace Adamlc\Wufoo;

class WufooCurl
{

    public function __construct()
    {
        //TIMTODO: set timout
    }

    public function getAuthenticated($url, $apiKey)
    {
        $this->curl = curl_init($url);
        $this->setBasicCurlOptions();

        curl_setopt($this->curl, CURLOPT_USERPWD, $apiKey.':footastical');

        $response = curl_exec($this->curl);
        $this->setResultCodes();
        $this->checkForCurlErrors();
        $this->checkForGetErrors($response);
        curl_close($this->curl);

        return $response;
    }

    public function postAuthenticated($postParams, $url, $apiKey)
    {
        $this->curl = curl_init($url);
        $this->setBasicCurlOptions();

        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-type: multipart/form-data', 'Expect:'));
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $postParams);
        curl_setopt($this->curl, CURLOPT_USERPWD, $apiKey.':footastical');

        $response = curl_exec($this->curl);
        $this->setResultCodes();
        $this->checkForCurlErrors();
        $this->checkForPostErrors($response);
        curl_close($this->curl);

        return $response;
    }

    //http://stackoverflow.com/questions/2081894/handling-put-delete-arguments-in-php
    public function putAuthenticated($postParams, $url, $apiKey)
    {
        $this->curl = curl_init($url);
        $this->setBasicCurlOptions();

        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-type: multipart/form-data', 'Expect:'));
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($postParams));
        curl_setopt($this->curl, CURLOPT_USERPWD, $apiKey.':footastical');

        $response = curl_exec($this->curl);
        $this->setResultCodes();
        $this->checkForCurlErrors();
        $this->checkForPutErrors($response);
        curl_close($this->curl);

        return $response;
    }

    //http://stackoverflow.com/questions/2081894/handling-put-delete-arguments-in-php
    public function deleteAuthenticated($postParams, $url, $apiKey)
    {
        $this->curl = curl_init($url);
        $this->setBasicCurlOptions();

        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-type: multipart/form-data', 'Expect:'));
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($postParams));
        curl_setopt($this->curl, CURLOPT_USERPWD, $apiKey.':footastical');

        $response = curl_exec($this->curl);
        $this->setResultCodes();
        $this->checkForCurlErrors();
        //GET and DELETE both expect 200 response for success
        $this->checkForGetErrors($response);
        curl_close($this->curl);

        return $response;
    }

    public function setBasicCurlOptions()
    {
        //http://bugs.php.net/bug.php?id=47030
//        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->curl, CURLOPT_USERAGENT, 'Wufoo API Wrapper');
        curl_setopt($this->curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);

        // This is required on local machine only
        if ( ! preg_match("/\.com$/",$_SERVER['HTTP_HOST']))
        {
            curl_setopt($this->curl, CURLOPT_SSL_CIPHER_LIST, 'TLSv1');
        }
    }

    private function setResultCodes()
    {
        $this->ResultStatus = curl_getinfo($this->curl);
    }

    private function checkForCurlErrors()
    {
        if (curl_errno($this->curl)) {
            throw new WufooException(curl_error($this->curl), curl_errno($this->curl));
        }
    }

    private function checkForGetErrors($response)
    {
        switch ($this->ResultStatus['http_code']) {
            case 200:
                //ignore, this is good.
                break;
            case 401:
                throw new WufooException('(401) Forbidden.  Check your API key.', 401);
                break;
            default:
                $this->throwResponseError($response);
                break;
        }
    }

    private function checkForPutErrors($response)
    {
        switch ($this->ResultStatus['http_code']) {
            case 201:
                //ignore, this is good.
                break;
            case 401:
                throw new WufooException('(401) Forbidden.  Check your API key.', 401);
                break;
            default:
                $this->throwResponseError($response);
                break;
        }
    }

    private function checkForPostErrors($response)
    {
        switch ($this->ResultStatus['http_code']) {
            case 200:
            case 201:
                //ignore, this is good.
                break;
            case 401:
                throw new WufooException('(401) Forbidden. Check your API key.', 401);
                break;
            default:
                $this->throwResponseError($response);
                break;
        }
    }

    private function throwResponseError($response)
    {
        if ($response) {
            $obj = json_decode($response);
            throw new WufooException('('.$obj->HTTPCode.') '.$obj->Text, $this->ResultStatus['http_code']);
        } else {
            throw new WufooException(
                'We did not anticipate this error type. Please contact support here: support@wufoo.com',
                500
            );
        }

        return $response;
    }
}
