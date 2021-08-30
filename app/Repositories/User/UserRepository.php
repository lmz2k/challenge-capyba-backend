<?php


namespace App\Repositories\User;


use App\Models\User;

class UserRepository implements UserRepositoryInterface
{

    public function findUserByEmail($email)
    {
        return User::where('email', $email)
            ->firstOrFail();
    }

    public function findUserById($id)
    {
        return User::where('id', $id)
            ->firstOrFail();
    }
}
