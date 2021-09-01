<?php

/** @var \Laravel\Lumen\Routing\Router $router */


$router->group(['prefix' => 'api'], function () use ($router) {
        $router->get('/check', function () use ($router) {
                return $router->app->version();
            }
        );

        $router->group(['prefix' => 'auth'], function () use ($router) {
            $router->post('/register', 'AuthController@register');
            $router->post('/login', 'AuthController@login');
            $router->post('/logout', 'VacanciesController@getVacanciesList');

            $router->group(['prefix' => 'code'], function () use ($router) {
                $router->post('/resend', 'AuthController@resendCode');
                $router->post('/confirm', [
                        'middleware' => 'code',
                        'uses' => 'AuthController@confirmCode'
                    ]);
                }
            );
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

