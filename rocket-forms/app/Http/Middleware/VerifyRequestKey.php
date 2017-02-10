<?php namespace App\Http\Middleware;

use Closure;
use App\KeyManager;
use App\Exceptions\AuthenticationException;
use App\Responder;

class VerifyRequestKey
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
        $keyManager = app()->make(KeyManager::class);

        if( ! $keyManager->verify($request->header(env('REQUEST_KEY_HEADER')))) {
            $respond = app()->make(Responder::class);
            return $respond->error('Invalid request key.', 401);
        }

        return $next($request);
    }
}
