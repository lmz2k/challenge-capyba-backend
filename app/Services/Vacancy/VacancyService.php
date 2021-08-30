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

    /**
     * @param $search
     * @param $hiringMode
     * @param $occupation
     * @param $isHomeOffice
     * @param $cityId
     * @param $salary
     * @param $createdAt
     * @param $page
     * @param $perPage
     * @return mixed
     */
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

    /**
     * @param $vacancyId
     * @return mixed
     */
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

    /**
     * @param $id
     * @param $title
     * @param $description
     * @param $salary
     * @param $isHomeOffice
     * @param $occupation
     * @param $cityId
     * @param $hiringMode
     * @return mixed
     */
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
