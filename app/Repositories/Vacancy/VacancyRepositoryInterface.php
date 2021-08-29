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

    public function getVacancy($vacancyId);

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

    public function updateVacancy(
        $id,
        $title,
        $description,
        $salary,
        $isHomeOffice,
        $occupation,
        $cityId,
        $hiringMode
    );
}
