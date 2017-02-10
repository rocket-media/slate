<?php

namespace App\ServiceTitan;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use Log;
use Validator;
use App\Exceptions\Handler;

/**
 * Communicates with the Service Titan API. In the future this should be split
 * into separate classes; perhaps one for each endpoint, with a parent class
 * that contains common code.
 */
class ServiceTitanApi
{
    const BASE_URI = 'https://api.servicetitan.com/v1/';
    const API_KEY_HEADER = 'X-HTTP-ServiceTitan-Api-Key';

    /**
     * Send a POST request to create a new booking.
     *
     * @param Illuminate\Http\Request $request
     */
    public function postBooking($request)
    {
        // Reformat the request input for validation etc.
        $data = $this->formatData($request);

        $validator = Validator::make($data, [
            'phone' => 'required|digits:10',
            'name' => 'required',
            'serviceType' => 'required',
            'address' => 'required',
            'city' => 'required',
            'date' => 'required|date_format:Ymd',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            $this->logError(422, $validator->messages()->first());

            return $this->respond(422, $validator->messages()->first());
        }

        $httpClient = $this->makeHttpClient();
        $data = $this->prepareData($data);

        try {
            $response = app()->environment() === 'production' ? $httpClient->post('bookings', ['json' => $data]) : $this->makeFakeServiceTitanResponse();
            $responseData = app()->environment() === 'production' ? json_decode($response->getBody()->getContents())->data : $response;

            return $this->respond(200, null, $responseData);
        } catch (RequestException $e) {
            $suggestion = '';

            if ($e instanceof ClientException) {
                $suggestion = 'Ensure API key is set in the environment file and API URL is valid';
            }

            $this->logException($e, $suggestion);

            $responseMessage = !is_null($e->getResponse()) ? $e->getResponse()->getReasonPhrase() : '';

            return $this->respond($e->getCode(), $responseMessage . '. ' . $suggestion, null);
        }
    }

    /**
     * Generate a fake response that mimics what comes from the Service Titan API.
     * This response is served when not in the production environment.
     *
     * @return array
     */
    protected function makeFakeServiceTitanResponse()
    {
        return [
            "customerType" => null,
            "id" => "000000",
            "externalId" => "0000000000000",
            "createdOn" => "0000-00-00T00:00:00.0000000Z",
            "active" => true,
            "start" => "0000-00-00T00:00:00+00:00",
            "summary" => "Please send $$\n-------------\nService Type: New replacement or install quote\nMaintenance type: N/A\nTime: 8:00AM to 10:00AM",
            "address" => [
                "street" => "Fake Address",
                "unit" => null,
                "country" => "USA",
                "city" => "Gilbert",
                "state" => "CO",
                "zip" => "00000",
                "streetAddress" => "Fake Address"
            ],
            "customer" => "Fake Customer",
            "contacts" => [
                [
                    "id" => 000000,
                    "type" => "Email",
                    "value" => "fakeresponse@example.com",
                    "memo" => null,
                    "active" => true
                ],
                [
                    "id" => 000000,
                    "type" => "Phone",
                    "value" => "8005555555",
                    "memo" => null,
                    "active" => true
                ]
            ],
            "job" => null,
            "isFirstTimeClient" => null,
            "uploadedImages" => [],
            "isSendConfirmationEmail" => null,
            "businessUnitId" => null,
            "status" => "New"
        ];
    }

    /**
     * Transforms the request data into a format that's easier to work with.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function formatData($request)
    {
        // Convert field data into format like:
        // ['fieldName' => 'value']
        $fieldData = [];
        foreach ($request->get('data') as $field) {
            $fieldData[$field['name']] = $field['value'];
        }

        return $fieldData;
    }

    /**
     * Create a Guzzle Client for interacting with the Service Titan API.
     *
     * @return GuzzleHttp\Client
     */
    protected function makeHttpClient()
    {
        return new Client([
            'base_uri' => self::BASE_URI,
            'timeout' => 5.0,
            'headers' => [
                self::API_KEY_HEADER => env('SERVICE_TITAN_API_KEY', ''),
            ],
        ]);
    }

    /**
     * Convert request data into a format that's ready for the Service Titan Api.
     *
     * @param Illuminate\Http\Reqest $request
     *
     * @return array
     */
    protected function prepareData($fieldData)
    {
        $customerType = array_key_exists('customerType', $fieldData) ? $fieldData['customerType'] : 'Residential';
        $start = Carbon::createFromFormat('Ymd', $fieldData['date'], env('CLIENT_TIMEZONE', 'America/New_York'));
        // Because appointment times can vary so widely, for now we'll just set the time to 8am.
        // The actual requested time slot will be added to the `summary` field
        $start->hour(8)->minute(0)->second(0);
        $time = array_key_exists('time', $fieldData) ? $fieldData['time'] : 'N/A';
        $maintenanceType = array_key_exists('serviceCategory', $fieldData) ? $fieldData['serviceCategory'] : 'N/A';

        // We'll need to pass additional data to the CSR. But Service Titan doesn't
        // offer a way to do this, so we'll have to jam it all into the `summary` field
        $summary = <<<EOT
{$fieldData['message']}
-------------
Service Type: {$fieldData['serviceType']}
Maintenance type: $maintenanceType
Time: $time
EOT;
        $zip = array_key_exists('zip', $fieldData) ? $fieldData['zip'] : env('CLIENT_ZIP', '');

        $data = [
            'customerType' => $customerType,
            'externalId' => uniqid(),
            'start' => $start->toIso8601String(),
            'summary' => $summary,
            'address' => [
                'street' => $fieldData['address'],
                'country' => 'USA',
                'city' => $fieldData['city'],
                'state' => env('CLIENT_STATE', ''),
                'zip' => $zip,
                'streetAddress' => $fieldData['address'],
            ],
            'customer' => $fieldData['name'],
            'contacts' => [
                [
                    'type' => 'email',
                    'value' => $fieldData['email'],
                ],
                [
                    'type' => 'phone',
                    'value' => $fieldData['phone'],
                ],
            ],
        ];

        return $data;
    }

    /**
     * Logs an exception.
     *
     * @param RequestException $e
     * @param string           $suggestion A helpful suggestion to resolve the error
     *
     * @return string
     */
    protected function logException($e, $suggestion = '')
    {
        // Log the exception
        $exceptionHandler = new Handler();
        $exceptionHandler->report($e);

        $response = $e->getResponse();
        $errorCode = $e->getCode();
        $reason = !is_null($response) ? $response->getReasonPhrase() : '';
        $this->logError($e->getCode(), $reason, $suggestion, ['exception' => $e]);
    }

    /**
     * Logs an error in a preformatted way.
     *
     * @param string $errorCode
     * @param string $message
     * @param string $suggestion A helpful suggestion to resolve the error
     * @param array  $data
     *
     * @return bool
     */
    protected function logError($errorCode = false, $message = false, $suggestion = false, $data = [])
    {
        $prefix = 'Service Titan API Error: ';
        $errorCode = $errorCode ? $errorCode.' | ' : '';
        $message = $message ? $message.' ' : '';
        $suggestion = $suggestion ? $suggestion.' ' : '';

        Log::error($prefix.$errorCode.$message.$suggestion, $data);
    }

    /**
     * Prepare a response that will be sent back to the frontend (along with
     * the Wufoo response).
     *
     * @param int    $statusCode
     * @param string $message
     * @param array  $data The data sent to Service Titan
     *
     * @return array
     */
    protected function respond($statusCode, $message = null, $data = null)
    {
        return [
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data,
        ];
    }
}
