<?php


namespace App\Services\Vacancy;


interface VacancyServiceInterface
{
    public function getList(
        $userId,
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
