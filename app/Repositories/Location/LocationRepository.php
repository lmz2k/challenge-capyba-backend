<?php


namespace App\Repositories\Location;


use App\Models\City;

class LocationRepository implements LocationRepositoryInterface
{

    public function searchCity($search, $page, $perPage)
    {
        return City::where('cities.name', 'like', '%' . $search . '%')
            ->join('states', 'states.id', '=', 'cities.state_id')
            ->paginate(
                $perPage,
                [
                    'cities.id as city_id',
                    'cities.name as city_name',
                    'states.id as state_id',
                    'states.name as state_name'
                ],
                'page',
                $page
            );
    }
}
