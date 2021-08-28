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

    public function show()
    {
        return $this->vacancyRepository->show();
    }
}
