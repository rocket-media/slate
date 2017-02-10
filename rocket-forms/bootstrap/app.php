<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../vendor/rollbar/rollbar/src/rollbar.php';

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);

$app->withFacades();
// $app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

// Add custom dotenv requirements. We make sure to add these after the
// exception handlers (above) have been registered, so that they handle any
// exceptions thrown
$dotenv = new Dotenv\Dotenv(__DIR__.'/../');
$dotenv->load();
$dotenv->required('FORM_ID_HEADER')->notEmpty();
$dotenv->required('REQUEST_KEY_HEADER')->notEmpty();
$dotenv->required('ROCKET_FORMS_API_KEY')->notEmpty();
$dotenv->required('WUFOO_API_KEY')->notEmpty();
$dotenv->required('WUFOO_SUBDOMAIN')->notEmpty();

/*
|--------------------------------------------------------------------------
| Rollbar error reporting
|--------------------------------------------------------------------------
*/
$config = array(
    // required
    'access_token' => 'aa1d7fa0f4044a908aed171d1670b17f',
    // optional - environment name. any string will do.
    'environment' => app()->environment(),
    // optional - path to directory your code is in. used for linking stack traces.
    'root' => $_SERVER['DOCUMENT_ROOT']
);
$set_exception_handler = false;
$set_error_handler = false;
if (app()->environment() === 'production') {
    Rollbar::init($config, $set_exception_handler, $set_error_handler);
}

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

// $app->middleware([
//      Illuminate\Cookie\Middleware\EncryptCookies::class,
//      Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
//      Illuminate\Session\Middleware\StartSession::class,
//      Illuminate\View\Middleware\ShareErrorsFromSession::class,
//      Laravel\Lumen\Http\Middleware\VerifyCsrfToken::class,
// ]);

$app->routeMiddleware([
    'verifyRequestKey' => App\Http\Middleware\VerifyRequestKey::class,
    'verifyApiKey' => App\Http\Middleware\VerifyApiKey::class,
    'verifyFormId' => App\Http\Middleware\VerifyFormId::class,
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

// $app->register(App\Providers\AppServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->group(['namespace' => 'App\Http\Controllers'], function ($app) {
    require __DIR__.'/../app/Http/routes.php';
});

return $app;
