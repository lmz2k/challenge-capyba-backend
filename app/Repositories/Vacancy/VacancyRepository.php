<?php


namespace App\Repositories\Vacancy;

use App\Models\Vacancy;
use Illuminate\Support\Facades\DB;

class VacancyRepository implements VacancyRepositoryInterface
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
    ) {
        $query = Vacancy::leftJoin('users', 'users.id', '=', 'vacancies.announcement_by')
            ->leftJoin('cities', 'cities.id', '=', 'vacancies.city_id')
            ->leftJoin('states', 'states.id', '=', 'cities.state_id');

        $this->filterBySearch($query, $search);
        $this->filterByHiringMode($query, $hiringMode);
        $this->filterByOccupation($query, $occupation);
        $this->filterByLocal($query, $isHomeOffice, $cityId);
        $this->orderByColumn($query, $salary, 'salary');
        $this->orderByColumn($query, $createdAt, 'created_at');


        return $query->paginate(
            $perPage,
            [
                'vacancies.id as id',
                'vacancies.title as title',
                'vacancies.description as description',
                'vacancies.is_home_office as home_office',
                'vacancies.occupation as occupation',
                'vacancies.salary as salary',
                'cities.name as city',
                'states.name as state',
                DB::raw(
                    "CASE
                        WHEN vacancies.hiring_mode = 'BOTH'
                        THEN 'PJ E CLT'
                        ELSE vacancies.hiring_mode
                     END as hiring_mode"
                )
            ],
            'page',
            $page);
    }

    private function filterBySearch(&$query, $search)
    {
        if (!isset($search)) {
            return;
        }

        $query->where(
            function ($subQuery) use ($search) {
                $subQuery->where('vacancies.title', 'like', '%' . $search . '%')
                    ->orWhere('vacancies.description', 'like', '%' . $search . '%')
                    ->orWhere('cities.name', 'like', '%' . $search . '%');
            }
        );
    }

    private function filterByHiringMode(&$query, $hiringMode)
    {
        if (!isset($hiringMode)) {
            return;
        }

        if ($hiringMode !== Vacancy::BOTH_HIRING) {
            $query->where('vacancies.hiring_mode', '=', $hiringMode);
        }
    }

    private function filterByOccupation(&$query, $occupation)
    {
        if (!isset($occupation)) {
            return;
        }

        $query->where('vacancies.occupation', '=', $occupation);
    }

    private function filterByLocal(&$query, $isHomeOffice, $cityId)
    {
        if (isset($isHomeOffice)) {
            $query->where('vacancies.is_home_office', '=', $isHomeOffice);
        }

        if (isset($cityId)) {
            $query->where('vacancies.city_id', '=', $cityId);
        }
    }

    private function orderByColumn(&$query, $order, $column)
    {
        $selectedOrder = $order ?? 'asc';

        $query->orderBy('vacancies.'.$column, $selectedOrder);
    }
}
