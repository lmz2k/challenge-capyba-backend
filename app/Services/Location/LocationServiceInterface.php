<?php


namespace App\Services\Location;


interface LocationServiceInterface
{
    public function searchCity($search, $page, $perPage);
}
