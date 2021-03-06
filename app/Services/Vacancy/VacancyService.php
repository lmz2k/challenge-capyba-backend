<?php


namespace App\Services\Vacancy;

use App\Repositories\Vacancy\VacancyRepositoryInterface;

class VacancyService implements VacancyServiceInterface
{
    private VacancyRepositoryInterface $vacancyRepository;

    /**
     * VacancyService constructor.
     * @param VacancyRepositoryInterface $vacancyRepository
     */
    public function __construct(VacancyRepositoryInterface $vacancyRepository)
    {
        $this->vacancyRepository = $vacancyRepository;
    }

    /**
     * @param $userId
     * @param $title
     * @param $description
     * @param $salary
     * @param $isHomeOffice
     * @param $occupation
     * @param $cityId
     * @param $hiringMode
     * @return mixed
     */
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
     * @param $userId
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
