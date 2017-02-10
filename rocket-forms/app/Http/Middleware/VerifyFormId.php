<?php namespace App\Http\Middleware;

use Closure;
use App\KeyManager;
use App\Exceptions\AuthenticationException;
use App\Responder;

class VerifyFormId
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
        // Let's  ensure the Form-Id header is provided
        if (is_null($request->header(env('FORM_ID_HEADER')))) {
            $respond = app()->make(Responder::class);
            return $respond->error('Invalid form ID.', 422);
        }

        return $next($request);
    }
}
