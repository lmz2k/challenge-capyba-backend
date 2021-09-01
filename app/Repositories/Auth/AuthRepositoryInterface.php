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

    public function registerCodeValidation($userId, $codeHash, $token);

    public function invalidateOldCodes($userId);

    public function trackToken($jwt);

    public function getRegisterConfirmFromToken($token);

    public function logout($token);
}
