<?php


namespace App\Services\Jwt;


interface JwtServiceInterface
{
    public function create($object);

    public function validate($jwt);

    public function decode($jwt);

}
