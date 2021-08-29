<?php


namespace App\Repositories\Auth;


use App\Models\User;

class AuthRepository implements AuthRepositoryInterface
{

    public function register($name, $email, $passwordHash, $photoPath): User
    {
        $user = new User();

        $user->name = $name;
        $user->email = $email;
        $user->password = $passwordHash;
        $user->photo = $photoPath;
        $user->verified = 0;

        $user->save();

        return $user;
    }
}
