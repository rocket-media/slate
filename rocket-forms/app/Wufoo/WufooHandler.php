<?php namespace App\Wufoo;

use Adamlc\Wufoo\ValueObject\WufooSubmitField;
use Adamlc\Wufoo\Response\PostResponse;
use Adamlc\Wufoo\WufooException;
use Adamlc\Wufoo\WufooApiWrapper;
use Illuminate\Http\Request;
use App\KeyManager;
use App\Responder;
use App\Exceptions\Handler;

class WufooHandler {

    /**
     * The reponder class
     * @var  App\Responder;
     */
    protected $respond;

    public function __construct(Responder $responder)
    {
        $this->respond = $responder;
    }

    public function getValidationRules()
    {
        return [
            'id' => 'required',
            'name' => 'required',
            'value' => 'required',
        ];
    }

    public function handle($request)
    {
        $fieldDataArray = $request->get('data');
        // Wrap fieldData with Wufoo-ness
        $formId = $request->header(env('FORM_ID_HEADER'));
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
     * Mimic a sucessfull Wufoo response to return when doing local development.
     *
     * @return PostResponse
     */
    protected function makeFakeWufooPostResponse()
    {
        $fakeResponse = new \StdClass();

        $fakeResponse->Success = true;
        $fakeResponse->ErrorText = null;
        $fakeResponse->FieldErrors = null;
        $fakeResponse->EntryId = 99999;
        $fakeResponse->RedirectUrl = 'fakeRedirectUrl';
        $fakeResponse->EntryLink = 'fakeEntryLink';

        return new PostResponse(json_encode($fakeResponse));
    }

    /**
     * Handle a Wufoo exception
     *
     * @param  WufooException $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function parseWufooException(WufooException $e)
    {
        // Log the exception
        $exceptionHandler = new Handler();
        $exceptionHandler->report($e);

        return $this->respond->error('Wufoo Exception: '.$e->getMessage(), $e->getCode(), []);
    }

    protected function parseWufooResponse($wufooResponse, Request $request)
    {
        $keyManager = app()->make(KeyManager::class);

        if ($wufooResponse->Success) {
            // If request successful remove the request key from cache
            $keyManager->forget($request->header(env('REQUEST_KEY_HEADER')));

            return $this->respondSuccess($wufooResponse);
        }

        $fieldErrors = [];
        foreach ($wufooResponse->FieldErrors as $fieldError) {
            $fieldErrors[] = [
                'id' => $fieldError->ID,
                'message' => $fieldError->ErrorText,
            ];
        }

        // Decrement the 'uses' of the key
        $keyManager->decrement($request->header(env('REQUEST_KEY_HEADER')));

        return $this->respondFieldError($fieldErrors, $wufooResponse->ErrorText);
    }

    protected function respondFieldError($fieldErrors, $message = 'Oops. Someting went wrong', $statusCode = 400, $apiCode = null)
    {
        return $this->respond->error($message, $statusCode, ['fieldErrors' => $fieldErrors], $apiCode);
    }

    protected function respondSuccess(PostResponse $wufooResponse)
    {
        return $this->respond->success('Success!', 200, [
            'entryId' => $wufooResponse->EntryId,
            'entryLink' => $wufooResponse->EntryLink,
        ]);
    }


}
