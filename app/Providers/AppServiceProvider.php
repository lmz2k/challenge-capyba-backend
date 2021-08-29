<?php

namespace App\Providers;

use App\Repositories\Auth\AuthRepository;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Services\Auth\AuthService;
use App\Services\Auth\AuthServiceInterface;
use App\Services\Ftp\FtpService;
use App\Services\Ftp\FtpServiceInterface;
use App\Services\Mail\MailService;
use App\Services\Mail\MailServiceInterface;
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

        $this->app->bind(
            AuthServiceInterface::class,
            AuthService::class,
        );

        $this->app->bind(
            AuthRepositoryInterface::class,
            AuthRepository::class,
        );

        $this->app->bind(
            MailServiceInterface::class,
            MailService::class,
        );

        $this->app->bind(
            FtpServiceInterface::class,
            FtpService::class,
        );
    }
}
