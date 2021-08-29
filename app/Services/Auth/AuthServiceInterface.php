<?php


namespace App\Services\Auth;


interface AuthServiceInterface
{
    public function register($name, $email, $password, $photo);
}
