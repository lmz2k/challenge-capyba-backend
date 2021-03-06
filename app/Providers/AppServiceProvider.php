<?php

namespace App\Providers;

use App\Repositories\Auth\AuthRepository;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Repositories\Location\LocationRepository;
use App\Repositories\Location\LocationRepositoryInterface;
use App\Repositories\Profile\ProfileRepository;
use App\Repositories\Profile\ProfileRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Auth\AuthService;
use App\Services\Auth\AuthServiceInterface;
use App\Services\Ftp\FtpService;
use App\Services\Ftp\FtpServiceInterface;
use App\Services\Hash\HashService;
use App\Services\Hash\HashServiceInterface;
use App\Services\Jwt\JwtService;
use App\Services\Jwt\JwtServiceInterface;
use App\Services\Location\LocationService;
use App\Services\Location\LocationServiceInterface;
use App\Services\Mail\MailService;
use App\Services\Mail\MailServiceInterface;
use App\Services\Profile\ProfileService;
use App\Services\Profile\ProfileServiceInterface;
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
            HashServiceInterface::class,
            HashService::class,
        );

        $this->app->bind(
            JwtServiceInterface::class,
            JwtService::class,
        );

        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class,
        );

        $this->app->bind(
            FtpServiceInterface::class,
            FtpService::class,
        );

        $this->app->bind(
            ProfileServiceInterface::class,
            ProfileService::class,
        );

        $this->app->bind(
            ProfileRepositoryInterface::class,
            ProfileRepository::class,
        );

        $this->app->bind(
            LocationRepositoryInterface::class,
            LocationRepository::class,
        );

        $this->app->bind(
            LocationServiceInterface::class,
            LocationService::class,
        );
    }
}
