<?php

namespace App\Http\Controllers;

use Adamlc\Wufoo\Response\PostResponse;
use Adamlc\Wufoo\WufooException;
use App\Exceptions\AuthorizationException;
use App\Exceptions\ValidationException;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Adamlc\Wufoo\ValueObject\WufooSubmitField;
use Adamlc\Wufoo\WufooApiWrapper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\Handler;

class FormsController extends BaseController
{
    const API_CODE_WUFOO_EXCEPTION = 50; // Arbitrary number
    const REQUEST_KEY_LIFETIME = 1440; // Minutes
    protected $requestKeyErrorUses;
    protected $requestKeyHeaderName = 'Rocket-Forms-Request-Key';
    protected $formIdHeaderName = 'Rocket-Forms-Form-Id';

    public function __construct()
    {
        $this->requestKeyErrorUses = (int) getenv('REQUEST_KEY_ERROR_USES') ?: 5;
    }

    /**
     * Creates and returns a request key. The request key is to be provided with an XHR
     * request, and if it's valid (e.g. it's been generated by this method) then the app will
     * process the data and send along to the forms backend (Wufoo).
     *
     * GET /request-key
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getRequestKey(Request $request)
    {
        // We expect the forms API key to be supplied with the header and match what's in the .env file
        $formsApiKey = $request->header($this->requestKeyHeaderName);
        if (is_null($formsApiKey) or $formsApiKey !== env('ROCKET_FORMS_API_KEY')) {
            return $this->respondUnauthorized();
        }

        // Generate a new request key
        $requestKey = str_random();

        // Cache it for 24 hours
        // Notice the value. This is the remaining 'uses' for the key, in the case
        // that there is a submission error. Each error occurrence will decrement the
        // value, and a successful submission will remove it entirely.
        Cache::put($requestKey, $this->requestKeyErrorUses, self::REQUEST_KEY_LIFETIME);

        // Return key
        return response()->json([
            'requestKey' => $requestKey
        ]);
    }

    /**
     * Gets, validates, and sends data to Wufoo.
     *
     * Right now everything is done in the controller. When the need arises, these functions
     * will be extracted to their own classes.
     *
     * Expected request structure
     *  {
     *      "data": [
     *          {
     *              "id": "Field1",
     *              "name": "firstName",
     *              "value": "billy"
     *           }
     *       ]
     *   }
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function processFormData(Request $request)
    {
        $fieldDataArray = $request->input('data');
        
        try {
            $this->validateRequest($request);
            $this->validateFormData($fieldDataArray);
        } catch (ValidationException $e) {
            return $this->respondValidationError($e->getMessage());
        }

        // Wrap fieldData with Wufoo-ness
        $formId = $request->header($this->formIdHeaderName);
        $wufooPostData = [];
        foreach ($fieldDataArray as $fieldData) {
            $wufooPostData[] = new WufooSubmitField($fieldData['id'], $fieldData['value']);
        }

        $wufooApiWrapper = new WufooApiWrapper(getenv('WUFOO_API_KEY'), getenv('WUFOO_SUBDOMAIN'));

        try {
            // Only actually submit to Wufoo if on production
            $wufooResponse = app()->environment() === 'production' ? $wufooApiWrapper->entryPost($formId, $wufooPostData) : $this->makeFakeWufooPostResponse();
            $response = $this->parseWufooResponse($wufooResponse, $request);
        } catch (WufooException $e) {
            $response = $this->parseWufooException($e);
        }

        return $response;
    }

    /**
     * Mimic a sucessfull Wufoo response to return when doing local development
     *     
     * @return PostResponse
     */
    protected function makeFakeWufooPostResponse()
    {
        $fakeResponse = new \StdClass;

        $fakeResponse->Success = true;
        $fakeResponse->ErrorText = null;
        $fakeResponse->FieldErrors = null;
        $fakeResponse->EntryId = 99999;
        $fakeResponse->RedirectUrl = 'fakeRedirectUrl';
        $fakeResponse->EntryLink = 'fakeEntryLink';

        return new PostResponse(json_encode($fakeResponse));
    }

    protected function parseWufooException(WufooException $e)
    {
        // Log the exception
        $exceptionHandler = new Handler();
        $exceptionHandler->report($e);

        return $this->respondError([], 'Wufoo Exception: ' . $e->getMessage(), 400, self::API_CODE_WUFOO_EXCEPTION);
    }

    /**
     * Validate the request
     *
     * @param $request
     * @throws ValidationException
     */
    protected function validateRequest($request)
    {
        /*
         * First, let's ensure this is a valid request. We'll look in our cache store to see
         * if a key exists with the provided requestKey and the value is greater than 0. Recall,
         * the value of the key starts at 5 (or so), and is decremented when an error occurs. Once it's
         * decremented to 0, it will be regarded as invalid.
         */
        if (! Cache::get($request->header($this->requestKeyHeaderName)) and $request->header($this->requestKeyHeaderName) !== getenv('EVERYGREEN_REQUEST_KEY')) {
            throw new ValidationException('Invalid request key.');
        }

        // Let's also ensure the Form-Id header is provided
        if (is_null($request->header($this->formIdHeaderName))) {
            throw new ValidationException('Form id is required.');
        }
    }

    /**
     * Validate form data
     *
     * @param $fieldDataArray
     * @throws ValidationException
     */
    protected function validateFormData($fieldDataArray)
    {
        if (is_null($fieldDataArray)) {
            throw new ValidationException('No form data');
        }

        foreach ($fieldDataArray as $fieldData) {
            // Strip empty values to avoid validation errors with optional fields
            $fieldData = array_filter($fieldData);

            $validator = Validator::make($fieldData, [
                'id'    => 'required',
                'name'  => 'required',
                'value' => 'sometimes|required',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator->errors()->first());
            }
        }
    }

    protected function parseWufooResponse($wufooResponse, Request $request)
    {
        if ($wufooResponse->Success) {
            // If request successful remove the request key from cache
            Cache::forget($request->header($this->requestKeyHeaderName));

            return $this->respondSuccess($wufooResponse);
        }

        $fieldErrors = [];
        foreach ($wufooResponse->FieldErrors as $fieldError) {
            $fieldErrors[] = [
                'id'      => $fieldError->ID,
                'message' => $fieldError->ErrorText
            ];
        }

        // Decrement the 'uses' of the key
        Cache::decrement($request->header($this->requestKeyHeaderName));

        return $this->respondError($fieldErrors, $wufooResponse->ErrorText);
    }

    protected function respondError($fieldErrors, $message = 'Oops. Someting went wrong', $statusCode = 400, $apiCode = null)
    {
        return response()->json([
            'error' => [
                'message'     => $message,
                'status_code' => $statusCode,
                'api_code'    => $apiCode,
                'fieldErrors' => $fieldErrors
            ],
        ])->setStatusCode($statusCode);
    }

    protected function respondSuccess(PostResponse $wufooResponse, $message = 'Success!', $statusCode = 200)
    {
        return response()->json([
            'success' => [
                'message'     => $message,
                'entryId'     => $wufooResponse->EntryId,
                'entryLink'   => $wufooResponse->EntryLink,
                'status_code' => $statusCode,
            ],
        ])->setStatusCode($statusCode);
    }

    protected function respondUnauthorized($message = 'Invalid API Key')
    {
        return response()->json([
            'error' => [
                'message'     => $message,
                'status_code' => 401,
            ],
        ])->setStatusCode(401);
    }

    protected function respondValidationError($message = 'Validation error')
    {
        return response()->json([
            'error' => [
                'message'     => $message,
                'status_code' => 422,
            ],
        ])->setStatusCode(422);
    }
}