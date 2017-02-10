<?php

/*
 * By default, we'll assume the URL to access Rocket Forms is /rocket-forms. But in case this
 * needs to be customized, look for an environment variable first.
 */
$rocketFormsRoutePrefix = env('ROCKET_FORMS_INSTALL_PATH') ?: 'rocket-forms';
$app->group(['prefix' => $rocketFormsRoutePrefix, 'namespace' => 'App\Http\Controllers'], function ($app)
{
    $app->post('/submission', ['as' => 'submit', 'uses' => 'FormController@process', 'middleware' => ['verifyRequestKey', 'verifyFormId']]);
    $app->post('/payment', ['as' => 'payment', 'uses' => 'PaymentController@process', 'middleware' => 'verifyRequestKey']);
    $app->get('/request-key', ['as' => 'request', 'uses' => 'RequestController@getKey', 'middleware' => 'verifyApiKey']);
});
