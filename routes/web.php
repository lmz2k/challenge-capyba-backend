<?php

/** @var \Laravel\Lumen\Routing\Router $router */


$router->group(['prefix' => 'api'], function () use ($router) {

    $router->get('/check', function () use ($router) {
        return $router->app->version();
    });

    $router->group(['prefix' => 'vacancy'], function () use ($router){
        $router->get('/', 'VacanciesController@show');
    });
});
