<?php

/** @var \Laravel\Lumen\Routing\Router $router */


$router->group(['prefix' => 'api/vacancy'], function () use ($router) {
        $router->get('/', 'VacanciesController@getVacancies');

        $router->group(['middleware' => 'auth'], function () use ($router) {
                $router->post('/', 'VacanciesController@createVacancy');
                $router->get('/my', 'VacanciesController@getVacancies');
            }
        );
    }
);

