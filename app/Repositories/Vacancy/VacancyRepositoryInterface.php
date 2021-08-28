<?php


namespace App\Repositories\Vacancy;


interface VacancyRepositoryInterface
{
    public function getVacanciesList(
        $search,
        $hiringMode,
        $occupation,
        $isHomeOffice,
        $cityId,
        $salary,
        $createdAt,
        $page,
        $perPage
    );
}
