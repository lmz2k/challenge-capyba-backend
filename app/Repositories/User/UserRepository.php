<?php


namespace App\Repositories\User;


use App\Models\User;

class UserRepository implements UserRepositoryInterface
{

    /**
     * @param $email
     * @return mixed
     */
    public function findUserByEmail($email)
    {
        return User::where('email', $email)
            ->firstOrFail();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findUserById($id)
    {
        return User::where('id', $id)
            ->firstOrFail();
    }
}
