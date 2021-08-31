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

    public function updateUser($id, $attributes)
    {
        $user = new User();
        $user->id = $id;
        $user->exists = true;

        foreach ($attributes as $key =>$value){
            $user->{$key} = $value;
        }

        $user->save();
        return $user;
    }
}
