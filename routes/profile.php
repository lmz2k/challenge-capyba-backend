<?php

/** @var \Laravel\Lumen\Routing\Router $router */


$router->group(['prefix' => 'api/profile', 'middleware' => 'auth'], function () use ($router) {
        $router->post('/', 'ProfileController@update');
        $router->post('/password', 'ProfileController@changePassword');
});

