<?php namespace App;

use Illuminate\Support\Facades\Cache;

/**
 * Handles form request keys.
 *
 * Request keys are used to verify requested to the app. To prevent spam, a
 * valid key must be included with each request. Keys are then destroyed after a
 * successful form submission
 */
class KeyManager {

    /**
     * @const  int  Key lifetime in minutes
     */
    const REQUEST_KEY_LIFETIME = 1440;

    /**
     * @var string  Remaining key uses
     */
    protected $requestKeyErrorUses;

    public function __construct()
    {
        $this->requestKeyErrorUses = (int) env('REQUEST_KEY_ERROR_USES') ?: 5;
    }

    /**
     * Creates, caches, and returns a request key.
     *
     * @return string
     */
    public function get() {

        // Generate a new request key
        $requestKey = str_random();

        // Cache it for 24 hours
        // Notice the value. This is the remaining 'uses' for the key, in the case
        // that there is a submission error. Each error occurrence will decrement the
        // value, and a successful submission will remove it entirely.
        Cache::put($requestKey, $this->requestKeyErrorUses, self::REQUEST_KEY_LIFETIME);

        return $requestKey;
    }

    /**
     * Destroys a cached key
     *
     * @param   string  $key
     * @return  void
     */
    public function forget($key)
    {
        Cache::forget($key);
    }

    public function verify($key)
    {
        /*
         * First, let's ensure this is a valid request. We'll look in our cache store to see
         * if a key exists with the provided requestKey and the value is greater than 0. Recall,
         * the value of the key starts at 5 (or so), and is decremented when an error occurs. Once it's
         * decremented to 0, it will be regarded as invalid.
         */
        if (!Cache::get($key) and $key !== env('EVERGREEN_REQUEST_KEY')) {
            return false;
            // return $this->respondError('Invalid request key.', 401);
        }

        return true;
    }

    public function decrement($key)
    {
        Cache::decrement($key);
    }

}
