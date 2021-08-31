<?php
namespace Tests\Helpers;

use App\Models\User;

class TestDatabaseCreationHelper
{

    public function createTestUser(
        $name,
        $email,
        $password = '123mudar@@',
        $verified = 0
    ): User
    {
        $user = new User();

        $user->name = $name ?? '';
        $user->email = $email ?? '';
        $user->password = $password;
        $user->photo = '...';
        $user->verified = $verified;

        $user->save();

        return $user;
    }
}
