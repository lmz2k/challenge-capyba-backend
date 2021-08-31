<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\Helpers\TestDatabaseCreationHelper;


class AuthTest extends TestCase
{
    use DatabaseTransactions;

    private $url = '/api/auth';
    private TestDatabaseCreationHelper $testDatabaseCreationHelper;
    protected function setUp(): void
    {
        parent::setUp();
        $this->testDatabaseCreationHelper = new TestDatabaseCreationHelper();
    }

    public function testSuccessUserRegister()
    {
        $name = 'Gabriel';
        $email = 'contact@glima.me';
        $password = '123mudar@@';
        $photo = UploadedFile::fake()->create('image.jpg', 1024);

        $response = $this->call(
            'POST',
            ($this->url . '/register'),
            [
                'name' => $name,
                'email' => $email,
                'password' => $password
            ],
            [],
            ['photo' => $photo,],
            []
        );

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(1, User::all()->count());
    }

    public function testRegisterUserWithSameEmail()
    {
        $name = 'Gabriel';
        $email = 'contact@glima.me';
        $password = '123mudar@@';
        $photo = UploadedFile::fake()->create('image.jpg', 1024);

        $this->testDatabaseCreationHelper->createTestUser($name, $email, $password);

        $response = $this->call(
            'POST',
            ($this->url . '/register'),
            [
                'name' => $name,
                'email' => $email,
                'password' => $password
            ],
            [],
            ['photo' => $photo,],
            []
        );

        $this->assertEquals(401, $response->getStatusCode());
    }
}
