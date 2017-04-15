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

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->group(['prefix' => 'api/v1'], function($app) {
    $app->group(['prefix' => 'users'], function($app) {
        $app->get('/me', [
            'middleware' => 'auth',
            'uses' => 'UserController@me'
        ]);
        $app->post('/login', 'UserController@login');
        $app->post('/register', 'UserController@register');
    });
});
