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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function () use ($router){
    // Matches "/api/register
    $router->post('register', 'AuthController@register');
    // Matches "/api/login
    $router->post('login', 'AuthController@login');

    // User routes group
    $router->group(['prefix' => 'user'], function () use ($router){
        $router->get('/', 'UserController@index');    
        $router->get('/profile', 'UserController@profile');
        $router->get('/{id}', 'UserController@singleUser');
        $router->put('/update/{id}', 'UserController@updateUser');
    });
});