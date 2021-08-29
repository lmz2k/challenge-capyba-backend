<?php


namespace App\Repositories\Auth;


interface AuthRepositoryInterface
{
    public function register(
        $name,
        $email,
        $passwordHash,
        $photoPath
    );
}
