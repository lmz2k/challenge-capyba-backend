<?php


namespace App\Repositories\Profile;


use App\Models\User;

class ProfileRepository implements ProfileRepositoryInterface
{

    public function update($userId, $attributes): User
    {
        $user = new User();
        $user->id = $userId;
        $user->exists = true;

        foreach ($attributes as $key => $value) {
            $user->{$key} = $value;
        }

        $user->save();

        return $user;
    }

    public function changePassword($userId, $newPassword): User
    {
        $user = new User();

        $user->id = $userId;
        $user->exists = true;
        $user->password = $newPassword;

        $user->save();
        return $user;
    }
}
