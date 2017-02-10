<?php namespace App;

class Responder {

    public function validationError($message = 'Validation errors')
    {
        return $this->error($message, 422);
    }

    public function success($message = 'Success!', $statusCode = 200, $data = [], $apiCode = null)
    {
        return $this->respond('success', $message, $statusCode, $data, $apiCode);
    }

    public function error($message = 'Oops. Someting went wrong', $statusCode = 400, $errors = [], $apiCode = null)
    {
        return $this->respond('error', $message, $statusCode, $errors, $apiCode);
    }

    /**
     * Base JSON response
     *
     * @param  string   $type        success|error
     * @param  string   $message     Resonse message
     * @param  integer  $statusCode  HTTP status code
     * @param  array    $data        Additional data to be included with response
     * @param  integer  $apiCode     Api-specific response code, for debugging
     * @return Illuminate\Http\Response
     */
    protected function respond($type, $message, $statusCode, $data = [], $apiCode = null)
    {
        return response()->json([
            $type => array_merge([
                'message' => $message,
                'status_code' => $statusCode,
                'api_code' => $apiCode,
            ], $data)
        ])->setStatusCode($statusCode);
    }

}
