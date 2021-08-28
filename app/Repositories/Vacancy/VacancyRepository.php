<?php


namespace App\Repositories\Vacancy;


class VacancyRepository implements VacancyRepositoryInterface
{
    public function show(): array
    {
        return ['hey' => 'tei'];
    }
}
