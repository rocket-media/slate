<?php namespace App\Http\Middleware;

use Closure;
use App\KeyManager;
use App\Exceptions\AuthenticationException;
use App\Responder;

class VerifyApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  IlluminateHttpRequest  $request
     * @param  Closure                $next
     * @return  mixed
     */
    public function handle($request, Closure $next)
    {
        // We expect the forms API key to be supplied with the header and match what's in the .env file
        $formsApiKey = $request->header(env('ROCKET_FORMS_API_KEY_HEADER'));

        if (is_null($formsApiKey) or $formsApiKey !== env('ROCKET_FORMS_API_KEY')) {
            $respond = app()->make(Responder::class);
            return $respond->error('Invalid Rocket Forms API key.', 401);
        }

        return $next($request);
    }
}
