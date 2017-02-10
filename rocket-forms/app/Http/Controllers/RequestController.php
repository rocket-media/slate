<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FormRequest;
use App\KeyManager;
use App\Responder;
use Laravel\Lumen\Routing\Controller;

class RequestController extends Controller {

    /**
     * Return a valid request key. The request key is meant to be provided with an XHR
     * request, and if it's valid then the app will respond to the request.
     *
     * GET /request-key
     *
     * @param Request     $request
     * @param FormRequest $formRequest Class that actually generates & caches the request key
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getKey(Request $request, KeyManager $keyManager, Responder $responder)
    {
        $requestKey = $keyManager->get();

        // Return key
        return response()->json([
            'requestKey' => $requestKey,
        ]);
    }

}
