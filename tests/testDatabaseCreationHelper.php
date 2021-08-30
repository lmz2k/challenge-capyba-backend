<?php

use App\Services\Hash\HashServiceInterface;
use Faker\Factory as Faker;

class testDatabaseCreationHelper
{
    private Faker $faker;
    private HashServiceInterface $hashService;

    public function __construct(HashServiceInterface $hashService)
    {
        $this->hashService = $hashService;
        $this->faker = Faker::create();
    }

    public function createTestUser(
        $name,
        $email,
        $password,
        $verified
    ): User
    {
        $user = new User();

        $user->name = $name ?? $this->faker->name;
        $user->email = $email ?? $this->faker->email;
        $user->password = $this->hashService->create($password ?? '123mudar@@');
        $user->verified = $verified ?? 0;

        $user->save();

        return $user;
    }
}
