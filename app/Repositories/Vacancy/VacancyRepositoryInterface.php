<?php


namespace App\Repositories\Vacancy;


interface VacancyRepositoryInterface
{
    public function getList(
        $search,
        $hiringMode,
        $occupation,
        $isHomeOffice,
        $cityId,
        $salary,
        $createdAt,
        $page,
        $perPage,
        $userId = null
    );

    public function createVacancy(
        $userId,
        $title,
        $description,
        $salary,
        $isHomeOffice,
        $occupation,
        $cityId,
        $hiringMode
    );
}
