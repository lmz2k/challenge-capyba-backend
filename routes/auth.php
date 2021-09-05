<?php

/** @var \Laravel\Lumen\Routing\Router $router */


$router->group(['prefix' => 'api/auth'], function () use ($router) {

        $router->post('/register', 'AuthController@register');
        $router->post('/login', 'AuthController@login');
        $router->get('/privacy/policy', 'AuthController@privacyPolicy');

        $router->group(['middleware' => 'auth'], function () use ($router) {
                $router->post('/logout', 'AuthController@logout');
        });
    }
);

