<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Vacancy\VacancyService;
use App\Services\Vacancy\VacancyServiceInterface;
use App\Repositories\Vacancy\VacancyRepository;
use App\Repositories\Vacancy\VacancyRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind(
            VacancyServiceInterface::class,
            VacancyService::class,
        );

        $this->app->bind(
            VacancyRepositoryInterface::class,
            VacancyRepository::class,
        );
    }
}
