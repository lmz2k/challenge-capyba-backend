<?php

/** @var \Laravel\Lumen\Routing\Router $router */


$router->group(['prefix' => 'api/auth/code'], function () use ($router) {
        $router->post('/resend', 'AuthController@resendCode');
        $router->group(['middleware' => 'code',], function () use ($router) {
                $router->post('/confirm', 'AuthController@confirmCode');
        });
});

