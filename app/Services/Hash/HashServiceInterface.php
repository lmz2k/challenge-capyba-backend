<?php


namespace App\Services\Hash;


interface HashServiceInterface
{
    public function create($value);

    public function validate($hash, $value);
}
