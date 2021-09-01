<?php


namespace App\Services\Vacancy;

use App\Repositories\Vacancy\VacancyRepositoryInterface;

class VacancyService implements VacancyServiceInterface
{
    private VacancyRepositoryInterface $vacancyRepository;

    public function __construct(VacancyRepositoryInterface $vacancyRepository)
    {
        $this->vacancyRepository = $vacancyRepository;
    }

    public function createVacancy(
        $userId,
        $title,
        $description,
        $salary,
        $isHomeOffice,
        $occupation,
        $cityId,
        $hiringMode
    ){
        return $this->vacancyRepository->createVacancy(
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
    ) {
        return $this->vacancyRepository->getList(
            $search,
            $hiringMode,
            $occupation,
            $isHomeOffice,
            $cityId,
            $salary,
            $createdAt,
            $page,
            $perPage,
            $userId,
        );
    }
}
