<?php

namespace Tests\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestDatabaseCreationHelper
{

    public function createTestUser(
        $name,
        $email,
        $password = '123mudar@@',
        $verified = 0
    ): User {
        $user = new User();

        $user->name = $name ?? '';
        $user->email = $email ?? '';
        $user->password = Hash::make($password);
        $user->photo = '...';
        $user->verified = $verified;

        $user->save();

        return $user;
    }

    public function updateUser($userId, $column, $value)
    {
        $user = new User();

        $user->exists = true;
        $user->id = $userId;
        $user->{$column} = $value;

        $user->save();
    }
}
