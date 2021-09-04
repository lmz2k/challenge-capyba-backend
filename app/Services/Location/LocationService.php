<?php


namespace App\Services\Location;


use App\Repositories\Location\LocationRepositoryInterface;

class LocationService implements LocationServiceInterface
{
    private LocationRepositoryInterface $locationRepository;

    /**
     * LocationService constructor.
     * @param LocationRepositoryInterface $locationRepository
     */
    public function __construct(LocationRepositoryInterface $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    /**
     * @param $search
     * @param $page
     * @param $perPage
     * @return mixed
     */
    public function searchCity($search, $page, $perPage)
    {
        return $this->locationRepository->searchCity($search, $page, $perPage);
    }
}
