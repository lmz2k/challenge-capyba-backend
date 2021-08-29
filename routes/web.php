<?php

/** @var \Laravel\Lumen\Routing\Router $router */


$router->group(['prefix' => 'api'], function () use ($router) {
        $router->get('/check', function () use ($router) {
                return $router->app->version();
            }
        );

        $router->group(['prefix' => 'auth'], function () use ($router) {
            $router->post('/register', 'AuthController@register');
            $router->post('/login', 'VacanciesController@getVacanciesList');
            $router->post('/logout', 'VacanciesController@getVacanciesList');
        });

        $router->group(['prefix' => 'vacancy'], function () use ($router) {
                $router->get('/', 'VacanciesController@getVacanciesList');
                $router->get('/{id}', 'VacanciesController@getVacancy');
                $router->post('/', 'VacanciesController@createVacancy');
                $router->put('/{id}', 'VacanciesController@updateVacancy');
            }
        );
    }
);
