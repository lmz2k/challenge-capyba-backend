<?php

/** @var \Laravel\Lumen\Routing\Router $router */


$router->group(['prefix' => 'api'], function () use ($router) {

        $router->group(['prefix' => 'auth'], function () use ($router) {
            $router->post('/register', 'AuthController@register');
            $router->post('/login', 'AuthController@login');
            $router->post('/logout', 'VacanciesController@getVacanciesList');

            $router->group(['prefix' => 'code'], function () use ($router) {
                $router->post('/resend', 'AuthController@resendCode');

                $router->group(['middleware' => 'code',], function () use ($router) {
                    $router->post('/confirm', 'AuthController@confirmCode');
                });

            });
        });

        $router->group(['middleware' => 'auth', 'prefix' => 'profile'], function () use ($router) {
            $router->post('/', 'ProfileController@update');
        });


        $router->group(['prefix' => 'vacancy'], function () use ($router) {
            $router->get('/', 'VacanciesController@getVacancies');

            $router->group(['middleware' => 'auth'], function () use ($router) {
                    $router->post('/', 'VacanciesController@createVacancy');
                    $router->get('/my', 'VacanciesController@getVacancies');
                }
            );
        });
    }
);

