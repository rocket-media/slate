<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller;
use App\Exceptions\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Wufoo\WufooHandler;
use App\KeyManager;
use App\Responder;

class FormController extends Controller
{
    /**
     * Request key manager
     * @var  App\KeyManager
     */
    protected $keyManager;

    /**
     * Responder class, responsible for responding in a consistent way.
     * @var  App\Responder
     */
    protected $respond;

    /**
     * Form handler class
     * @var  mixed
     */
    protected $formHandler;

    public function __construct(KeyManager $keyManager, Responder $responder, WufooHandler $wufooHandler)
    {
        $this->keyManager = $keyManager;
        $this->respond = $responder;
        // 'Hardcode' Wufoo handler for now. In the future we might want to have
        // some mechanism for resolving which form handler is required
        $this->formHandler = $wufooHandler;
    }

    /**
     * Gets, validates, and sends data to form integration (currently only Wufoo).
     *
     * Expected request structure
     *  {
     *      "data": [
     *          {
     *              "id": "Field1",
     *              "name": "firstName",
     *              "value": "billy"
     *           }
     *       ],
     *       "serviceTitan": true/false,
     *       ...
     *   }
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function process(Request $request)
    {
        try {
            $this->validateFormData($request->get('data'));
        } catch (ValidationException $e) {
            return $this->respond->validationError('Form data validation error: ' . $e->getMessage());
        }

        $response = $this->formHandler->handle($request);

        /**
         * Service Titan integration
         *
         * Recall the gotcha here, where $request->get('serviceTitan') might be
         * the string 'false', which as a boolean is true. We remedied this by
         * making sure Vue submitted data as application/json, but when it was
         * submitting as x-www-form-encoded, it was passing the string 'false',
         * breaking this conditional. Just FYI.
         */
        if (($response->getStatusCode() === 200 and $request->get('serviceTitan')) or getenv('TESTING_SERVICE_TITAN')) {
            // Send data to Service Titan
            $serviceTitanApi = app()->make('App\ServiceTitan\ServiceTitanApi');
            $stResponse = $serviceTitanApi->postBooking($request, $response);
            // Add to response to indicate if ST failed/passed
            $response->setContent($this->mergeResponseValue('serviceTitanResponse', $stResponse, $response));
        }

        return $response;
    }

    /**
     * Merge key/value into current response text.
     *
     * Because we wait for the Wufoo response before executing any third party
     * integrations (Service Titan, Stripe, etc.), we often have the need to
     * merge the responses from those integrations into the already formed
     * response from Wufoo.
     *
     * @param  String $newKey          The new response key
     * @param  String $value           The value
     * @param  \Symfony\Component\HttpFoundation\Response $currentResponse
     * @return String                  The updated content string
     */
    protected function mergeResponseValue($newKey, $value, $currentResponse)
    {
        // Add to response to indicate if ST failed/passed
        $content = json_decode($currentResponse->getContent());
        $content->$newKey = $value;
        $content = json_encode($content);

        return $content;
    }



    /**
     * Validate form data.
     *
     * @param $fieldDataArray
     *
     * @throws ValidationException
     */
    protected function validateFormData($fieldDataArray)
    {
        // Data should be an array
        if (is_null($fieldDataArray) or !is_array($fieldDataArray)) {
            throw new ValidationException('Invalid form data');
        }

        foreach ($fieldDataArray as $fieldData) {

            // Data should also be an array of arrays
            if (!is_array($fieldData)) {
                throw new ValidationException('Invalid form data');
            }

            // Strip empty values to avoid validation errors with optional fields
            $fieldData = array_filter($fieldData);

            $validator = Validator::make($fieldData, $this->formHandler->getValidationRules());

            if ($validator->fails()) {
                throw new ValidationException($validator->errors()->first());
            }
        }
    }

}
