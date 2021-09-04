<?php


namespace App\Repositories\User;


use App\Models\User;

class UserRepository implements UserRepositoryInterface
{

    public function findUserByAttribute($attribute, $value)
    {
        return User::where($attribute, $value)
            ->firstOrFail();
    }

    public function updateUser($id, $attributes): User
    {
        $user = new User();
        $user->id = $id;
        $user->exists = true;

        foreach ($attributes as $key => $value) {
            $user->{$key} = $value;
        }

        $user->save();
        return $user;
    }
}
