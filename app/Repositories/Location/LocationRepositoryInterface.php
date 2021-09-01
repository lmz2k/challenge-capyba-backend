<?php

namespace App\Repositories\Location;

interface LocationRepositoryInterface
{
    public function searchCity($search, $page, $perPage);
}
