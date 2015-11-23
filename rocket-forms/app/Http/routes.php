<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/*
 * By default, we'll assume the URL to access Rocket Forms is /rocket-forms. But in case this
 * needs to be customized, look for an environment variable first.
 */
$rocketFormsRoutePrefix = getenv('ROCKET_FORMS_INSTALL_PATH') ?: 'rocket-forms';

$app->group(['prefix' => $rocketFormsRoutePrefix, 'namespace' => 'App\Http\Controllers',], function ($app)
{
    $app->post('/submission', ['uses' => 'FormsController@processFormData']);
    $app->get('/request-key', ['uses' => 'FormsController@getRequestKey']);
});
