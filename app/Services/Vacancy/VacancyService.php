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
    ) {
        return $this->vacancyRepository->getVacanciesList(
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

    public function getVacancy($vacancyId)
    {
        return $this->vacancyRepository->getVacancy($vacancyId);
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

    public function updateVacancy(
        $id,
        $title,
        $description,
        $salary,
        $isHomeOffice,
        $occupation,
        $cityId,
        $hiringMode
    ) {
        return $this->vacancyRepository->updateVacancy(
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
}
