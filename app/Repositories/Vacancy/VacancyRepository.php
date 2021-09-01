<?php


namespace App\Repositories\Vacancy;

use App\Models\Vacancy;
use Illuminate\Support\Facades\DB;

class VacancyRepository implements VacancyRepositoryInterface
{
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
     * @param null $userId
     * @return mixed
     */
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
    ) {
        $query = Vacancy::leftJoin('users', 'users.id', '=', 'vacancies.announcement_by')
            ->leftJoin('cities', 'cities.id', '=', 'vacancies.city_id')
            ->leftJoin('states', 'states.id', '=', 'cities.state_id');

        $this->filterListByUser($query, $userId);
        $this->filterListBySearch($query, $search);
        $this->filterListByHiringMode($query, $hiringMode);
        $this->filterListByOccupation($query, $occupation);
        $this->filterListByLocal($query, $isHomeOffice, $cityId);
        $this->orderListByColumn($query, $salary, 'salary');
        $this->orderListByColumn($query, $createdAt, 'created_at');


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
            $page
        );
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
     * @return Vacancy
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
    ): Vacancy {
        $vacancy = new Vacancy();

        $vacancy->title = $title;
        $vacancy->description = $description;
        $vacancy->salary = $salary;
        $vacancy->is_home_office = $isHomeOffice;
        $vacancy->occupation = $occupation;
        $vacancy->city_id = $cityId;
        $vacancy->hiring_mode = $hiringMode;
        $vacancy->announcement_by = $userId;

        $vacancy->save();

        return $vacancy;
    }


    /**
     * @param $query
     * @param $search
     */
    private function filterListBySearch(&$query, $search)
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

    /**
     * @param $query
     * @param $hiringMode
     */
    private function filterListByHiringMode(&$query, $hiringMode)
    {
        if (!isset($hiringMode)) {
            return;
        }

        if ($hiringMode !== Vacancy::BOTH_HIRING) {
            $query->where('vacancies.hiring_mode', '=', $hiringMode);
        }
    }

    /**
     * @param $query
     * @param $userId
     */
    private function filterListByUser(&$query, $userId)
    {
        if (!isset($userId)) {
            return;
        }
        $query->where('vacancies.announcement_by', '=', $userId);
    }

    /**
     * @param $query
     * @param $occupation
     */
    private function filterListByOccupation(&$query, $occupation)
    {
        if (!isset($occupation)) {
            return;
        }

        $query->where('vacancies.occupation', '=', $occupation);
    }

    /**
     * @param $query
     * @param $isHomeOffice
     * @param $cityId
     */
    private function filterListByLocal(&$query, $isHomeOffice, $cityId)
    {
        if (isset($isHomeOffice)) {
            $query->where('vacancies.is_home_office', '=', $isHomeOffice);
        }

        if (isset($cityId)) {
            $query->where('vacancies.city_id', '=', $cityId);
        }
    }

    /**
     * @param $query
     * @param $order
     * @param $column
     */
    private function orderListByColumn(&$query, $order, $column)
    {
        $selectedOrder = $order ?? 'asc';

        $query->orderBy('vacancies.' . $column, $selectedOrder);
    }
}
